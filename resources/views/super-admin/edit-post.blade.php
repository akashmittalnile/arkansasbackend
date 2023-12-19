@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Edit Page')
@section('content')
<meta name="_token" content="{{csrf_token()}}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
<div class="body-main-content">
    <div class="d-flex justify-content-between">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Edit Page</h2>
            </div>
        </div>
        <a href="{{ route('SA.Posts') }}"><button class="product-list-btn"><i class="bbi bi-caret-left me-2"></i>Post List</button></a>
    </div>

    <div class="pmu-content-list mt-3">
        <div class="pmu-content">
            <form method="post" action="{{ route('SA.Update.Post') }}" id="AddProduct" enctype="multipart/form-data">@csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="product-item-card">
                            <div class="card-header">
                                <h2>Page Info</h2>
                            </div>
                            <div class="pmu-item-content">
                                <div class="mb-3 form-group errorInForm">
                                    <label for="productname" class="form-label">Title <b class="text-danger">*</b></label>
                                    <input type="text" placeholder="Title" name="title" class="form-control" id="productname" aria-describedby="productname" value="{{ $data->title }}">
                                    <span class="error">{{ $errors->first('title') }}</span>
                                </div>
                                <div class="mb-5 form-group errorInForm">
                                    <label for="makeMeSummernote1" class="form-label">Description <b class="text-danger">*</b></label>
                                    <textarea name="description" cols="30" rows="10" id="makeMeSummernote1" class="form-control full-description" placeholder="Description">{{ $data->description }}</textarea>
                                    <span class="error">{{ $errors->first('description') }}</span>
                                    <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="product-item-card">
                            <div class="card-header">
                                <h2>Status</h2>
                            </div>
                            <div class="pmu-item-content">
                                <div class="mb-3">
                                    <label for="makeMeSummernote2" class="form-label">Status <b class="text-danger">*</b></label>
                                    <div class="col-md-12 form-group errorInForm">
                                        <select class="form-select" aria-label="Default select example" name="status">
                                            <option @if($data->status=="" ) selected @endif value="">Select Status</option>
                                            <option @if($data->status=="1" ) selected @endif value="1">Active</option>
                                            <option @if($data->status=="0" ) selected @endif value="0">In-active</option>
                                        </select>
                                        <span class="error">{{ $errors->first('status') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="product-item-card update-card">
                        <div class="pmu-item-content bg-white">
                            <button class="btn update-btn btn-sm" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include jQuery Validation Plugin -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="{{ assets('assets/superadmin-js/create-product.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

<!-- Style of h2 tag and error message  jQuery Validation -->
<style>
    .error {
        color: red;
    }

    h2 {
        color: white;
    }
</style>

<!-- Include jQuery Validation -->
<script>
    $(document).ready(function() {

        $('#AddProduc').validate({
            rules: {
                title: {
                    required: true,
                },
                description: {
                    required: true,
                },
                status: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Please enter title'
                },
                status: {
                    required: 'Please select status'
                },
            },
            submitHandler: function(form) {
                // This function will be called when the form is valid and ready to be submitted
                form.submit();
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".errorInForm").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>

@endsection