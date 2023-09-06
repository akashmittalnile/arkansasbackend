@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Add Course')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ url('super-admin/course') }}" class="add-more">Back</a>
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
                                <form method="post" action="{{ route('SA.SubmitCourse') }}" id="AddCourse" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="status" value="1" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Title</h4>
                                                <input type="text" class="form-control" name="title" placeholder="Title" id="title">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Description</h4>
                                                <textarea type="text" class="form-control" name="description" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Course Fees</h4>
                                                <input type="number" class="form-control" name="course_fee"
                                                    placeholder="Enter Course Fees" step="0.01" required>
                                                {{-- @if ($errors->has('course_fee'))
                                                    <span class="text-danger text-left">{{ $errors->first('course_fee') }}</span>
                                                @endif --}}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Valid Up-To</h4>
                                                <input type="date" class="form-control" name="valid_upto" placeholder="4 Month" required>
                                                {{-- @if ($errors->has('valid_upto'))
                                                    <span class="text-danger text-left">{{ $errors->first('valid_upto') }}</span>
                                                @endif --}}
                                            </div>
                                        </div>
                                        

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <select class="form-control livesearch form-control p-3" name="livesearch" multiple="multiple" required></select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Course Certificates(jpg,jpeg,png only|Size:2048)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="certificates" id="PDF/JPEG Or PNG"
                                                        class="uploadsignature addsignature" required>
                                                    <label for="PDF/JPEG Or PNG">
                                                        <div class="signature-text">
                                                            <span id="certificates_name"><img src="{!! url('assets/website-images/upload.svg') !!}"> Click here to Upload</span>
                                                        </div>
                                                    </label>
                                                    @if ($errors->has('certificates'))
                                                        <span class="text-danger text-left">{{ $errors->first('certificates') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Disclaimers & Introduction(mp4 only|Size:2048)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="disclaimers_introduction"
                                                        id="Upload Training Video Or PDF / Paste Video URL Here…"
                                                        class="uploadsignature addsignature">
                                                    <label for="Upload Training Video Or PDF / Paste Video URL Here…">
                                                        <div class="signature-text">
                                                            <span id="disclaimers_introduction_name"><img src="{!! url('assets/website-images/upload.svg') !!}"> Click here to Upload</span>
                                                        </div>
                                                    </label>
                                                    @if ($errors->has('disclaimers_introduction'))
                                                        <span class="text-danger text-left">{{ $errors->first('disclaimers_introduction') }}</span>
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

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- JQuery Search Tags -->
    <script type="text/javascript">
        $('.livesearch').select2({
            placeholder: 'Select tags',
            ajax: {
                url: 'http://127.0.0.1:8000/load-sectors',
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
    
    <!-- Include jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <!-- Style of h2 tag and error message  jQuery Validation -->
    <style>
        .error {
            color: red;
        }
        h2 {
            color: white;
        },
    </style>

    <!-- Include jQuery Validation -->
    {{-- <script>
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
                    livesearch: {
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
                    course_fee: {
                        required: 'Please enter course fee',
                    },
                    valid_upto: {
                        required: 'Please enter valid upto',
                    },
                    livesearch: {
                        required: 'Please enter tags',
                    },
                },

                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                }
            });
        });
    </script> --}}

    <!-- Submit form using Jquery -->
    <script>
        $(document).ready(function() {
            $('#SaveCourse').click(function() {
                $('#AddCourse').submit();
            });
        });
    </script>

    <!-- Append File name -->
    <script>
        $(document).ready(function() {
            $('input[name="certificates"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#certificates_name").text(geekss);
            });
            $('input[name="disclaimers_introduction"]').change(function(e) {
                var geekss = e.target.files[0].name;
                $("#disclaimers_introduction_name").text(geekss);
            });
        });
    </script>

@endsection
