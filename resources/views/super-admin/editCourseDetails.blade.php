@extends('super-admin-layouts.app-master')
@section('title', 'Permanent Makeup University - Edit Course')
@section('content')
    <div class="body-main-content">
        <div class="pmu-filter-section">
            <div class="pmu-filter-heading">
                <h2>Courses</h2>
            </div>
            <div class="pmu-filter">
                <div class="row">
                    <div class="col-md-12">
                        <a href="#" id="SaveCourse" class="add-more">Update & Continue</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pmu-content-list">
            <div class="pmu-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pmu-courses-form-section">
                            <h2>Edit Course Details</h2>
                            <div class="pmu-courses-form">
                                <form method="post" action="{{ route('SA.updateCourseDetails') }}" id="AddCourse" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="status" value="0" />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Title</h4>
                                                <input type="text" class="form-control" name="title" placeholder="Title" id="title" required value="{{ $course->title }}">
                                                {{-- @error('title')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Description</h4>
                                                <textarea type="text" class="form-control" name="description" placeholder="Description">{{ $course->description }}</textarea>
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
                                                    placeholder="Enter Course Fees" min="1" step="0.01" required value="{{ $course->course_fee }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Valid Up-To</h4>
                                                <input type="date" class="form-control" name="valid_upto" placeholder="4 Month" required value="{{ $course->valid_upto }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4>Category</h4>
                                                <select name="course_category" id="" class="form-control">
                                                    <option @if($course->category_id == "") selected @endif value="">Select Category</option>
                                                    @foreach(getCategory(1) as $val)
                                                        <option @if($course->category_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <select class="form-control livesearch p-3" name="tags[]" multiple="multiple" required>
                                                    @foreach($combined as $val)
                                                        <option @if($val['selected']) selected @endif value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="hide" value="{{encrypt_decrypt('encrypt', $course->id)}}">
                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Course Certificate (jpg,jpeg,png only | Size: 1MB)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="certificates" id="certificates"
                                                        class="uploadsignature addsignature" accept="image/png, image/jpg, image/jpeg" onchange="loadImageFile(event)">
                                                    <label for="certificates">
                                                        <div class="signature-text">
                                                            <span id="certificates_nam"><img id="prev-img" width="160" height="80" style="object-fit: cover; object-position: center; border-radius: 8px" src="{!! url('upload/course-certificates/'.$course->certificates) !!}"> <small id="prev-small-line">Click here to change image</small></span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Introduction Video (mp4 only | Size: 2MB)</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="disclaimers_introduction"
                                                        id="disclaimers_introduction"
                                                        class="uploadsignature addsignature" accept="video/mp4" onchange="loadVideoFile(event)">
                                                    <label for="disclaimers_introduction">
                                                        <div class="signature-text">
                                                            <span id="disclaimers_introduction_nam">
                                                                <video width="160" height="80" style="object-fit: cover; object-position: center; border-radius: 8px" controls controlslist="nodownload noplaybackrate" disablepictureinpicture volume src="{!! url('upload/disclaimers-introduction/'.$course->introduction_image) !!}" id="vid-prev-tag"></video><small id="video-small-line">Click here to Change video</small>
                                                            </span>
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
            tags: true,
            // ajax: {
            //     url: "{{ route('load-sectors') }}",
            //     dataType: 'json',
            //     delay: 250,
            //     processResults: function (data) {
            //         return {
            //             results: $.map(data, function (item) {
            //                 return {
            //                     text: item.tag_name,
            //                     id: item.id
            //                 }
            //             })
            //         };
            //     },
            //     cache: true
            // }
        });
        
    </script>

    <style>
        .error {
            color: red;
        }
    </style>

    <script>
        $(document).ready(function() {

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
                    valid_upto: {
                        required:true,
                    },
                    "tags[]": {
                        required: true,
                    },
                    course_category: {
                        required:true,
                    },
                    disclaimers_introduction: {
                        filesize : 2,
                    },
                },
                messages: {
                    title: {
                        required: 'Please enter title',
                    },
                    description: {
                        required: 'Please enter description',
                    },
                    "tags[]": {
                        required: 'Please enter tags',
                    },
                    course_category: {
                        required: 'Please select course category',
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
            $("#prev-img").attr({src: URL.createObjectURL(event.target.files[0])});
            $("#prev-small-line").hide();
            // $("#remove-img-btn1").removeClass('d-none');
        };

        const loadVideoFile = (event) => {
            $("#prev-vid").hide();
            $("#vid-prev-tag").attr({"src": URL.createObjectURL(event.target.files[0]), style: "object-fit: cover; object-position: center; border-radius: 8px", width: "160", height: "80",})
            // $("#remove-img-btn1").removeClass('d-none');
        };
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
