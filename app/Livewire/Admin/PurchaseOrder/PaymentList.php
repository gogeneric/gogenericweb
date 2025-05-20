<?php

namespace App\Livewire\Admin\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPayment;
use Livewire\Component;

class PaymentList extends Component
{
    public PurchaseOrder $purchaseOrder;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function render()
    {
        return view('livewire.admin.purchase-order.payment-list', [
            'payments' => $this->purchaseOrder->payments()->with('purchaseOrder')->latest()->get()
        ]);
    }

    public function approvePayment($paymentId)
    {
        $payment = PurchaseOrderPayment::findOrFail($paymentId);
        $payment->update(['status' => 'approved']);

        // Update purchase order paid amount
        $this->purchaseOrder->updatePaymentStatus();

        $this->dispatch('notify', 'Payment approved successfully!');
    }

    public function rejectPayment($paymentId)
    {
        $payment = PurchaseOrderPayment::findOrFail($paymentId);
        $payment->update(['status' => 'rejected']);

        // Update purchase order status
        $this->purchaseOrder->updatePaymentStatus();

        $this->dispatch('notify', 'Payment rejected successfully!');
    }
}
