@extends('layouts.admin.app')

@section('title', translate('Purchase Order Details'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-view-list-outlined"></i> {{translate('messages.purchase_order')}} #{{$purchaseOrder->id}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-title">{{translate('messages.purchase_order')}} {{translate('messages.details')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{translate('messages.status')}}:</strong>
                                    @if($purchaseOrder->status == 'pending')
                                        <span class="badge badge-soft-info">{{translate('messages.pending')}}</span>
                                    @elseif($purchaseOrder->status == 'approved')
                                        <span class="badge badge-soft-success">{{translate('messages.approved')}}</span>
                                    @elseif($purchaseOrder->status == 'rejected')
                                        <span class="badge badge-soft-danger">{{translate('messages.rejected')}}</span>
                                    @elseif($purchaseOrder->status == 'completed')
                                        <span class="badge badge-soft-success">{{translate('messages.completed')}}</span>
                                    @endif
                                </p>
                                <p><strong>{{translate('messages.created_at')}}:</strong> {{$purchaseOrder->created_at->format('M d, Y H:i:s')}}</p>
                                @if($purchaseOrder->estimated_delivery)
                                <p><strong>{{translate('messages.estimated_delivery')}}:</strong> {{$purchaseOrder->estimated_delivery->format('M d, Y')}}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{translate('messages.payment_status')}}:</strong>
                                    <span class="badge badge-soft-{{ $purchaseOrder->payment_status == 'paid' ? 'success' : ($purchaseOrder->payment_status == 'partial' ? 'info' : 'warning') }}">
                                        {{ $purchaseOrder->payment_status ? ucfirst($purchaseOrder->payment_status) : 'Unpaid' }}
                                    </span>
                                </p>
                                <p><strong>{{translate('messages.total_amount')}}:</strong> {{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->total_amount) }}</p>
                                <p><strong>{{translate('messages.paid_amount')}}:</strong> {{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->paid_amount) }}</p>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">{{translate('messages.order_items')}}</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{translate('messages.item')}}</th>
                                            <th>{{translate('messages.price')}}</th>
                                            <th>{{translate('messages.quantity')}}</th>
                                            <th>{{translate('messages.total')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchaseOrder->items as $item)
                                            <tr>
                                                <td>{{$item->superAdminStock->name}}</td>
                                                <td>{{ \App\CentralLogics\Helpers::format_currency($item->superAdminStock->price) }}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{ \App\CentralLogics\Helpers::format_currency($item->quantity * $item->superAdminStock->price) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">{{translate('messages.total')}}:</th>
                                            <th>{{ \App\CentralLogics\Helpers::format_currency($purchaseOrder->total_amount) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        @if($purchaseOrder->notes)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">{{translate('messages.vendor_notes')}}</h5>
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($purchaseOrder->notes)) !!}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($purchaseOrder->admin_notes)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">{{translate('messages.admin_notes')}}</h5>
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($purchaseOrder->admin_notes)) !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row mt-3">
                            <div class="col-12">
                                <form action="{{ route('admin.purchase-order.update', $purchaseOrder->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">{{translate('messages.update_status')}}</label>
                                                <select name="status" id="status" class="form-control" {{ $purchaseOrder->status == 'completed' || $purchaseOrder->status == 'rejected' ? 'disabled' : '' }}>
                                                    <option value="pending" {{ $purchaseOrder->status == 'pending' ? 'selected' : '' }}>{{translate('messages.pending')}}</option>
                                                    <option value="approved" {{ $purchaseOrder->status == 'approved' ? 'selected' : '' }}>{{translate('messages.approved')}}</option>
                                                    <option value="completed" {{ $purchaseOrder->status == 'completed' ? 'selected' : '' }}>{{translate('messages.completed')}}</option>
                                                    <option value="rejected" {{ $purchaseOrder->status == 'rejected' ? 'selected' : '' }}>{{translate('messages.rejected')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="estimated_delivery">{{translate('messages.estimated_delivery')}}</label>
                                                <input type="date" name="estimated_delivery" id="estimated_delivery" class="form-control" value="{{ $purchaseOrder->estimated_delivery ? $purchaseOrder->estimated_delivery->format('Y-m-d') : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="admin_notes">{{translate('messages.admin_notes')}}</label>
                                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3">{{ $purchaseOrder->admin_notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{translate('messages.update')}}</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                @livewire('admin.purchase-order.purchase-order-view', ['purchaseOrder' => $purchaseOrder], key('payment-list-'.$purchaseOrder->id))
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
@endpush
