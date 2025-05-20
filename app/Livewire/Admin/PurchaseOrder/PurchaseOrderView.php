<?php

namespace App\Livewire\Admin\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPayment;
use Livewire\Component;

class PurchaseOrderView extends Component
{
    public PurchaseOrder $purchaseOrder;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function render()
    {
        return view('livewire.admin.purchase-order.purchase-order-view', [
            'purchaseOrder' => $this->purchaseOrder,
            'items' => $this->purchaseOrder->items()->with(['superAdminStock', 'superAdminStock.unit'])->get(),
            'payments' => $this->purchaseOrder->payments()
                ->with('purchaseOrder')
                ->latest()
                ->get()
        ]);
    }

    public function updateStatus($status)
    {
        $this->purchaseOrder->status = $status;
        $this->purchaseOrder->save();

        $this->emit('statusUpdated');
    }

    public function updatePaymentStatus($status)
    {
        $this->purchaseOrder->payment_status = $status;
        $this->purchaseOrder->save();

        $this->emit('paymentStatusUpdated');
    }

    public function approvePayment($paymentId)
    {
        $payment = PurchaseOrderPayment::findOrFail($paymentId);
        $payment->update(['status' => 'approved']);

        // Update purchase order paid amount
        $this->purchaseOrder->paid_amount = $this->purchaseOrder->payments()
            ->where('status', 'approved')
            ->sum('amount');

        $this->purchaseOrder->updatePaymentStatus();
        $this->purchaseOrder->save();

        $this->dispatch('notify', 'Payment approved successfully!');
    }

    public function rejectPayment($paymentId)
    {
        $payment = PurchaseOrderPayment::findOrFail($paymentId);
        $payment->update(['status' => 'rejected']);
        $this->purchaseOrder->updatePaymentStatus();
        $this->dispatchBrowserEvent('notify', 'Payment rejected!');
    }
}
