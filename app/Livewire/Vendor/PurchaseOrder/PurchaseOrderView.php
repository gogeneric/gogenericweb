<?php

namespace App\Livewire\Vendor\PurchaseOrder;

use App\Models\ItemStockTransaction;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class PurchaseOrderView extends Component
{
    public PurchaseOrder $purchaseOrder;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function render()
    {
        // Refresh the purchase order to get the latest data
        $this->purchaseOrder->refresh();

        return view('livewire.vendor.purchase-order.purchase-order-view', [
            'purchaseOrder' => $this->purchaseOrder,
            'items' => $this->purchaseOrder->items()->with(['superAdminStock', 'superAdminStock.unit'])->get(),
            'stockTransactions' => ItemStockTransaction::where('purchase_order_id', $this->purchaseOrder->id)
                ->with('item')
                ->latest()
                ->get()
        ]);
    }

    protected function getListeners()
    {
        return [
            'refreshPurchaseOrder' => 'refreshData',
            'items_received' => 'showSuccessAlert',
            'processing_error' => 'showErrorAlert'
        ];
    }

    public function refreshData()
    {
        $this->purchaseOrder->refresh();
    }
    
    public function receiveItems()
    {
        // Validate admin completion
        if ($this->purchaseOrder->status !== 'completed') {
            Toastr::error(translate('messages.po_completion_required'));
            return;
        }

        // Verify vendor ownership
        if ($this->purchaseOrder->store_id !== auth('vendor')->user()->store->id) {
            Toastr::error(translate('messages.unauthorized_action'));
            return;
        }

        // Prevent duplicate processing
        if ($this->purchaseOrder->vendor_status === 'received') {
            Toastr::error(translate('messages.items_already_received'));
            return;
        }

        try {
            DB::transaction(function () {
                $this->purchaseOrder->processStockTransactions();
                $this->purchaseOrder->update(['vendor_status' => 'received']);
            });
            
            $this->dispatch('items_received');
            $this->purchaseOrder->refresh();
        } catch (\Exception $e) {
            $this->dispatch('processing_error', $e->getMessage());
            Toastr::error(translate('messages.error_processing_receipt') . ': ' . $e->getMessage());
        }
    }
    
    public function showSuccessAlert()
    {
        Toastr::success(translate('messages.items_received_and_stock_updated'));
    }

    public function showErrorAlert($message = null)
    {
        Toastr::error($message ?? translate('messages.error_processing_receipt'));
    }
}
