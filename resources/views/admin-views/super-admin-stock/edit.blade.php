@extends('layouts.admin.app')

@section('title', translate('Update Super Admin Stock'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('messages.update_super_admin_stock')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.super-admin-stocks.update', [$stock['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{translate('messages.new_stock')}}" value="{{$stock['name']}}" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.category')}}<span
                                        class="input-label-secondary">*</span></label>
                                <select name="category_id" class="form-control js-select2-custom" required>
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}" {{$category->id==$stock->category_id? 'selected':''}}>{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.price')}}</label>
                                <input type="number" min="0" max="100000" step="0.01" name="price" value="{{$stock['price']}}" class="form-control" placeholder="Ex : 100" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.stock')}}</label>
                                <input type="number" min="0" max="100000" name="stock" value="{{$stock['stock']}}" class="form-control" placeholder="Ex : 100" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.discount')}}</label>
                                <input type="number" min="0" max="100000" name="discount" value="{{$stock['discount']}}" class="form-control" placeholder="Ex : 100">
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.discount_type')}}</label>
                                <select name="discount_type" class="form-control js-select2-custom">
                                    <option value="percent" {{$stock['discount_type']=='percent'?'selected':''}}>{{translate('messages.percent')}}</option>
                                    <option value="amount" {{$stock['discount_type']=='amount'?'selected':''}}>{{translate('messages.amount')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{translate('messages.stock_image')}}</label><small style="color: red">* ( {{translate('messages.ratio')}} 1:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose_file')}}</label>
                                </div>
                                <div class="text-center mt-2">
                                    <img style="width: 30%;border: 1px solid; border-radius: 10px;" id="viewer"
                                         src="{{asset('storage/app/public/super_admin_stock')}}/{{$stock['image']}}" alt="stock image"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.description')}}</label>
                        <textarea type="text" name="description" class="form-control ckeditor">{{$stock['description']}}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">{{translate('messages.update')}}</button>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
