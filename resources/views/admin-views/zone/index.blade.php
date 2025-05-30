@extends('layouts.admin.app')

@section('title',translate('Add new zone'))

@push('css_or_js')

@endpush

@section('content')
@php($zone_instruction = session()?->get('zone-instruction') ?? '0')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/zone.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.Add_New_Business_Zone')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row g-3">
            <div class="col-12">
                <form action="javascript:" method="post" id="zone_form" class="shadow--card">
                    @csrf
                    <div class="row justify-content-between">
                        <div class="col-md-5">
                            <div class="zone-setup-instructions">
                                <div class="zone-setup-top">
                                    <h6 class="subtitle">{{ translate('Instructions') }}</h6>
                                    <p>
                                        {{ translate('Create_&_connect_dots_in_a_specific_area_on_the_map_to_add_a_new_business_zone.') }}
                                    </p>
                                </div>
                                <div class="zone-setup-item">
                                    <div class="zone-setup-icon">
                                        <i class="tio-hand-draw"></i>
                                    </div>
                                    <div class="info">
                                        {{ translate('Use_this_‘Hand_Tool’_to_find_your_target_zone.') }}
                                    </div>
                                </div>
                                <div class="zone-setup-item">
                                    <div class="zone-setup-icon">
                                        <i class="tio-free-transform"></i>
                                    </div>
                                    <div class="info">
                                        {{ translate('Use_this_‘Shape_Tool’_to_point_out_the_areas_and_connect_the_dots._Minimum_3_points/dots_are_required.') }}
                                    </div>
                                </div>
                                <div class="instructions-image mt-4">
                                    <img src="{{asset('public/assets/admin/img/instructions.gif')}}" alt="instructions">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-7 zone-setup">
                            <div class="pl-xl-5 pl-xxl-0">
                                @if($language)
                                    @php($defaultLang = $language[0])
                                    <ul class="nav nav-tabs mb-4">
                                        <li class="nav-item">
                                            <a class="nav-link lang_link active"
                                            href="#"
                                            id="default-link">{{translate('messages.default')}}</a>
                                        </li>
                                        @foreach ($language as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link lang_link"
                                                    href="#"
                                                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                            </li>
                                        @endforeach
                                        <span class="form-label-secondary text-danger"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Choose_your_preferred_language_&_set_your_zone_name.') }}"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.veg_non_veg') }}"></span>
                                    </ul>

                                <div class="tab-content">
                                    <div class="row lang_form" id="default-form">
                                        <div class="form-group col-6">
                                            <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.business_Zone_name')}} ({{ translate('messages.default') }})</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.Write_a_New_Business_Zone_Name')}}" maxlength="191"  >
                                        </div>
                                        <div class="form-group col-6">
                                            <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.display_name')}} ({{ translate('messages.default') }})</label>
                                            <input type="text" name="display_name[]" class="form-control" placeholder="{{translate('messages.Write_a_New_Display_Zone_Name')}}" maxlength="191"  >
                                        </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    </div>

                                    @foreach($language as $lang)
                                        <div class="row lang_form d-none" id="{{$lang}}-form">
                                            <div class="form-group col-6">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.business_Zone_name')}} ({{strtoupper($lang)}})</label>
                                                <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.Write_a_New_Business_Zone_Name')}}" maxlength="191"  >
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.display_name')}} ({{strtoupper($lang)}})</label>
                                                <input type="text" name="display_name[]" class="form-control" placeholder="{{translate('messages.Write_a_New_Display_Zone_Name')}}" maxlength="191"  >
                                            </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                        </div>
                                    @endforeach
                                @endif
                                </div>

                                <div class="form-group mb-3 d-none">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('Coordinates') }}<span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
        data-original-title="{{translate('messages.draw_your_zone_on_the_map')}}">{{translate('messages.draw_your_zone_on_the_map')}}</span></label>
                                        <textarea type="text" rows="8" name="coordinates"  id="coordinates" class="form-control" readonly></textarea>
                                </div>
                                <div class="map-warper rounded mt-0">
                                    <input id="pac-input" class="controls rounded" title="{{translate('messages.search_your_location_here')}}" type="text" placeholder="{{translate('messages.search_here')}}"/>
                                    <div id="map-canvas" class="rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container mt-3 justify-content-end">
                        <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>

            @php($config=\App\CentralLogics\Helpers::get_business_settings('cash_on_delivery'))
            @php($digital_payment=\App\CentralLogics\Helpers::get_business_settings('digital_payment'))
            @php($offline_payment=\App\CentralLogics\Helpers::get_business_settings('offline_payment_status'))

            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                {{translate('messages.zone_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$zones->total()}}</span>
                            </h5>
                            <form   class="search-form">
                                            <!-- Search -->

                                <div class="input-group input--group">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{translate('messages.Search_Business_Zone')}}"  value="{{ request()?->search ?? null }}" aria-label="{{translate('messages.search')}}" required>
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>
                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                                    data-hs-unfold-options='{
                                            "target": "#usersExportDropdown",
                                            "type": "css-animation"
                                        }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.business-settings.zone.export', ['type'=>'excel',request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="{{route('admin.business-settings.zone.export', ['type'=>'csv',request()->getQueryString()])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('messages.SL') }}</th>
                                <th class="border-0">{{translate('messages.zone_Id')}}</th>
                                <th class="border-0" >{{ translate('messages.business_Zone_name')}}</th>
                                <th class="border-0" >{{translate('messages.vendors')}}</th>
                                <th class="border-0" >{{translate('messages.deliverymen')}}</th>
                                <th class="border-0" >{{translate('messages.status')}}</th>
                                @if ($digital_payment && $digital_payment['status']==1)
                                <th class="border-0" >{{translate('messages.digital_payment')}}</th>
                                @endif
                                @if ($config && $config['status']==1)
                                <th class="border-0" >{{translate('messages.cash_on_delivery')}}</th>
                                @endif
                                @if ($offline_payment && $offline_payment==1)
                                <th class="border-0" >{{translate('messages.offline_payment')}}</th>
                                @endif
                                <th class="border-0 text-center" >{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @php($non_mod = 0)
                            @foreach($zones as $key=>$zone)
                            @php($non_mod = (count($zone->modules)>0 && $non_mod == 0) ? $non_mod:$non_mod+1 )
                                <tr>
                                    <td>{{$key+$zones->firstItem()}}</td>
                                    <td>{{$zone->id}}</td>
                                    <td>
                                    <span class="d-block font-size-sm text-body">
                                        {{$zone['name']}}
                                    </span>
                                    </td>
                                    <td>
                                        {{$zone->stores->filter(function($store) {
                                            return $store->vendor && $store->vendor->status == 1;
                                        })->count()}}
                                    </td>
                                    <td>{{$zone->deliverymen_count}}</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="status-{{$zone['id']}}">
                                            <input type="checkbox" class="toggle-switch-input dynamic-checkbox"
                                                   data-id="status-{{$zone['id']}}"
                                                   data-type="status"
                                                   data-image-on='{{asset('/public/assets/admin/img/modal')}}/zone-status-on.png'
                                                   data-image-off="{{asset('/public/assets/admin/img/modal')}}/zone-status-off.png"
                                                   data-title-on="{{translate('Want_to_activate_this_Zone?')}}"
                                                   data-title-off="{{translate('Want_to_deactivate_this_Zone?')}}"
                                                   data-text-on="<p>{{translate('If_you_activate_this_zone,_Customers_can_see_all_stores_&_products_available_under_this_Zone_from_the_Customer_App_&_Website.')}}</p>"
                                                   data-text-off="<p>{{translate('If_you_deactivate_this_zone,_Customers_Will_NOT_see_all_stores_&_products_available_under_this_Zone_from_the_Customer_App_&_Website.')}}</p>"
                                                   id="status-{{$zone['id']}}" {{$zone->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.business-settings.zone.status',[$zone['id'],$zone->status?0:1])}}" method="get" id="status-{{$zone['id']}}_form">
                                        </form>
                                    </td>
                                    @if ($digital_payment && $digital_payment['status']==1)
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="digital_paymentCheckbox{{$zone->id}}">
                                            <input type="checkbox" data-id="digital_payment-{{$zone['id']}}" data-title="{{ $zone->digital_payment?translate('Want_to_disable_‘Digital_Payment’?'):translate('Want_to_enable_‘Digital_Payment’?') }}" data-message="{{ $zone->digital_payment? translate('If_yes,_the_digital_payment_option_will_be_hidden_during_checkout.'):translate('If_yes,_Customers_can_choose_the_‘Digital_Payment’_option_during_checkout.')}}" class="toggle-switch-input status_form_alert" id="digital_paymentCheckbox{{$zone->id}}" {{$zone->digital_payment?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.business-settings.zone.digital-payment',[$zone['id'],$zone->digital_payment?0:1])}}" method="get" id="digital_payment-{{$zone['id']}}">
                                        </form>
                                    </td>
                                    @endif
                                    @if ($config && $config['status']==1)
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="cashOnDeliveryCheckbox{{$zone->id}}">
                                            <input type="checkbox" data-id="cash_on_delivery-{{$zone['id']}}" data-title="{{ $zone->cash_on_delivery?translate('Want_to_disable_‘Cash_On_Delivery’?'):translate('Want_to_enable_‘Cash_On_Delivery’?') }}" data-message="{{ $zone->cash_on_delivery? translate('If_yes,_the_Cash_on_Delivery_option_will_be_hidden_during_checkout.'):translate('If_yes,_Customers_can_choose_the_‘Cash_On_Delivery’_option_during_checkout.')}}" class="toggle-switch-input status_form_alert" id="cashOnDeliveryCheckbox{{$zone->id}}" {{$zone->cash_on_delivery?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.business-settings.zone.cash-on-delivery',[$zone['id'],$zone->cash_on_delivery?0:1])}}" method="get" id="cash_on_delivery-{{$zone['id']}}">
                                        </form>
                                    </td>
                                    @endif
                                    @if ($offline_payment && $offline_payment==1)
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="offline_paymentCheckbox{{$zone->id}}">
                                            <input type="checkbox" data-id="offline_payment-{{$zone['id']}}" data-title="{{ $zone->offline_payment?translate('Want_to_disable_‘offline_Payment’?'):translate('Want_to_enable_‘offline_Payment’?') }}" data-message="{{ $zone->offline_payment? translate('If_yes,_the_offline_payment_option_will_be_hidden_during_checkout.'):translate('If_yes,_Customers_can_choose_the_‘offline_Payment’_option_during_checkout.')}}" class="toggle-switch-input status_form_alert" id="offline_paymentCheckbox{{$zone->id}}" {{$zone->offline_payment?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.business-settings.zone.offline-payment',[$zone['id'],$zone->offline_payment?0:1])}}" method="get" id="offline_payment-{{$zone['id']}}">
                                        </form>
                                    </td>
                                    @endif
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary"
                                                href="{{route('admin.business-settings.zone.edit',[$zone['id']])}}" title="{{translate('messages.edit_zone')}}"><i class="tio-edit"></i>
                                            </a>
                                            <!-- <div class="popover-wrapper active"> add active class to show -->
                                            <div class="popover-wrapper {{ $non_mod == 1 ? 'active':'' }}">
                                                <a class="btn active action-btn btn--warning btn-outline-warning" href="{{route('admin.business-settings.zone.module-setup',[$zone['id']])}}">
                                                    <i class="tio-settings"></i>
                                                </a>
                                                <div class="popover __popover">
                                                    <div class="arrow"></div>
                                                    <h3 class="popover-header d-flex justify-content-between">
                                                        <span>{{ translate('messages.Important!') }}</span>
                                                        {{-- <span class="tio-clear"></span> --}}
                                                    </h3>
                                                    <div class="popover-body">
                                                        {{ translate('The_Business_Zone_will_NOT_work_if_you_don’t_select_your_business_module_&_payment_method.') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn action-btn btn--danger btn-outline-danger status_form_alert" href="javascript:" data-id="zone-{{$zone['id']}}" data-title="{{ translate('Want_to_Delete_this_Zone?') }}" data-message="{{ translate('If_yes,_all_its_modules,_stores,_and_products_will_be_DELETED_FOREVER.') }}" title="{{translate('messages.delete_zone')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.business-settings.zone.delete',[$zone['id']])}}" method="post" id="zone-{{$zone['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($zones) !== 0)
                    <hr>
                    @endif
                    <div class="page-area">
                        {!! $zones->withQueryString()->links() !!}
                    </div>
                    @if(count($zones) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>
@if ($zone_instruction == '0')

<div class="modal fade" id="warning-modal">
    <div class="modal-dialog modal-lg warning-modal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mb-3">
                    <h3 class="modal-title mb-3">{{translate('New_Business_Zone_Created_Successfully!')}}</h3>
                    <p class="txt">
                        {{translate("NEXT_IMPORTANT_STEP:_You_need_to_select_‘Payment_Method’_and_add_‘Business_Modules’_with_other_details_from_the_Zone_Settings._If_you_don’t_finish_the_setup,_the_Zone_you_created_won’t_function_properly.")}}
                    </p>
                </div>
                <img src="{{asset('/public/assets/admin/img/zone-settings-popup-arrow.gif')}}" alt="admin/img" class="w-100">
                <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between">
                    <label class="form-check form--check m-0">
                        <input type="checkbox" class="form-check-input rounded redirect-url" data-url="{{route('admin.business-settings.zone.instruction')}}">
                        <span class="form-check-label">{{translate("Don't show this anymore")}}</span>
                    </label>
                    <div class="btn--container justify-content-end">
                        <button id="reset_btn" type="reset" class="btn btn--reset" data-dismiss="modal">{{translate("I will do it later")}}</button>
                        <a id="module-setup-modal-button" data-url="{{route('admin.business-settings.zone.go-module-setup')}}" class="btn btn--primary redirect-url">{{translate('Go_to_zone_Settings')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <div class="modal fade" id="status-warning-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center mb-20">
                        <img src="{{asset('/public/assets/admin/img/zone-status-on.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('By switching the status to “ON”,  this zone and under all the functionality of this zone will be turned on')}}</h5>
                        <p class="txt">
                            {{translate("In the user app & website all stores & products  already assigned under this zone will show to the customers")}}
                        </p>
                    </div>
                    <div class="btn--container justify-content-center">
                        <button type="submit" class="btn btn--primary min-w-120" data-dismiss="modal">{{translate('Ok')}}</button>
                        <button id="reset_btn" type="reset" class="btn btn--cancel min-w-120" data-dismiss="modal">{{translate("Cancel")}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initialize&libraries=drawing,places&v=3.49"></script>
<script>
    "use strict";
        $(".popover-wrapper").click(function(){
          $(".popover-wrapper").removeClass("active");
        });

        $('.status_form_alert').on('click', function (event) {
            let id = $(this).data('id');
            let title = $(this).data('title');
            let message = $(this).data('message');
            status_form_alert(id, title, message, event)
        })

        function status_form_alert(id, title, message, e) {
            e.preventDefault();
            Swal.fire({
                title: title,
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#'+id).submit()
                }
            })
        }
    auto_grow();
    function auto_grow() {
        let element = document.getElementById("coordinates");
        element.style.height = "5px";
        element.style.height = (element.scrollHeight)+"px";
    }


        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $("#zone_form").on('keydown', function(e){
                if (e.keyCode === 13) {
                    e.preventDefault();
                }
            })
        });

        let map; // Global declaration of the map
        let drawingManager;
        let lastpolygon = null;
        let polygons = [];

        function resetMap(controlDiv) {
            // Set CSS for the control border.
            const controlUI = document.createElement("div");
            controlUI.style.backgroundColor = "#fff";
            controlUI.style.border = "2px solid #fff";
            controlUI.style.borderRadius = "3px";
            controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
            controlUI.style.cursor = "pointer";
            controlUI.style.marginTop = "8px";
            controlUI.style.marginBottom = "22px";
            controlUI.style.textAlign = "center";
            controlUI.title = "Reset map";
            controlDiv.appendChild(controlUI);
            // Set CSS for the control interior.
            const controlText = document.createElement("div");
            controlText.style.color = "rgb(25,25,25)";
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "10px";
            controlText.style.lineHeight = "16px";
            controlText.style.paddingLeft = "2px";
            controlText.style.paddingRight = "2px";
            controlText.innerHTML = "X";
            controlUI.appendChild(controlText);
            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", () => {
                lastpolygon.setMap(null);
                $('#coordinates').val('');

            });
        }

        function initialize() {
            @php($default_location=\App\Models\BusinessSetting::where('key','default_location')->first())
            @php($default_location=$default_location->value?json_decode($default_location->value, true):0)
            let myLatlng = { lat: {{$default_location?$default_location['lat']:'23.757989'}}, lng: {{$default_location?$default_location['lng']:'90.360587'}} };


            let myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                editable: true
                }
            });
            drawingManager.setMap(map);


            //get current location block
            // infoWindow = new google.maps.InfoWindow();
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    map.setCenter(pos);
                });
            }

            drawingManager.addListener("overlaycomplete", function(event) {
                if(lastpolygon)
                {
                    lastpolygon.setMap(null);
                }
                $('#coordinates').val(event.overlay.getPath().getArray());
                lastpolygon = event.overlay;
                auto_grow();
            });

            const resetDiv = document.createElement("div");
            resetMap(resetDiv, lastpolygon);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(resetDiv);

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                };
                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                    map,
                    icon,
                    title: place.name,
                    position: place.geometry.location,
                    })
                );

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                });
                map.fitBounds(bounds);
            });
        }

        // initialize();


        function set_all_zones()
        {
            $.get({
                url: '{{route('admin.zone.zoneCoordinates')}}',
                dataType: 'json',
                success: function (data) {
                    for(let i=0; i<data.length;i++)
                    {
                        polygons.push(new google.maps.Polygon({
                            paths: data[i],
                            strokeColor: "#FF0000",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: "#FF0000",
                            fillOpacity: 0.1,
                        }));
                        polygons[i].setMap(map);
                    }

                },
            });
        }
        $(document).on('ready', function(){
            set_all_zones();
        });


        $('#zone_form').on('submit', function () {
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.business-settings.zone.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if(data.errors){
                        $.each(data.errors, function(index, value){
                            toastr.error(value.message);
                        });
                    }
                    else{
                        $('.tab-content').find('input:text').val('');
                        $('input[name="name"]').val(null);
                        lastpolygon.setMap(null);
                        $('#coordinates').val(null);
                        toastr.success("{{ translate('messages.zone_added_successfully') }}", {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        $('#set-rows').html(data.view);
                        $('#itemCount').html(data.total);
                        $("#module-setup-modal-button").prop("href",'{{url('/')}}/admin/business-settings/zone/module-setup/'+ data.id)
                        $("#warning-modal").modal("show");
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

            $('#reset_btn').click(function(){
                $('.tab-content').find('input:text').val('');

                lastpolygon.setMap(null);
                $('#coordinates').val(null);
            })
        </script>
@endpush
