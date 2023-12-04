@extends('super-admin-layouts.app-master')
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

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Course Fees <span class="text-danger">(in $)</span></h4>
                                                <input type="number" class="form-control" name="course_fee"
                                                    placeholder="Enter Course Fees" min="1" step="0.01" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Category</h4>
                                                <select name="course_category" id="" class="form-control">
                                                    <option value="">Select Category</option>
                                                    @foreach(getCategory(1) as $val)
                                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <select class="form-control livesearch p-3" name="tags[]" multiple="multiple" required></select>
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Course Certificate (jpg,jpeg,png only | Size: 1MB)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="certificates" accept="image/png, image/jpg, image/jpeg" id="PDFJPEGOrPNG"
                                                        class="uploadsignature addsignature" required onchange="loadImageFile(event)">
                                                    <label for="PDFJPEGOrPNG">
                                                        <div class="signature-text">
                                                            <span id="certificates_nam"><img id="prev-img" src="{!! assets('assets/website-images/upload.svg') !!}"> <small id="prev-small-line">Click here to Upload</small></span>
                                                        </div>
                                                    </label>
                                                    @if ($errors->has('certificates'))
                                                        <span class="text-danger text-left">{{ $errors->first('certificates') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Introduction Video (mp4 only | Size: 10MB)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="disclaimers_introduction" accept="video/mp4"
                                                        id="UploadTrainingVideo"
                                                        class="uploadsignature addsignature" onchange="loadVideoFile(event)">
                                                    <label for="UploadTrainingVideo">
                                                        <div class="signature-text">
                                                            <span id="disclaimers_introduction_nam">
                                                                <img id="prev-vid" src="{!! assets('assets/website-images/upload.svg') !!}"> <small id="video-small-line">Click here to Upload</small>
                                                                <video controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume src="" id="vid-prev-tag" class="d-none"></video><small id="video2-small-line" class="d-none">Click here to change video</small>
                                                            </span>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/additional-methods.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- JQuery Search Tags -->
    <script type="text/javascript">
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

    <!-- Style of h2 tag and error message  jQuery Validation -->
    <style>
        .error {
            color: red;
        }
        h2 {
            color: white;
        }
        a{
            text-decoration: none;
        }
        a:hover{
            color: #fff;
        }
        .select2-container--default .select2-selection--multiple{ border: none !important; }
    </style>

    <!-- Include jQuery Validation -->
    <script>
        $(document).ready(function() {
            $(".select2-search__field.form-control").css('border', 'none');

            $.validator.addMethod('filesize', function (value, element, param) {
                return this.optional(element) || (element.files[0].size <= param * 1000000)
            }, 'File size must be less than {0} MB');

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
                        minlength: 1,
                        maxlength: 6
                    },
                    course_category: {
                        required:true,
                    },
                    "tags[]": {
                        required: true,
                    },
                    disclaimers_introduction: {
                        required: true,
                        filesize : 10,
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
                    course_category: {
                        required: 'Please select course category',
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

        const loadImageFile = (event) => {
            $("#prev-img").attr({width: "160", height: "80", src: URL.createObjectURL(event.target.files[0]), style: "object-fit: cover; object-position: center; border-radius: 8px"});
            $("#prev-small-line").html("Click here to change image");
            // $("#remove-img-btn1").removeClass('d-none');
        };

        const loadVideoFile = (event) => {
            $("#prev-vid").hide();
            $("#video-small-line").hide();
            $("#vid-prev-tag").removeClass('d-none');
            $("#vid-prev-tag").attr({"src": URL.createObjectURL(event.target.files[0]), style: "object-fit: cover; object-position: center; border-radius: 8px", width: "160", height: "80",})
            $("#video2-small-line").removeClass('d-none');
            // $("#remove-img-btn1").removeClass('d-none');
        };

    </script>

    <!-- Submit form using Jquery -->
    <script>
        $(document).ready(function() {
            $('#SaveCourse').click(function() {
                $('#AddCourse').submit();
            });
            $(".select2-container .selection .select2-selection .select2-search__field").addClass('form-control');
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
