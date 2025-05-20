<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\SuperAdminStock;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use function json_encode;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchase_orders = PurchaseOrder::latest()->paginate(20);
        return view('admin-views.purchase-order.index', compact('purchase_orders'));
    }

    public function create()
    {
        $stocks = SuperAdminStock::where('status', 1)->get();
        return view('admin-views.purchase-order.create', compact('stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:super_admin_stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $purchase_order = new PurchaseOrder();
        $purchase_order->status = 'pending';
        $purchase_order->save();

        foreach ($request->items as $item) {
            $purchase_order->items()->create([
                'super_admin_stock_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }

        Toastr::success(translate('messages.purchase_order_created_successfully'));
        return redirect()->route('admin.purchase-order.index');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return view('admin-views.purchase-order.show', compact('purchaseOrder'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'estimated_delivery' => 'nullable|date',
            'admin_notes' => 'nullable|string',
        ]);

        $purchaseOrder->update($request->only(['status', 'estimated_delivery', 'admin_notes']));

        Toastr::success(translate('messages.purchase_order_updated_successfully'));
        return redirect()->route('admin.purchase-order.show', $purchaseOrder->id);
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        Toastr::success(translate('messages.purchase_order_deleted_successfully'));
        return redirect()->route('admin.purchase-order.index');
    }

    public function paymentList(PurchaseOrder $purchaseOrder)
    {
        return view('admin-views.purchase-order.payment-list', compact('purchaseOrder'));
    }

    public function approvePayment($paymentId)
    {
        $payment = \App\Models\PurchaseOrderPayment::findOrFail($paymentId);
        $payment->update(['status' => 'approved']);

        // Update purchase order paid amount
        $purchaseOrder = $payment->purchaseOrder;
        $purchaseOrder->updatePaymentStatus();

        Toastr::success(translate('messages.payment_approved_successfully'));
        return back();
    }

    public function rejectPayment($paymentId)
    {
        $payment = \App\Models\PurchaseOrderPayment::findOrFail($paymentId);
        $payment->update(['status' => 'rejected']);

        // Update purchase order status
        $payment->purchaseOrder->updatePaymentStatus();

        Toastr::success(translate('messages.payment_rejected_successfully'));
        return back();
    }
}
