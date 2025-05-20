@extends('layouts.admin.app')

@section('title', translate('Purchase Order Payments'))

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/money.png')}}" class="w--22" alt="">
            </span>
            <span>
                {{ translate('Purchase Order Payments') }}
            </span>
        </h1>
    </div>
    
    <div class="card">
        <div class="card-header border-0">
            <h5 class="card-title">
                {{ translate('Purchase Order') }} #{{ $purchaseOrder->id }} {{ translate('Payments') }}
            </h5>
            <a href="{{ route('admin.purchase-order.show', $purchaseOrder->id) }}" class="btn btn-primary">
                <i class="tio-back-ui"></i> {{ translate('Back to Purchase Order') }}
            </a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th>{{ translate('Method') }}</th>
                            <th>{{ translate('Transaction ID') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Proof') }}</th>
                            <th class="text-center">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($purchaseOrder->payments as $key=>$payment)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ \App\CentralLogics\Helpers::format_currency($payment->amount) }}</td>
                            <td>{{ $payment->readable_payment_method }}</td>
                            <td><code>{{ $payment->transaction_id }}</code></td>
                            <td>
                                <span class="badge badge-{{$payment->status_badge_class}}">
                                    {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->payment_proof)
                                    <a href="{{ asset('storage/'.$payment->payment_proof) }}" 
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        {{ translate('View Proof') }}
                                    </a>
                                @else
                                    <span class="badge badge-light">{{ translate('No proof') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($payment->status === 'pending_approval')
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn-success"
                                           href="{{ route('admin.purchase-order.payment.approve', $payment->id) }}"
                                           onclick="return confirm('Are you sure you want to approve this payment?')">
                                            <i class="tio-checkmark-circle"></i> {{ translate('Approve') }}
                                        </a>
                                        <a class="btn btn-sm btn-danger"
                                           href="{{ route('admin.purchase-order.payment.reject', $payment->id) }}"
                                           onclick="return confirm('Are you sure you want to reject this payment?')">
                                            <i class="tio-clear-circle"></i> {{ translate('Reject') }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                    @if(count($purchaseOrder->payments) === 0)
                        <tr>
                            <td colspan="8" class="text-center">{{ translate('No payment records found') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
