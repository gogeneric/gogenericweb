@extends('layouts.admin.app')

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
                <form action="{{route('admin.purchase-order.store')}}" method="post" id="purchase-order-form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach($stocks as $key=>$stock)
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label class="input-label">{{$stock['name']}}</label>
                                            <input type="number" min="0" value="0" name="items[{{$key}}][quantity]" class="form-control" placeholder="{{translate('messages.Ex_:_1')}}" {{$stock->current_stock==0?'readonly':''}}>
                                            <input type="hidden" name="items[{{$key}}][id]" value="{{$stock['id']}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="{{translate('messages.submit')}}">
                            </div>
                        </div>
                    </div>
                </form>
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
