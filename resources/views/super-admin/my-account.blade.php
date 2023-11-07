@extends('super-admin-layouts.app-master')
@section('title', 'Makeup University - My Account')
@section('content')
<div class="body-main-content">
    <div class="pmu-filter-section">
        <div class="pmu-filter-heading">
            <h2>My Account</h2>
        </div>
        <div class="pmu-search-filter wd40">

        </div>
    </div>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('message') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="pmu-tab-nav">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#Profile" data-bs-toggle="tab">Profile</a> </li>
            <li class="nav-item"><a class="nav-link" href="#Password" data-bs-toggle="tab" id="passwordTab">Password</a> </li>
            <li class="nav-item"><a class="nav-link" href="#TaxSetting" data-bs-toggle="tab" id="arkansasSetting">Arkansas Setting</a> </li>
        </ul>
    </div>

    <div class="pmu-tab-content tab-content">
        <div class="tab-pane active" id="Profile">
            <div class="myaccount-card">
                <div class="myaccount-card-form">
                    <form action="{{ route('SA.Store.Mydata') }}" method="POST" id="my-account-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>First Name</h4>
                                    <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ $user->first_name }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Last Name</h4>
                                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Phone</h4>
                                    <input type="number" min="1000000" class="form-control" name="phone" placeholder="Phone" value="{{ $user->phone }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Email Address</h4>
                                    <input type="text" class="form-control" name="email" placeholder="Email" disabled value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Upload Profile Image (jpg,jpeg,png only | Size: 1MB)</h4>
                                    <div class="upload-signature">
                                        <input type="file" name="profile" accept="image/png, image/jpg, image/jpeg" id="profileimg" class="uploadsignature addsignature" onchange="loadImageFile(event, 1)">
                                        <label for="profileimg">
                                            <div class="signature-text">
                                                <span id="certificates_nam">@if($user->profile_image!="" && $user->profile_image!=null) <img style="object-fit: cover; object-position: center; border-radius: 8px" width="160" height="80" id="prev-img1" src="{{ url( 'upload/profile-image/' . $user->profile_image) }}"> <small id="prev-small-line1">Click here to change image</small> @else <img id="prev-img1" src="{!! url('assets/website-images/upload.svg') !!}"> <small id="prev-small-line1">Click here to Upload</small> @endif</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Business Title</h4>
                                    <input type="text" class="form-control" name="bus_name" placeholder="Business Title" value="{{ $user->company_name }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Business Sub Title</h4>
                                    <input type="text" class="form-control" name="bus_title" placeholder="Business Sub Title" value="{{ $user->professional_title }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Upload Business Logo (jpg,jpeg,png only | Size: 1MB)</h4>
                                    <div class="upload-signature">
                                        <input type="file" name="logo" accept="image/png, image/jpg, image/jpeg" id="logoimg" class="uploadsignature addsignature" onchange="loadImageFile(event, 2)">
                                        <label for="logoimg">
                                            <div class="signature-text">
                                                <span id="certificates_nam">@if($user->business_logo!="" && $user->business_logo!=null) <img style="object-fit: cover; object-position: center; border-radius: 8px" width="160" height="80" id="prev-img2" src="{{ url( 'upload/business-logo/' . $user->business_logo) }}"> <small id="prev-small-line2">Click here to change image</small> @else <img id="prev-img2" src="{!! url('assets/website-images/upload.svg') !!}"> <small id="prev-small-line2">Click here to Upload</small> @endif</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Upload Signature (jpg,jpeg,png only | Size: 1MB)</h4>
                                    <div class="upload-signature">
                                        <input type="file" name="signature" accept="image/png, image/jpg, image/jpeg" id="signatureimg" class="uploadsignature addsignature" onchange="loadImageFile(event, 3)">
                                        <label for="signatureimg">
                                            <div class="signature-text">
                                                <span id="certificates_nam">@if($user->signature!="" && $user->signature!=null)<img style="object-fit: cover; object-position: center; border-radius: 8px" width="160" height="80" id="prev-img3" src="{{ url( 'upload/signature/' . $user->signature) }}"> <small id="prev-small-line3">Click here to change image</small> @else <img id="prev-img3" src="{!! url('assets/website-images/upload.svg') !!}"> <small id="prev-small-line3">Click here to Upload</small> @endif</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancelbtn">Cancel</button>
                                    <button class="Createbtn" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="Password">
            <div class="myaccount-card">
                <div class="myaccount-card-form">
                    <form action="{{ route('SA.Change.Password') }}" method="POST" id="password-form">@csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Old Password</h4>
                                    <input type="password" class="form-control" name="old_pswd" id="old_pswd" placeholder="Old Password">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>New Password </h4>
                                    <input type="password" class="form-control" name="new_pswd" id="new_pswd" placeholder="Enter New Password ">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Confirm New Password </h4>
                                    <input type="password" class="form-control" name="c_new_pswd" id="c_new_pswd" placeholder="Confirm New Password ">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancelbtn" type="button">Cancel</button>
                                    <button class="Createbtn" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="TaxSetting">
            <div class="myaccount-card">
                <div class="myaccount-card-form">
                    <form action="{{ route('SA.Store.Setting') }}" method="POST">@csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Tax <span class="text-danger">(in %)</span></h4>
                                    <input type="number" min="1" step="0.1" max="99.9" class="form-control" name="value" placeholder="Tax (in %)" required value="{{ $tax->attribute_value ?? '' }}">
                                    <input type="hidden" value="{{ encrypt_decrypt('encrypt', 'tax') }}" name="attribute">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="Createbtn mt-3">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('SA.Store.Setting') }}" method="POST">@csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Course Purchased Validity <span class="text-danger">(in days)</span></h4>
                                    <input type="number" min="1" step="1" max="365" class="form-control" name="value" placeholder="Course Purchased Validity (in days)" required value="{{ $course->attribute_value ?? '' }}">
                                    <input type="hidden" value="{{ encrypt_decrypt('encrypt', 'course') }}" name="attribute">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="Createbtn mt-3">Save</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    var tab = "{{ session()->get('tab') ?? false }}";
    if (tab == 1) $("#passwordTab").get(0).click();
    if (tab == 2) $("#arkansasSetting").get(0).click();

    $(document).ready(function() {

        $.validator.addMethod("notEqual", function(value, element, param) {
            return this.optional(element) || value != $(param).val();
        }, 'Old password and New password should not same.');

        $('#password-form').validate({
            rules: {
                old_pswd: {
                    required: true,
                    minlength: 6,
                    remote: {
                        type: 'get',
                        url: arkansasUrl + '/check_password',
                        data: {
                            'password': function () { return $("#old_pswd").val(); }
                        },
                        dataType: 'json'
                    }
                },
                new_pswd: {
                    required: true,
                    maxlength: 15,
                    minlength: 6,
                    notEqual: "input[name='old_pswd']"
                },
                c_new_pswd: {
                    required: true,
                    equalTo: "input[name='new_pswd']"
                },
            },
            messages: {
                old_pswd: {
                    required: 'Please enter old password'
                },
                new_pswd: {
                    required: 'Please enter new password'
                },
                c_new_pswd: {
                    required: 'Please enter confirm new password',
                    equalTo: "New password and Confirm new password must be same."
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

        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1000000)
        }, 'File size must be less than {0} MB');

        $('#my-account-form').validate({
            rules: {
                first_name: {
                    required: true,
                },
                phone: {
                    required: true,
                    minlength: 8,
                    maxlength: 12
                },
                bus_name: {
                    required: true,
                },
                bus_title: {
                    required: true,
                },
                signature: {
                    filesize: 1,
                },
                logo: {
                    filesize: 1,
                },
                profile: {
                    filesize: 1,
                },
            },
            messages: {
                first_name: {
                    required: 'Please enter first name',
                },
                phone: {
                    required: 'Please enter phone',
                },
                bus_name: {
                    required: 'Please enter business title',
                },
                bus_title: {
                    required: 'Please enter business sub title',
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

    const loadImageFile = (event, num) => {
        $("#prev-img" + num).attr({
            width: "160",
            height: "80",
            src: URL.createObjectURL(event.target.files[0]),
            style: "object-fit: cover; object-position: center; border-radius: 8px"
        });
        $("#prev-small-line" + num).html("Click here to change image");
    };
</script>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/myaccount.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/course.css') !!}">
@endpush