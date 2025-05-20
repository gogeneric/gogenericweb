@extends('layouts.vendor.app')

@section('title', translate('Create Purchase Order'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{translate('messages.create')}} {{translate('messages.purchase_order')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <livewire:vendor.purchase-order.create-purchase-order />
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    $(document).ready(function () {
        // You can add any necessary JavaScript for form handling here
    });
</script>
@endpush
