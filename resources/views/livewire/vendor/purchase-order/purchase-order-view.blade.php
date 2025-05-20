<div>
    <div class="card">
        <div class="card-header bg-light-primary">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mr-3">
                    <h5 class="mb-1">Purchase Order #{{ $purchaseOrder->id }}</h5>
                    <small class="text-muted">Created: {{ $purchaseOrder->created_at->format('M d, Y h:i A') }}</small>
                </div>
                <div class="status-badges">
                    <span class="badge badge-{{ $purchaseOrder->status == 'approved' ? 'success' : ($purchaseOrder->status == 'rejected' ? 'danger' : 'warning') }} badge-lg">
                        {{ ucfirst($purchaseOrder->status) }}
                    </span>
                    @if($purchaseOrder->payment_status)
                        <span class="badge badge-{{ $purchaseOrder->payment_status == 'paid' ? 'success' : ($purchaseOrder->status == 'partial' ? 'info' : 'secondary') }} badge-lg ml-2">
                            Payment: {{ ucfirst($purchaseOrder->payment_status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if($purchaseOrder->status === 'completed' && $purchaseOrder->vendor_status !== 'received')
        <div class="card-alert alert alert-info mb-0 mx-3 mt-3">
            <div class="d-flex align-items-center">
                <i class="tio-info-outined mr-2"></i>
                {{ translate('messages.confirm_physical_receipt') }}
                <button type="button" wire:click="receiveItems" class="btn btn-primary btn-sm ml-auto">
                    <i class="tio-checkmark-circle-outlined mr-1"></i> 
                    {{ translate('messages.confirm_receipt') }}
                </button>
            </div>
        </div>
        @endif

        <div class="card-body">
            <!-- Order Summary Section -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-card">
                        <h6><i class="tio-store"></i> Store Details</h6>
                        <p class="mb-1">{{ $purchaseOrder->store->name }}</p>
                        <p class="mb-1 text-muted small">{{ $purchaseOrder->store->address ?? '' }}</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="info-card">
                        <h6><i class="tio-receipt"></i> Financial Summary</h6>
                        <dl class="row mb-0">
                            <dt class="col-6">Total Amount:</dt>
                            <dd class="col-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->total_amount) }}</dd>
                            
                            <dt class="col-6">Paid Amount:</dt>
                            <dd class="col-6 text-right">{{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->paid_amount) }}</dd>
                            
                            <dt class="col-6 border-top">Remaining:</dt>
                            <dd class="col-6 text-right border-top font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->remaining_balance) }}</dd>
                        </dl>
                        
                        @if($purchaseOrder->estimated_delivery)
                        <div class="mt-3 text-center">
                            <span class="badge badge-soft-info p-2">
                                <i class="tio-calendar mr-1"></i> Estimated Delivery: {{ $purchaseOrder->estimated_delivery->format('M d, Y') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <h6><i class="tio-delivery"></i> Order Actions</h6>
                        @if($purchaseOrder->status == 'approved' && $purchaseOrder->payment_status != 'paid')
                            <a href="{{ route('vendor.purchase-order.payment', $purchaseOrder->id) }}" class="btn btn-primary btn-block mb-2">
                                <i class="tio-money mr-1"></i> Make Payment
                            </a>
                        @endif
                        <a href="{{ route('vendor.purchase-order.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="tio-chevron-left mr-1"></i> Back to Orders
                        </a>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="detailed-table mb-4">
                <h6 class="section-header"><i class="tio-package"></i> Ordered Items</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 40%">Item</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->superAdminStock->image)
                                                <img src="{{ asset('storage/' . $item->superAdminStock->image) }}"
                                                     alt="{{ $item->superAdminStock->name }}"
                                                     class="img-thumbnail mr-3"
                                                     style="width: 60px; height: 60px; object-fit: contain;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->superAdminStock->name }}</h6>
                                                @if($item->superAdminStock->unit)
                                                    <div class="text-muted small">
                                                        Unit: {{ $item->superAdminStock->unit->unit }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ \App\CentralLogics\Helpers::format_currency($item->superAdminStock->price) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">
                                        {{ \App\CentralLogics\Helpers::format_currency($item->quantity * $item->superAdminStock->price) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <th class="text-center">{{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->total_amount) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment History Section -->
            @if($purchaseOrder->payments && $purchaseOrder->payments->count() > 0)
                <div class="payment-history mb-4">
                    <h6 class="section-header"><i class="tio-history"></i> Payment History</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrder->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($payment->amount) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td>
                                            <code>{{ $payment->transaction_id }}</code>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $payment->status_badge_class }}">
                                                {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <!-- Stock Transaction History -->
            @if($stockTransactions && $stockTransactions->count() > 0)
                <div class="stock-history mb-4">
                    <h6 class="section-header"><i class="tio-history"></i> Stock Transaction History</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Previous Stock</th>
                                    <th class="text-center">Change</th>
                                    <th class="text-center">New Stock</th>
                                    <th>Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->item->name }}</td>
                                        <td class="text-center">{{ $transaction->old_stock }}</td>
                                        <td class="text-center {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type === 'credit' ? '+' : '-' }}{{ $transaction->quantity }}
                                        </td>
                                        <td class="text-center font-weight-bold">{{ $transaction->new_stock }}</td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                        <td><small>{{ $transaction->notes }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Notes Section -->
            <div class="row">
                @if($purchaseOrder->notes)
                    <div class="col-md-6">
                        <div class="notes-section mb-4">
                            <h6 class="section-header"><i class="tio-note"></i> Your Notes</h6>
                            <div class="alert alert-info">
                                {!! nl2br(e($purchaseOrder->notes)) !!}
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($purchaseOrder->admin_notes)
                    <div class="col-md-{{ $purchaseOrder->notes ? '6' : '12' }}">
                        <div class="notes-section mb-4">
                            <h6 class="section-header"><i class="tio-message"></i> Admin Notes</h6>
                            <div class="alert alert-secondary">
                                {!! nl2br(e($purchaseOrder->admin_notes)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
