<?php

namespace App\Livewire\Admin\PurchaseOrder;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseOrderList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $status = '';
    public $paymentStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PurchaseOrder::with('store')
            ->when($this->search, function($query) {
                $query->where('id', 'like', "%{$this->search}%")
                    ->orWhereHas('store', function($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->when($this->paymentStatus, function($query) {
                $query->where('payment_status', $this->paymentStatus);
            })
            ->latest();

        $purchaseOrders = $query->paginate(10);

        return view('livewire.admin.purchase-order.purchase-order-list', [
            'purchaseOrders' => $purchaseOrders
        ]);
    }
}
