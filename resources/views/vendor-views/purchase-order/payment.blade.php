@extends('layouts.vendor.app')

@section('title', translate('Payment'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                {{ translate('Payment for Purchase Order #') }}{{ $purchaseOrder->id }}
            </h1>
        </div>

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                @livewire('vendor.purchase-order.payment-form', ['purchaseOrder' => $purchaseOrder])
            </div>
        </div>
    </div>
@endsection
