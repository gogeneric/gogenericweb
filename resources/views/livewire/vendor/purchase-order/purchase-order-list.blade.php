<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Purchase Orders</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->status == 'approved' ? 'success' : ($order->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ \App\CentralLogics\Helpers::format_currency($order->total_amount) }}</td>
                                <td>
                                    @if($order->payment_status)
                                        <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'partial' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vendor.purchase-order.show', $order->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>

                                    @if($order->status == 'approved' && $order->payment_status != 'paid')
                                        <a href="{{ route('vendor.purchase-order.payment', $order->id) }}"
                                           class="btn btn-sm btn-outline-success">
                                            Pay
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No purchase orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    </div>
</div>
