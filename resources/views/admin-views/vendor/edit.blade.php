@extends('layouts.admin.app')

@section('title','Update restaurant info')
@push('css_or_js')
    {{-- <link rel="stylesheet" href="{{asset('/public/assets/admin/css/intlTelInput.css')}}" /> --}}

    @endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/edit.png')}}" class="w--26" alt="">
                </span>
                <span>{{translate('messages.update_store')}}</span>
            </h1>
        </div>
        @php
        $delivery_time_start = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time??'')?explode('-',$store->delivery_time)[0]:10;
        $delivery_time_end = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time??'')?explode(' ',explode('-',$store->delivery_time)[1])[0]:30;
        $delivery_time_type = preg_match('([0-9]+[\-][0-9]+\s[min|hours|days])', $store->delivery_time??'')?explode(' ',explode('-',$store->delivery_time)[1])[1]:'min';
    @endphp
        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
        @php($language = $language->value ?? null)
        @php($defaultLang = 'en')
        <!-- End Page Header -->
        <form action="{{route('admin.store.update',[$store['id']])}}" method="post" class="js-validate"
                enctype="multipart/form-data" id="vendor_form">
            @csrf

            <div class="row g-2">
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-body">
                            @if($language)
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active"
                                    href="#"
                                    id="default-link">{{ translate('Default') }}</a>
                                </li>
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link"
                                            href="#"
                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                            @if ($language)
                            <div class="lang_form"
                            id="default-form">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="default_name">{{ translate('messages.name') }}
                                        ({{ translate('messages.Default') }})
                                    </label>
                                    <input type="text" name="name[]" id="default_name"
                                        class="form-control" placeholder="{{ translate('messages.store_name') }}" value="{{$store->getRawOriginal('name')}}"
                                        required
                                         >
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                                <div class="form-group mb-0">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.address') }} ({{ translate('messages.default') }})</label>
                                    <textarea type="text" name="address[]" placeholder="{{translate('messages.store')}}" class="form-control min-h-90px ckeditor">{{$store->getRawOriginal('address')}}</textarea>
                                </div>
                            </div>
                                @foreach (json_decode($language) as $lang)
                                <?php
                                    if(count($store['translations'])){
                                        $translate = [];
                                        foreach($store['translations'] as $t)
                                        {
                                            if($t->locale == $lang && $t->key=="name"){
                                                $translate[$lang]['name'] = $t->value;
                                            }
                                            if($t->locale == $lang && $t->key=="address"){
                                                $translate[$lang]['address'] = $t->value;
                                            }
                                        }
                                    }
                                ?>
                                    <div class="d-none lang_form"
                                        id="{{ $lang }}-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="{{ $lang }}_name">{{ translate('messages.name') }}
                                                ({{ strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="name[]" id="{{ $lang }}_name"
                                                class="form-control" value="{{ $translate[$lang]['name']??'' }}" placeholder="{{ translate('messages.store_name') }}"
                                                 >
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.address') }} ({{ strtoupper($lang) }})</label>
                                            <textarea type="text" name="address[]" placeholder="{{translate('messages.store')}}" class="form-control min-h-90px ckeditor">{{ $translate[$lang]['address']??'' }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div id="default-form">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.name') }} ({{ translate('messages.default') }})</label>
                                        <input type="text" name="name[]" class="form-control"
                                            placeholder="{{ translate('messages.store_name') }}" required>
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.address') }}
                                        </label>
                                        <textarea type="text" name="address[]" placeholder="{{translate('messages.store')}}" class="form-control min-h-90px ckeditor"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                                <span>{{translate('Store Logo & Covers')}}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap flex-sm-nowrap __gap-12px">
                                <div class="__custom-upload-img mr-lg-5">
                                    @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                    @php($logo = $logo->value ?? '')
                                    <label class="form-label">
                                        {{ translate('logo') }} <span class="text--primary">({{ translate('1:1') }})</span>
                                    </label>
                                    <label class="text-center position-relative">
                                        <img class="img--110 min-height-170px min-width-170px onerror-image image--border" id="viewer"
                                        data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                        src="{{ $store->logo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}"
                                            alt="logo image" />
                                        <div class="icon-file-group">
                                            <div class="icon-file">
                                                <i class="tio-edit"></i>
                                        <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                        accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="__custom-upload-img">
                                    @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                    @php($icon = $icon->value ?? '')
                                    <label class="form-label">
                                        {{ translate('Store Cover') }}  <span class="text--primary">({{ translate('2:1') }})</span>
                                    </label>
                                    <label class="text-center position-relative">
                                        <img class="img--vertical min-height-170px min-width-170px onerror-image image--border" id="coverImageViewer"
                                        data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                        src="{{ $store->cover_photo_full_url ?? asset('public/assets/admin/img/upload-img.png') }}"
                                            alt="Fav icon" />
                                        <div class="icon-file-group">
                                            <div class="icon-file">
                                                <i class="tio-edit"></i>
                                                <input type="file" name="cover_photo" id="coverImageUpload"  class="custom-file-input"
                                                    accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <img class="mr-2 align-self-start w--20" src="{{asset('public/assets/admin/img/resturant.png')}}" alt="instructions">
                                <span>{{translate('store_information')}}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 my-0">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="tax">{{translate('messages.vat/tax')}} (%)</label>
                                        <input type="number" name="tax" class="form-control" placeholder="{{translate('messages.vat/tax')}}" min="0" step=".01" required value="{{$store->tax}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <label class="input-label" for="tax">{{translate('Estimated Delivery Time ( Min & Maximum Time)')}}</label>
                                        <input type="text" id="time_view" value="{{$delivery_time_start}} to {{$delivery_time_end}} {{$delivery_time_type}}" class="form-control" readonly>
                                        <a href="javascript:void(0)" class="floating-date-toggler">&nbsp;</a>
                                        <span class="offcanvas"></span>
                                        <div class="floating--date" id="floating--date">
                                            <div class="card shadow--card-2">
                                                <div class="card-body">
                                                    <div class="floating--date-inner">
                                                        <div class="item">
                                                            <label class="input-label"
                                                                for="minimum_delivery_time">{{ translate('Minimum Time') }}</label>
                                                            <input id="minimum_delivery_time" type="number" name="minimum_delivery_time" value="{{$delivery_time_start}}" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 30"
                                                                pattern="^[0-9]{2}$" required value="{{ old('minimum_delivery_time') }}">
                                                        </div>
                                                        <div class="item">
                                                            <label class="input-label"
                                                                for="maximum_delivery_time">{{ translate('Maximum Time') }}</label>
                                                            <input id="maximum_delivery_time" type="number" name="maximum_delivery_time" value="{{$delivery_time_end}}" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 60"
                                                                pattern="[0-9]{2}" required value="{{ old('maximum_delivery_time') }}">
                                                        </div>
                                                        <div class="item smaller">
                                                            <select name="delivery_time_type" id="delivery_time_type" class="custom-select">
                                                                <option value="min" {{$delivery_time_type=='min'?'selected':''}}>{{translate('messages.minutes')}}</option>
                                                                <option value="hours" {{$delivery_time_type=='hours'?'selected':''}}>{{translate('messages.hours')}}</option>
                                                                <option value="days" {{$delivery_time_type=='days'?'selected':''}}>{{translate('messages.days')}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="item smaller">
                                                            <button type="button" class="btn btn--primary delivery-time">{{ translate('done') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 my-0">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label" for="choice_zones">{{translate('messages.zone')}}<span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
        data-original-title="{{translate('messages.select_zone_for_map')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{translate('messages.select_zone_for_map')}}"></span></label>
                                        <select name="zone_id" id="choice_zones" data-placeholder="{{translate('messages.select_zone')}}"
                                                class="form-control js-select2-custom get_zone_data">
                                            @foreach(\App\Models\Zone::active()->get() as $zone)
                                                @if(isset(auth('admin')->user()->zone_id))
                                                    @if(auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{$zone->id}}" {{$store->zone_id == $zone->id? 'selected': ''}}>{{$zone->name}}</option>
                                                    @endif
                                                @else
                                                    <option value="{{$zone->id}}" {{$store->zone_id == $zone->id? 'selected': ''}}>{{$zone->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="latitude">{{translate('messages.latitude')}}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{translate('messages.store_lat_lng_warning')}}">
                                                <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{translate('messages.store_lat_lng_warning')}}">
                                            </span>
                                        </label>
                                        <input type="text" id="latitude"
                                                name="latitude" class="form-control"
                                                placeholder="{{ translate('messages.Ex:') }} -94.22213" value="{{$store->latitude}}" required readonly>
                                    </div>
                                    <div class="form-group mb-5">
                                        <label class="input-label" for="longitude">{{translate('messages.longitude')}}
                                            <span
                                                class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{translate('messages.store_lat_lng_warning')}}">
                                                <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{translate('messages.store_lat_lng_warning')}}">
                                            </span>
                                        </label>
                                        <input type="text"
                                                name="longitude" class="form-control"
                                                placeholder="{{ translate('messages.Ex:') }} 103.344322" id="longitude" value="{{$store->longitude}}" required readonly>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <input id="pac-input" class="controls rounded"
                                        data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.search_your_location_here') }}" type="text" placeholder="{{ translate('messages.search_here') }}" />
                                    <div id="map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2"><i class="tio-user"></i></span>
                                <span>{{translate('messages.owner_information')}}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="f_name">{{translate('messages.first_name')}}</label>
                                        <input type="text" name="f_name" class="form-control" placeholder="{{translate('messages.first_name')}}"
                                                value="{{$store->vendor->f_name}}"  required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="l_name">{{translate('messages.last_name')}}</label>
                                        <input type="text" name="l_name" class="form-control" placeholder="{{translate('messages.last_name')}}"
                                        value="{{$store->vendor->l_name}}"  required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="phone">{{translate('messages.phone')}}</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                        placeholder="{{ translate('messages.Ex:') }} 017********" value="{{$store->vendor->phone}}"
                                        required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2"><i class="tio-user"></i></span>
                                <span>{{translate('messages.account_information')}}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.email')}}</label>
                                        <input type="email" name="email" class="form-control" placeholder="{{ translate('messages.Ex:') }} ex@example.com" value="{{$store->email}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="js-form-message form-group mb-0">
                                        <label class="input-label" for="signupSrPassword">{{ translate('password') }}<span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                 data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span></label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control" name="password" id="signupSrPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                            placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                            aria-label="8+ characters required"
                                            data-msg="Your password is invalid. Please try again."
                                            data-hs-toggle-password-options='{
                                            "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                            }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="js-form-message form-group mb-0">
                                        <label class="input-label" for="signupSrConfirmPassword">{{ translate('messages.Confirm Password') }}</label>

                                        <div class="input-group input-group-merge">
                                        <input type="password" class="js-toggle-password form-control" name="confirmPassword" id="signupSrConfirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                        placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                        aria-label="8+ characters required"                                      data-msg="Password does not match the confirm password."
                                                data-hs-toggle-password-options='{
                                                "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                "defaultClass": "tio-hidden-outlined",
                                                "showClass": "tio-visible-outlined",
                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                }'>
                                        <div class="js-toggle-password-target-2 input-group-append">
                                            <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pharmacy Store Information -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2"><i class="tio-medicine"></i></span>
                                <span>{{translate('messages.pharmacy_information')}}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="drug_license_number">{{translate('messages.drug_license_number')}}</label>
                                        <input type="text" name="drug_license_number" id="drug_license_number" class="form-control" placeholder="{{translate('messages.drug_license_number')}}" value="{{$store->drug_license_number}}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="drug_license_expiry">{{translate('messages.drug_license_expiry_date')}}</label>
                                        <input type="date" name="drug_license_expiry" id="drug_license_expiry" class="form-control" value="{{$store->drug_license_expiry}}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="store_contact_person_number">{{translate('messages.store_contact_person_number')}}</label>
                                        <input type="tel" name="store_contact_person_number" id="store_contact_person_number" class="form-control" placeholder="{{translate('messages.Ex:')}} 017********" value="{{$store->store_contact_person_number}}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="pan_gst_number">{{translate('messages.pan_gst_number')}}</label>
                                        <input type="text" name="pan_gst_number" id="pan_gst_number" class="form-control" placeholder="{{translate('messages.pan_gst_number')}}" value="{{$store->pan_gst_number}}">
                                    </div>
                                </div>
                                
                                <div class="col-md-8 mb-3">
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-12">
                                            <label class="form-label">{{translate('messages.form_20_21')}}</label>
                                            <div class="d-flex flex-column">
                                                <div class="custom-file">
                                                    <input type="file" name="form_20_21" id="form_20_21" class="custom-file-input" accept=".jpg, .jpeg, .png, .pdf">
                                                    <label class="custom-file-label" for="form_20_21">{{translate('messages.choose_file')}}</label>
                                                </div>
                                                <div class="form-text">
                                                    {{translate('messages.upload_form_20_21')}}
                                                </div>
                                                @if($store->form_20_21)
                                                <div class="mt-2">
                                                    <a href="{{asset('storage/app/public/store/form_20_21')}}/{{$store->form_20_21}}" target="_blank" class="text-primary">{{translate('messages.view_current_file')}}</a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-12">
                                            <label class="form-label">{{translate('messages.pan_gst_upload')}}</label>
                                            <div class="d-flex flex-column">
                                                <div class="custom-file">
                                                    <input type="file" name="pan_gst_upload" id="pan_gst_upload" class="custom-file-input" accept=".jpg, .jpeg, .png, .pdf">
                                                    <label class="custom-file-label" for="pan_gst_upload">{{translate('messages.choose_file')}}</label>
                                                </div>
                                                <div class="form-text">
                                                    {{translate('messages.upload_pan_gst')}}
                                                </div>
                                                @if($store->pan_gst_upload)
                                                <div class="mt-2">
                                                    <a href="{{asset('storage/app/public/store/pan_gst')}}/{{$store->pan_gst_upload}}" target="_blank" class="text-primary">{{translate('messages.view_current_file')}}</a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 col-sm-6">
                                    <div class="__custom-upload-img">
                                        <label class="form-label">{{translate('messages.owner_photo')}}</label>
                                        <label class="text-center position-relative">
                                            <img class="img--110 min-height-170px min-width-170px onerror-image image--border" id="ownerPhotoViewer"
                                            data-onerror-image="{{ asset('public/assets/admin/img/upload-img.png') }}"
                                                src="{{ $store->owner_photo ? asset('storage/app/public/store/owner') . '/' . $store->owner_photo : asset('public/assets/admin/img/upload-img.png') }}"
                                                alt="owner photo" />
                                            <div class="icon-file-group">
                                                <div class="icon-file">
                                                    <i class="tio-edit"></i>
                                                    <input type="file" name="owner_photo" id="ownerPhotoUpload" class="custom-file-input"
                                                        accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bank Account Information -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2"><i class="tio-money"></i></span>
                                <span>{{translate('messages.bank_account_information')}}</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="account_type">{{translate('messages.account_type')}}</label>
                                        <select name="account_type" id="account_type" class="form-control js-select2-custom">
                                            <option value="savings" {{$store->account_type == 'savings' ? 'selected' : ''}}>{{translate('messages.savings')}}</option>
                                            <option value="current" {{$store->account_type == 'current' ? 'selected' : ''}}>{{translate('messages.current')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="account_number">{{translate('messages.account_number')}}</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control" placeholder="{{translate('messages.account_number')}}" value="{{$store->account_number}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="bank_name">{{translate('messages.bank_name')}}</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="{{translate('messages.bank_name')}}" value="{{$store->bank_name}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="ifsc_code">{{translate('messages.ifsc_code')}}</label>
                                        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="{{translate('messages.ifsc_code')}}" value="{{$store->ifsc_code}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&libraries=places&callback=initMap&v=3.45.8"></script>
    <script>
        "use strict";
        $("#vendor_form").on('keydown', function(e){
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })
      $(document).on('ready', function () {
            $('.offcanvas').on('click', function(){
                $('.offcanvas, .floating--date').removeClass('active')
            })
            $('.floating-date-toggler').on('click', function(){
                $('.offcanvas, .floating--date').toggleClass('active')
            })
        @if (isset(auth('admin')->user()->zone_id))
            $('#choice_zones').trigger('change');
        @endif
    });

        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function () {
            readURL(this, 'coverImageViewer');
        });
        
        $("#ownerPhotoUpload").change(function () {
            readURL(this, 'ownerPhotoViewer');
        });

        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('messages.please_only_input_png_or_jpg_type_file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('messages.file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        let myLatlng = { lat: {{$store->latitude}}, lng: {{$store->longitude}} };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: myLatlng,
        });
        let zonePolygon = null;
        let infoWindow = new google.maps.InfoWindow({
                content: "Click the map to get Lat/Lng!",
                position: myLatlng,
            });
        let bounds = new google.maps.LatLngBounds();
        function initMap() {
            // Create the initial InfoWindow.
            new google.maps.Marker({
                position: { lat: {{$store->latitude}}, lng: {{$store->longitude}} },
                map,
                title: "{{$store->name}}",
            });
            infoWindow.open(map);
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            let markers = [];
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
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
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
        initMap();
        $('.get_zone_data').on('change',function (){
            let id = $(this).val();
            $.get({
                url: '{{url('/')}}/admin/zone/get-coordinates/'+id,
                dataType: 'json',
                success: function (data) {
                    if(zonePolygon)
                    {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                        position: mapsMouseEvent.latLng,
                        content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                        });
                        let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        })
        $(document).on('ready', function (){
            let id = $('#choice_zones').val();
            $.get({
                url: '{{url('/')}}/admin/zone/get-coordinates/'+id,
                dataType: 'json',
                success: function (data) {
                    if(zonePolygon)
                    {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPaths().forEach(function(path) {
                        path.forEach(function(latlng) {
                            bounds.extend(latlng);
                            map.fitBounds(bounds);
                        });
                    });
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function (mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                        position: mapsMouseEvent.latLng,
                        content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                        });
                        let coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        });

    $('#reset_btn').click(function(){
        $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload.png') }}");
        $('#customFileEg1').val(null);
        $('#coverImageViewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
        $('#coverImageUpload').val(null);
        $('#ownerPhotoViewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
        $('#ownerPhotoUpload').val(null);
        $('#choice_zones').val(null).trigger('change');
        $('#module_id').val(null).trigger('change');
        zonePolygon.setMap(null);
        $('#coordinates').val(null);
        $('#latitude').val(null);
        $('#longitude').val(null);
        $('#drug_license_number').val(null);
        $('#drug_license_expiry').val(null);
        $('#pan_gst_number').val(null);
        $('#store_contact_person_number').val(null);
        $('#account_type').val('savings').trigger('change');
        $('#account_number').val(null);
        $('#bank_name').val(null);
        $('#ifsc_code').val(null);
        $('#form_20_21').val(null);
        $('#pan_gst_upload').val(null);
    })

    let zone_id = 0;
    $('#choice_zones').on('change', function() {
        if($(this).val())
    {
        zone_id = $(this).val();
    }
    });



    $('#module_id').select2({
            ajax: {
                 url: '{{url('/')}}/vendor/get-all-modules',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        zone_id: zone_id
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });


    $('.delivery-time').on('click',function (){
        let min = $("#minimum_delivery_time").val();
        let max = $("#maximum_delivery_time").val();
        let type = $("#delivery_time_type").val();
        $("#floating--date").removeClass('active');
        $("#time_view").val(min+' to '+max+' '+type);

    })
</script>
@endpush
