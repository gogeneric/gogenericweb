@extends('layouts.admin.app')

@section('title', translate('Super Admin Stock List'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.super_admin_stock_list')}}</h1>
                </div>

                <div class="col-sm-auto">
                    <a href="{{route('admin.super-admin-stocks.create')}}" class="btn btn-primary pull-right"><i
                                class="tio-add-circle"></i> {{translate('messages.add_new_stock')}}</a>
                    <a href="{{ route('admin.super-admin-stocks.bulk-import') }}" class="btn btn-sm btn-outline-primary">{{ translate('Bulk Import') }}</a>
                    <a href="{{ route('admin.super-admin-stocks.bulk-export') }}" class="btn btn-sm btn-outline-primary">{{ translate('Bulk Export') }}</a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h5 class="card-header-title"></h5>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('messages.#')}}</th>
                                <th>{{translate('messages.name')}}</th>
                                <th>{{translate('messages.category')}}</th>
                                <th>{{translate('messages.price')}}</th>
                                <th>{{translate('messages.stock')}}</th>
                                <th>{{translate('messages.status')}}</th>
                                <th>{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($stocks as $key=>$stock)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{Str::limit($stock['name'], 20, '...')}}
                                        </span>
                                    </td>
                                    <td>
                                        {{$stock->category->name}}
                                    </td>
                                    <td>{{$stock['price']}}</td>
                                    <td>{{$stock['stock']}}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stockCheckbox{{$stock->id}}">
                                            <input type="checkbox" onclick="location.href='{{route('admin.super-admin-stocks.status',[$stock['id'],$stock->status?0:1])}}'" class="toggle-switch-input" id="stockCheckbox{{$stock->id}}" {{$stock->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-white"
                                            href="{{route('admin.super-admin-stocks.edit',[$stock['id']])}}" title="{{translate('messages.edit_stock')}}"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn-white" href="javascript:"
                                            onclick="form_alert('stock-{{$stock['id']}}','Want to delete this stock ?')" title="{{translate('messages.delete_stock')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.super-admin-stocks.delete',[$stock['id']])}}"
                                                method="post" id="stock-{{$stock['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <table>
                            <tfoot>
                            {!! $stocks->links() !!}
                            </tfoot>
                        </table>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
