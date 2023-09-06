@extends('layouts.app-master')

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
                    {{-- <div class="col-md-3">
                        <div class="chapter-card disabled">
                            <h3>Chapter list</h3>
                            @if($CourseChapters->isEmpty())
                                <tr>
                                    <td colspan="11" class="text-center">
                                        No record found
                                    </td>
                                </tr>
                            @elseif(!$CourseChapters->isEmpty())
                                @foreach($CourseChapters as $data)
                                    <div class="chapter-list">
                                        <div class="chapter-item">
                                            <span>Chapter 1</span> <a href="#"><img src="{!! url('assets/website-images/close-circle.svg') !!}"></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="chapter-action">
                                <a class="add-chapter-btn" href="#">Add Chapter</a>
                            </div>
                        </div>
                    </div> --}}

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
                                                <input type="month" class="form-control" name="valid_upto" placeholder="4 Month" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Tags With Comma</h4>
                                                <input type="text" class="form-control" name="tags"
                                                    placeholder="Enter Tags With Comma" required>
                                                {{-- <div class="tags-list">
                                                    <div class="Tags-text">Tattoo Course <img src="{!! url('assets/website-images/close-circle.svg') !!}">
                                                    </div>
                                                    <div class="Tags-text">Body Piercing <img src="{!! url('assets/website-images/close-circle.svg') !!}">
                                                    </div>
                                                    <div class="Tags-text">Tattoo <img src="{!! url('assets/website-images/close-circle.svg') !!}"></div>
                                                    <div class="Tags-text">Tattoos 2023 <img src="{!! url('assets/website-images/close-circle.svg') !!}">
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h4>Upload Course Certificates</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="certificates" id="certificates"
                                                        class="uploadsignature addsignature" required>
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
                                                <h4>Disclaimers & Introduction</h4>
                                                <div class="upload-signature">
                                                    <input type="file" name="disclaimers_introduction"
                                                        id="disclaimers_introduction"
                                                        class="uploadsignature addsignature" required>
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
                    
                },

                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                }
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
