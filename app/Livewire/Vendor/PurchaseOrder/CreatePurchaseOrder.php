<?php

namespace App\Livewire\Vendor\PurchaseOrder;

use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;
use App\Models\SuperAdminStock;
use App\Models\PurchaseOrder;
use App\CentralLogics\Helpers;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class CreatePurchaseOrder extends Component
{
    use WithFileUploads;

    public $search = '';
    public $selectedItems = [];
    public $cartItems = [];

    protected $listeners = ['itemSelected' => 'addToCart'];

    public function render()
    {
        $availableItems = collect();

        if(strlen($this->search) >= 2) {
            $availableItems = SuperAdminStock::with('unit')
                ->where('name', 'like', '%'.$this->search.'%')
                ->where('status', 1)
                ->take(10)
                ->get();
        }

        return view('livewire.vendor.purchase-order.create-purchase-order', [
            'availableItems' => $availableItems,
            'cartItems' => $this->cartItems
        ]);
    }

    public function addToCart($itemId)
    {
        $item = SuperAdminStock::with('unit')->findOrFail($itemId);

        if(!isset($this->cartItems[$itemId])) {
            $this->cartItems[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => 1,
                'image' => $item->image,
                'unit' => $item->unit?->unit
            ];
        }
    }

    public function removeItem($itemId)
    {
        unset($this->cartItems[$itemId]);
    }

    public function updateQuantity($itemId, $quantity)
    {
        $quantity = max(1, (int)$quantity);
        $this->cartItems[$itemId]['quantity'] = $quantity;
    }

    public function submitOrder()
    {
        $this->validate([
            'cartItems' => 'required|array|min:1',
            'cartItems.*.quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::transaction(function () {
                $po = PurchaseOrder::create([
                    'store_id' => Helpers::get_store_id(),
                    'status' => 'pending',
                    'total_amount' => 0
                ]);

                $total = 0;
                foreach ($this->cartItems as $item) {
                    $stock = SuperAdminStock::findOrFail($item['id']);

                    $po->items()->create([
                        'super_admin_stock_id' => $item['id'],
                        'quantity' => $item['quantity']
                    ]);

                    $total += $stock->price * $item['quantity'];
                }

                $po->update(['total_amount' => $total]);
            });

            $this->reset(['search', 'cartItems']);
            Toastr::info('Purchase order submitted successfully!');
            return redirect()->route('vendor.purchase-order.index');
        } catch (\Exception $e) {
            Toastr::error('Error creating purchase order: ' . $e->getMessage());
        }
    }
}
