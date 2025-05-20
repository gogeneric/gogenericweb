<?php

namespace App\Livewire\Vendor\PurchaseOrder;

use App\CentralLogics\Helpers;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderList extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public function render()
    {
        $purchaseOrders = PurchaseOrder::where('store_id', Helpers::get_store_id())
            ->latest()
            ->paginate(10);
            
        return view('livewire.vendor.purchase-order.purchase-order-list', [
            'purchaseOrders' => $purchaseOrders
        ]);
    }
}
