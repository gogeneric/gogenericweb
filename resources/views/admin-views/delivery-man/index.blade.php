@extends('layouts.admin.app')

@section('title', translate('messages.Add new delivery-man'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-break">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/delivery-man.png') }}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.add_new_deliveryman') }}</span>
            </h1>
        </div>
        <!-- End Page Header -->
        <form action="{{ route('admin.users.delivery-man.store') }}" method="post" enctype="multipart/form-data"
              class="js-validate">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-title-icon"><i class="tio-user"></i></span>
                        <span>
                            {{ translate('general_information') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.first_name') }}
                                            <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                  data-placement="right"
                                                  data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>

                                        </label>
                                        <input type="text" name="f_name" class="form-control"
                                               placeholder="{{ translate('messages.first_name') }}" value="{{ old('f_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.last_name') }}
                                            <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                  data-placement="right"
                                                  data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>

                                        </label>
                                        <input type="text" name="l_name" class="form-control"
                                               placeholder="{{ translate('messages.last_name') }}" value="{{ old('l_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.email') }}
                                            <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                  data-placement="right"
                                                  data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>

                                        </label>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="{{ translate('messages.Ex:') }} ex@example.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.deliveryman_type') }}
                                            <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                                  data-placement="right"
                                                  data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>
                                        </label>
                                        <select name="earning"
                                                data-placeholder="{{ translate('messages.Select_deliveryman_type') }}"
                                                required class="form-control js-select2-custom">
                                            <option value="" readonly="true"
                                                    hidden="true"> {{ translate('messages.Select_deliveryman_type') }}</option>
                                            <option value="1" {{ old('earning') == '1' ? 'selected' : '' }}>{{ translate('messages.freelancer') }}</option>
                                            <option value="0" {{ old('earning') == '0' ? 'selected' : '' }}>{{ translate('messages.salary_based') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.zone') }} <span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>
                                        </label>
                                        <select name="zone_id" class="form-control js-select2-custom" required
                                                data-placeholder="{{ translate('messages.select_zone') }}">
                                            <option value="" readonly="true" hidden="true">
                                                {{ translate('messages.select_zone') }}</option>
                                            @foreach (\App\Models\Zone::all() as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                        </option>
                                                    @endif
                                                @else
                                                    <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.Vehicle') }} <span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>
                                        </label>
                                        <select name="vehicle_id" class="form-control js-select2-custom h--45px"
                                                required
                                                data-placeholder="{{ translate('messages.select_vehicle') }}">
                                            <option value="" readonly="true"
                                                    hidden="true"> {{ translate('messages.select_vehicle') }}</option>
                                            @foreach (\App\Models\DMVehicle::where('status', 1)->get(['id', 'type']) as $v)
                                                <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>{{ $v->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex flex-column h-100">
                                <label class="text-center">{{ translate('messages.deliveryman_image') }} <small
                                        class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                                </label>
                                <div class="text-center py-3 my-auto">
                                    <img class="img--100" id="viewer"
                                         src="{{ asset('public/assets/admin/img/admin.png') }}"
                                         alt="delivery-man image"/>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label"
                                           for="customFileEg1">{{ translate('messages.choose_file') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.identity_type') }}
                                            <span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>
                                        </label>
                                        <select required name="identity_type"
                                                data-placeholder="{{ translate('messages.select_identity_type') }}"
                                                class="form-control js-select2-custom">
                                            <option value="" readonly="true"
                                                    hidden="true"> {{ translate('messages.select_identity_type') }}</option>
                                            <option value="passport" {{ old('identity_type') == 'passport' ? 'selected' : '' }}>{{ translate('messages.passport') }}</option>
                                            <option value="driving_license" {{ old('identity_type') == 'driving_license' ? 'selected' : '' }}>{{ translate('messages.driving_license') }} </option>
                                            <option value="nid" {{ old('identity_type') == 'nid' ? 'selected' : '' }}>{{ translate('messages.nid') }}</option>
                                            <option value="store_id" {{ old('identity_type') == 'store_id' ? 'selected' : '' }}>{{ translate('messages.store_id') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                               for="exampleFormControlInput1">{{ translate('messages.identity_number') }}
                                            <span
                                                class="form-label-secondary text-danger" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('messages.Required.') }}"> *
                                            </span>
                                        </label>
                                        <input type="text" name="identity_number" class="form-control"
                                               placeholder="{{ translate('messages.Ex:') }} DH-23434-LS" value="{{ old('identity_number') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label class="form-label"
                                       for="exampleFormControlInput1">{{ translate('messages.identity_image') }}

                                    <span
                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Max_5_Identity_Images') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.Max_5_Identity_Images') }}"></span>

                                    <span
                                        class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                </span></label>
                                <div>
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-title-icon"><i class="tio-document"></i></span>
                        <span>{{ translate('messages.verification_documents') }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.aadhar_number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="aadhar_number" class="form-control"
                                       placeholder="Ex: 1234 5678 9012" value="{{ old('aadhar_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">{{ translate('messages.aadhar_image') }}<span
                                    class="text-danger">*</span></label>
                            <div class="row" id="aadhar-image-container"></div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.pan_number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="pan_number" class="form-control" placeholder="Ex: AAAAA1234A"
                                       value="{{ old('pan_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="input-label">{{ translate('messages.pan_image') }}<span
                                    class="text-danger">*</span></label>
                            <div class="row" id="pan-image-container"></div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.bike_registration_number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="bike_registration_number" class="form-control" value="{{ old('bike_registration_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label class="input-label">{{ translate('messages.bike_registration_image') }}<span
                                    class="text-danger">*</span></label>
                            <div class="row" id="bike-reg-image-container"></div>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label class="input-label">{{ translate('messages.bike_insurance_image') }}<span
                                    class="text-danger">*</span></label>
                            <div class="row" id="insurance-image-container"></div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.driving_license_number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="driving_license_number" class="form-control" value="{{ old('driving_license_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="input-label">{{ translate('messages.driving_license_image') }}<span
                                    class="text-danger">*</span></label>
                            <div class="row" id="license-image-container"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="tio-bank"></i>
                        {{ translate('messages.bank_details') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.account_number') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.bank_name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.ifsc_code') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ translate('messages.account_type') }}<span
                                        class="text-danger">*</span></label>
                                <select name="account_type" class="form-control" required>
                                    <option value="savings" {{ old('account_type') == 'savings' ? 'selected' : '' }}>{{ translate('messages.savings') }}</option>
                                    <option value="current" {{ old('account_type') == 'current' ? 'selected' : '' }}>{{ translate('messages.current') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-title-icon"><i class="tio-user"></i></span>
                        <span>
                            {{ translate('login_information') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <div class="form-group mb-0">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('messages.phone') }}<span
                                        class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                    </span>
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       placeholder="{{ translate('messages.Ex:') }} 017********" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="js-form-message form-group mb-0">
                                <label class="input-label"
                                       for="signupSrPassword">{{ translate('messages.password') }}<span
                                        class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                    <span
                                        class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                        </span></label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control" name="password"
                                           id="signupSrPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                           title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                           placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                           aria-label="8+ characters required" required
                                           data-msg="Your password is invalid. Please try again."
                                           data-hs-toggle-password-options='{
                                    "target": [".js-toggle-password-target-1"],
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
                        <div class="col-md-4 col-12">
                            <div class="js-form-message form-group mb-0">
                                <label class="input-label"
                                       for="signupSrConfirmPassword">{{ translate('messages.confirm_password') }}<span
                                        class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                    </span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control"
                                           name="confirmPassword"
                                           id="signupSrConfirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                           title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                           placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                           aria-label="8+ characters required" required
                                           data-msg="Password does not match the confirm password."
                                           data-hs-toggle-password-options='{
                                        "target": [".js-toggle-password-target-2"],
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
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                                class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')

    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        "use strict";

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-6 spartan_item_wrapper size--md',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
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
                    toastr.error(
                        '{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        $('#reset_btn').click(function () {
            $('#viewer').attr('src', '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}');
            $("#aadhar-image-container").spartanMultiImagePicker('empty');
            $("#pan-image-container").spartanMultiImagePicker('empty');
            $("#bike-reg-image-container").spartanMultiImagePicker('empty');
            $("#insurance-image-container").spartanMultiImagePicker('empty');
            $("#license-image-container").spartanMultiImagePicker('empty');
            $("#coba").empty().spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-6 spartan_item_wrapper size--md',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
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
                    toastr.error(
                        '{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        $("#aadhar-image-container").spartanMultiImagePicker({
            fieldName: 'aadhar_image',
            maxCount: 1,
            rowHeight: '120px',
            groupClassName: 'col-6 spartan_item_wrapper size--md',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate('messages.file_size_too_big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });

        $("#pan-image-container").spartanMultiImagePicker({
            fieldName: 'pan_image',
            maxCount: 1,
            rowHeight: '120px',
            groupClassName: 'col-6 spartan_item_wrapper size--md',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate('messages.file_size_too_big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });

        $("#bike-reg-image-container").spartanMultiImagePicker({
            fieldName: 'bike_registration_image',
            maxCount: 1,
            rowHeight: '120px',
            groupClassName: 'col-6 spartan_item_wrapper size--md',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate('messages.file_size_too_big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });

        $("#insurance-image-container").spartanMultiImagePicker({
            fieldName: 'bike_insurance_image',
            maxCount: 1,
            rowHeight: '120px',
            groupClassName: 'col-6 spartan_item_wrapper size--md',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate('messages.file_size_too_big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });

        $("#license-image-container").spartanMultiImagePicker({
            fieldName: 'driving_license_image',
            maxCount: 1,
            rowHeight: '120px',
            groupClassName: 'col-6 spartan_item_wrapper size--md',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate('messages.file_size_too_big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    </script>
@endpush
