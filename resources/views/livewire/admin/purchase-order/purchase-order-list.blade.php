<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-header-title">{{translate('messages.purchase_orders')}}</h5>
            <div class="search--button-wrapper">
                <div class="input-group">
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="{{translate('messages.search')}}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="tio-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('messages.sl')}}</th>
                            <th>{{translate('messages.order')}} {{translate('messages.id')}}</th>
                            <th>{{translate('messages.store')}}</th>
                            <th>{{translate('messages.total')}}</th>
                            <th>{{translate('messages.payment_status')}}</th>
                            <th>{{translate('messages.status')}}</th>
                            <th>{{translate('messages.created_at')}}</th>
                            <th class="text-center">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $key => $order)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.purchase-order.show', $order->id) }}">#{{ $order->id }}</a>
                                </td>
                                <td>{{ $order->store ? $order->store->name : 'N/A' }}</td>
                                <td>{{ \App\CentralLogics\Helpers::format_currency($order->total_amount) }}</td>
                                <td>
                                    <span class="badge badge-soft-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'partial' ? 'info' : 'warning') }}">
                                        {{ ucfirst($order->payment_status ?? 'unpaid') }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge badge-soft-info">{{translate('messages.pending')}}</span>
                                    @elseif($order->status == 'approved')
                                        <span class="badge badge-soft-success">{{translate('messages.approved')}}</span>
                                    @elseif($order->status == 'rejected')
                                        <span class="badge badge-soft-danger">{{translate('messages.rejected')}}</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge badge-soft-success">{{translate('messages.completed')}}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                            href="{{ route('admin.purchase-order.show', $order->id) }}"
                                            title="{{translate('messages.view')}}">
                                            <i class="tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($purchaseOrders) == 0)
                            <tr>
                                <td colspan="8" class="text-center">{{translate('messages.no_data_found')}}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>
