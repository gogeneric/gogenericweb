<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\ItemStockTransaction;
use App\Models\Item;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::where('store_id', auth('vendor')->user()->store->id)
            ->latest()
            ->paginate(15);
        return view('vendor-views.purchase-order.index', compact('purchaseOrders'));
    }

    public function create()
    {
        return view('vendor-views.purchase-order.create');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->store_id != auth('vendor')->user()->store->id) {
            abort(403, 'Unauthorized');
        }

        $stockTransactions = ItemStockTransaction::where('purchase_order_id', $purchaseOrder->id)
            ->with('item')
            ->latest()
            ->get();

        return view('vendor-views.purchase-order.show', compact('purchaseOrder', 'stockTransactions'));
    }

    public function payment(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->store_id != auth('vendor')->user()->store->id) {
            abort(403, 'Unauthorized');
        }
        return view('vendor-views.purchase-order.payment', compact('purchaseOrder'));
    }

    public function receiveItems(Request $request, PurchaseOrder $purchaseOrder)
    {
        // This functionality has been moved to the Livewire component
        // Keeping this method for backward compatibility
        if ($purchaseOrder->store_id !== auth('vendor')->user()->store->id) {
            abort(403, 'Unauthorized');
        }

        if ($purchaseOrder->status !== 'completed') {
            Toastr::error('Cannot receive items for a purchase order that is not completed');
            return back();
        }

        // Check if items have already been received
        if (ItemStockTransaction::where('purchase_order_id', $purchaseOrder->id)->exists()) {
            Toastr::error('Items have already been received for this purchase order');
            return back();
        }

        $result = $purchaseOrder->processStockTransactions();

        if ($result) {
            Toastr::success('Items received and added to inventory successfully');
        } else {
            Toastr::error('Failed to process stock transactions');
        }

        return back();
    }
}
