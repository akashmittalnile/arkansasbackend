@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Update Category')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Update Category</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('super-admin/category') }}" class="add-more">Back</a>
                        <a href="#" id="updateCategory" class="add-more">Save & Continue</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pmu-courses-form-section">
                            <h2>Category Details</h2>
                            <div class="pmu-courses-form">
                                <form method="post" action="{{ route('SA.UpdateCategory') }}" id="UpdateCategory"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="id" value="{{ $data->id }}" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Category Name</h4>
                                                <input type="text" class="form-control" name="category_name"
                                                    placeholder="Category Name" id="category_name" value="{{ $data->name }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Category Status</h4>
                                                <select class="form-control" name="cat_status" id="cat_status" required>
                                                    <option value="1" @if ($data->status == 1) selected='selected' @else @endif>Active</option>
                                                    <option value="0" @if ($data->status == 0) selected='selected' @else @endif>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Category Type</h4>
                                                <select class="form-control" name="cat_type" id="cat_type" required>
                                                    <option @if($data->type == '') selected @endif value="">Select Category For</option>
                                                    <option @if($data->type == '1') selected @endif value="1">Course</option>
                                                    <option @if($data->type == '2') selected @endif value="2">Product</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Image (jpg,jpeg,png only | Size: 1MB)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="category_image" id="PDFJPEGOrPNG"
                                                        class="uploadsignature addsignature form-control" accept="image/png, image/jpg, image/jpeg" @if(empty($data->icon)) required @endif onchange="loadImageFile(event)">
                                                    <label for="PDFJPEGOrPNG">
                                                        <div class="signature-text">
                                                            <span id="category_image"><img @if(!empty($data->icon)) width="160", height="80" @endif style="object-fit: cover; object-position: center; border-radius: 8px" id="prev-img"
                                                                    src="{!! url('upload/category-image/'.$data->icon) !!}">@if(empty($data->icon))<small id="prev-small-line">Click here to
                                                                Upload</small>@endif</span>
                                                        </div>
                                                    </label>
                                                    @if ($errors->has('category_image'))
                                                        <span
                                                            class="text-danger text-left">{{ $errors->first('category_image') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <!-- Style of h2 tag and error message  jQuery Validation -->
    <style>
        .error {
            color: red;
        }

        h2 {
            color: white;
        }

        ,
    </style>

    <!-- Include jQuery Validation -->
    <script>
        $(document).ready(function() {

            $.validator.addMethod('filesize', function (value, element, param) {
                return this.optional(element) || (element.files[0].size <= param * 1000000)
            }, 'File size must be less than {0} MB');

            $('#UpdateCategory').validate({
                rules: {
                    category_name: {
                        required: true,
                    },
                    cat_status: {
                        required: true,
                    },
                    category_image: {
                        filesize : 1,
                    },
                    cat_type: {
                        required: true,
                    },
                },
                messages: {
                    category_name: {
                        required: 'Please enter category',
                    },
                    cat_status: {
                        required: 'Please enter status',
                    },
                    cat_type: {
                        required: 'Please enter category type',
                    },
                },

                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);

                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                },
            });
        });

        const loadImageFile = (event) => {
            $("#prev-img").attr({width: "160", height: "80", src: URL.createObjectURL(event.target.files[0])});
            $("#prev-small-line").hide();
            // $("#remove-img-btn1").removeClass('d-none');
        };
    </script>

    <!-- Submit form using Jquery -->
    <script>
        $(document).ready(function() {
            $('#updateCategory').click(function() {
                $('#UpdateCategory').submit();
            });
        });
    </script>

    <!-- Append File name -->
    <script>
        $(document).ready(function() {
            $('input[name="category_image"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#category_image_file").text(geekss);
            });
        });
    </script>

@endsection
