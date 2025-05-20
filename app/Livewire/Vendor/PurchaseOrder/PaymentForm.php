<?php

namespace App\Livewire\Vendor\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPayment;
use App\CentralLogics\Helpers;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class PaymentForm extends Component
{
    use WithFileUploads;

    public PurchaseOrder $purchaseOrder;
    public $amount;
    public $payment_method = 'bank_transfer';
    public $transaction_id;
    public $payment_proof;
    public $notes;

    protected $rules = [
        'amount' => 'required|numeric|min:1',
        'payment_method' => 'required|in:bank_transfer,cash,cheque',
        'transaction_id' => 'required|string|max:255',
        'payment_proof' => 'required|file|mimes:jpg,png,pdf|max:2048',
        'notes' => 'nullable|string|max:500'
    ];

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->amount = $this->purchaseOrder->remaining_balance;
    }

    public function submitPayment()
    {
        $this->validate();

        // Check payment doesn't exceed remaining balance
        if ($this->amount > $this->purchaseOrder->remaining_balance) {
            $this->addError('amount', 'Payment amount exceeds remaining balance');
            return;
        }

        DB::transaction(function () {
            // Store payment proof
            $proofPath = $this->payment_proof->store('payment-proofs', 'public');

            // Create payment record
            $payment = PurchaseOrderPayment::create([
                'purchase_order_id' => $this->purchaseOrder->id,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'transaction_id' => $this->transaction_id,
                'payment_proof' => $proofPath,
                'notes' => $this->notes,
                'status' => 'pending_approval'
            ]);

            // Update purchase order payment status
            $this->purchaseOrder->save();
        });

        // Reset form and show success
        $this->resetExcept('purchaseOrder');
        session()->flash('payment_success', 'Payment submitted successfully! Awaiting admin approval.');
    }

    private function calculatePaymentStatus()
    {
        if ($this->purchaseOrder->paid_amount >= $this->purchaseOrder->total_amount) {
            return 'paid';
        }
        return $this->purchaseOrder->paid_amount > 0 ? 'partial' : 'unpaid';
    }

    public function render()
    {
        return view('livewire.vendor.purchase-order.payment-form', [
            'payments' => $this->purchaseOrder->payments()->latest()->get(),
            'remainingBalance' => $this->purchaseOrder->remaining_balance
        ]);
    }
}
