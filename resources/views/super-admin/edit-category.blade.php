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
                                                <h4>Upload Image</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="category_image" id="PDF/JPEG Or PNG"
                                                        class="uploadsignature addsignature">
                                                    <label for="PDF/JPEG Or PNG">
                                                        <div class="signature-text">
                                                            <span id="category_image_file"><img
                                                                    src="{!! url('assets/website-images/upload.svg') !!}"> Click here to
                                                                Upload</span>
                                                        </div>
                                                    </label>
                                                    @if ($errors->has('category_image'))
                                                        <span
                                                            class="text-danger text-left">{{ $errors->first('category_image') }}</span>
                                                    @endif
                                                    @if (!empty($data->icon))
                                                        <a href="{{ url('upload/category-image/'.$data->icon)}}" target="_blank"><i class="las la-image"></i></a>
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
            $('#UpdateCategory').validate({
                rules: {
                    category_name: {
                        required: true,
                    },
                    cat_status: {
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
                },

                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                }
            });
        });
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
