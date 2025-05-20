@extends('layouts.admin.app')

@section('title', translate('Purchase Orders'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.purchase_orders')}}</h1>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn-primary" href="{{route('admin.purchase-order.create')}}">
                        <i class="tio-add-circle"></i> {{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.purchase_order')}}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <livewire:admin.purchase-order.purchase-order-list/>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
@endpush
