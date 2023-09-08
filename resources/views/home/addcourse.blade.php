@extends('layouts.app-master')
@section('title', 'Permanent Makeup University - Add Course')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="#" id="SaveCourse" class="add-more">Save & Continue</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pmu-courses-form-section">
                            <h2>Course Details</h2>
                            <div class="pmu-courses-form">
                                <form method="post" action="{{ route('Home.submitcourse') }}" id="AddCourse" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="status" value="0" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Title</h4>
                                                <input type="text" class="form-control" name="title" placeholder="Title" id="title" required>
                                                {{-- @error('title')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Description</h4>
                                                <textarea type="text" class="form-control" name="description" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Course Fees Type</h4>
                                                <ul class="pmu-feestype-list">
                                                    <li>
                                                        <div class="pmu-radio">
                                                            <input type="radio" id="Monthly" name="fee_type">
                                                            <label for="Monthly">
                                                                Monthly
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="pmu-radio">
                                                            <input type="radio" id="Yearly" name="fee_type">
                                                            <label for="Yearly">
                                                                Yearly
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Course Fees</h4>
                                                <input type="number" class="form-control" name="course_fee"
                                                    placeholder="Enter Course Fees" step="0.01" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Valid Up-To</h4>
                                                <input type="date" class="form-control" name="valid_upto" placeholder="4 Month" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <select class="form-control livesearch p-3" name="tags[]" multiple="multiple" required></select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Course Certificate (jpg,jpeg,png only | Size:2048)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="certificates" id="certificates"
                                                        class="uploadsignature addsignature" required accept="image/png, image/jpg, image/jpeg">
                                                    <label for="certificates">
                                                        <div class="signature-text">
                                                            <span id="certificates_name"><img src="{!! url('assets/website-images/upload.svg') !!}"> Click here to Upload</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Introduction Video (mp4 only | Size:2048)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="disclaimers_introduction"
                                                        id="disclaimers_introduction"
                                                        class="uploadsignature addsignature" required accept="video/mp4">
                                                    <label for="disclaimers_introduction">
                                                        <div class="signature-text">
                                                            <span id="disclaimers_introduction_name"><img src="{!! url('assets/website-images/upload.svg') !!}"> Click here to Upload</span>
                                                        </div>
                                                    </label>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/additional-methods.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- Append File name -->
    <script>
        $(document).ready(function() {
            $('input[name="disclaimers_introduction"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#disclaimers_introduction_name").text(geekss);
            });
            $('input[name="certificates"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#certificates_name").text(geekss);
            });
            $(".select2-container .selection .select2-selection .select2-search__field").addClass('form-control');
        });
        $('.livesearch').select2({
            placeholder: 'Select tags',
            ajax: {
                url: "{{ route('load-sectors') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.tag_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>

    <style>
        .error {
            color: red;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#AddCourse').validate({
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    course_fee: {
                        required: true,
                    },
                    valid_upto: {
                        required:true,
                    },
                    "tags[]": {
                        required: true,
                    },
                    certificates: {
                        required: true,
                    },
                    disclaimers_introduction: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: 'Please enter title',
                    },
                    description: {
                        required: 'Please enter description',
                    },
                    certificates: {
                        required: "Please choose a file to upload.",
                        extension: "Please upload a file in one of these formats: jpg, jpeg, png, ico, bmp.",
                    },
                    disclaimers_introduction: {
                        required: "Please choose a file to upload.",
                        extension: "Please upload a file in one of these formats: jpg, jpeg, png, ico, bmp.",
                    },
                    "tags[]": {
                        required: 'Please enter tags',
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
    </script>

    <script>
        $(document).ready(function() {
            $('#SaveCourse').click(function() {
                document.getElementById("AddCourse").focus();
                $('#AddCourse').submit();
            });
        });
    </script>
@endsection
