@extends('layouts.vendor.app')

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
                @livewire('vendor.purchase-order.purchase-order-view', ['purchaseOrder' => $purchaseOrder])
            </div>
        </div>
    </div>
@endsection

@push('script_2')
@endpush
