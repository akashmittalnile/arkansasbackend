@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - Notifications')
@section('content')
<div class="body-main-content">
    <div class="pmu-content-list">
        <div class="pmu-content">
            <div class="pmu-filter-section">
                <div class="pmu-filter-heading">
                    <h2>Create Notitication </h2>
                </div>
                <div class="pmu-filter">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('SA.Notifications') }}" class="add-more">Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('SA.Store.Notifications') }}" method="POST" enctype="multipart/form-data" id="notification-form"> @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="manage-notification-filter">
                            <div class="manage-noti-item">
                                <h2>Push Notification To:</h2>
                                <ul class="managenotification-list">
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="Student" checked name="PushNotificationTo" value="1">
                                            <label for="Student">
                                                Students
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="ContentCreator" name="PushNotificationTo" value="2">
                                            <label for="ContentCreator">
                                                Content Creator
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="manage-noti-item content-creator-box">
                                <h2>Choose Content Creator</h2>
                                <ul class="managenotification-list">
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="SelectAll" name="ChooseContenttype" value="A" checked>
                                            <label for="SelectAll">
                                                Select All
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="SelectCreator" name="ChooseContenttype" value="S">
                                            <label for="SelectCreator">
                                                <div class="ChooseContent-item">
                                                    <select class="form-control ccsearch" name="cc[]" multiple="multiple">
                                                        @forelse($user as $val)
                                                            <option value="{{ $val->id }}">{{ $val->first_name }} {{ $val->last_name }}</option>
                                                        @empty
                                                            <option value="">No content creator</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>


                            <!-- <div class="manage-noti-item">
                                <h2>Notification Type</h2>
                                <ul class="managenotification-list">
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="CourseNotification" name="Notificationtype" value="1">
                                            <label for="CourseNotification">
                                                Course Notification
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="PriceNotification" name="Notificationtype" value="2">
                                            <label for="PriceNotification">
                                                Price Notification
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pmu-radio">
                                            <input type="radio" id="ReviewNotification" name="Notificationtype" value="3">
                                            <label for="ReviewNotification">
                                                Review Notification
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div> -->
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="notification-create-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Notitication Title</h4>
                                        <input type="text" class="form-control" name="title" placeholder="Title">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Description</h4>
                                        <textarea type="text" class="form-control" name="description" placeholder="Description" data-gramm="false" wt-ignore-input="true"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Upload Image (jpg,jpeg,png only | Size: 1MB)</h4>
                                        <div class="upload-signature">
                                            <input type="file" name="img" id="PDFJPEGOrPNG" class="uploadsignature addsignature" accept="image/png, image/jpg, image/jpeg" onchange="loadImageFile(event)">
                                            <label for="PDFJPEGOrPNG">
                                                <div class="signature-text">
                                                <span id="certificates_nam"><img id="prev-img" src="{!! url('assets/superadmin-images/upload.svg') !!}"> <small id="prev-small-line">Click here to Upload</small></span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="cancelbtn" type="button">Cancel</button>
                                        <button class="Createbtn" type="submit">Create New</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $(".select2-container .selection .select2-selection .select2-search__field").addClass('form-control');

        $(".content-creator-box").hide();

        $(document).on('change', "input[name='PushNotificationTo']", function(){
            let val = $(this).val();
            if(val == 1){
                $(".content-creator-box").hide();
            }else {
                $(".content-creator-box").show();
            } 
        })

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1000000)
        }, 'File size must be less than {0} MB');

        $('#notification-form').validate({
            rules: {
                title: {
                    required: true,
                },
                description: {
                    required: true,
                },
                img: {
                    required: true,
                    filesize : 1
                }
            },
            messages: {
                title: {
                    required: 'Please enter title',
                },
                description: {
                    required: 'Please enter description',
                },
                img: {
                    required: 'Please upload a image',
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
    })

    const loadImageFile = (event) => {
        $("#prev-img").attr({width: "160", height: "80", src: URL.createObjectURL(event.target.files[0]), style: "object-fit: cover; object-position: center; border-radius: 8px"});
        $("#prev-small-line").html("Click here to change image");
    };

    $('.ccsearch').select2({
        placeholder: 'Select content creators',
        tags: true,
    });
    
</script>
@endsection