<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permanent Makeup University - Content Creator Registration</title>
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/header-footer.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-plugins/iconsax/iconsax.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/becomeacreator.css') !!}">
    <script src="{!! assets('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
    <script src="{!! assets('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
    <script src="{!! assets('assets/website-js/function.js') !!}" type="text/javascript"></script>
</head>

<body>
    <div class="header">
        <div class="container">
            <div class="logo">
                <a href="#"><img src="{!! assets('assets/website-images/logo-2.png') !!}" /></a>
            </div>
        </div>
    </div>
    <div class="becomeacreator-form-section">
        <div class="container">
            <div class="becomeacreator-form-card">
                <form method="post" action="{{ route('register.perform') }}" id="register_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="role" value="2" />
                    <input type="hidden" name="status" value="0" />
                    <div class="becomeacreator-form">
                        <h2>PERMANENT MAKEUP TRAINING</h2>
                        <p>Create skills for your new career at Arkansas Permanent Cosmetics Institute, a school that
                            truly
                            cares.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="first_name" placeholder="First Name">
                                    @if ($errors->has('first_name'))
                                    <span class="text-danger text-left">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                                    @if ($errors->has('last_name'))
                                    <span class="text-danger text-left">{{ $errors->first('last_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email_add" name="email" placeholder="Email ID">
                                    @if ($errors->has('email'))
                                    <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Please Select Creator Type!</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="arkradio">
                                                <input type="radio" id="Permanent Makeup Training" name="CreatorType" value="1">
                                                <label for="Permanent Makeup Training">
                                                    Permanent Makeup Training
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="arkradio">
                                                <input type="radio" id="Tattooing & Piercing Institute" name="CreatorType" value="2">
                                                <label for="Tattooing & Piercing Institute">
                                                    Tattooing & Piercing Institute
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Create Password" required>
                                    @if ($errors->has('password'))
                                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required>
                                    @if ($errors->has('password_confirmation'))
                                    <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="arkCheckbox">
                                                <input type="checkbox" id="Accept the terms & conditions." name="terms_conditions" checked="checked">
                                                <label for="Accept the terms & conditions.">
                                                    I accept the <a href="{{ route('terms.condition') }}" target="_blank" style="color: #fff;"><u>Terms & Conditions</u></a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="arkCheckbox">
                                                <input type="checkbox" id="privacy_policy" name="privacy_policy" checked="checked">
                                                <label for="Privacy policy.">
                                                    I accept the <a href="{{ route('privacy.policy') }}" target="_blank" style="color: #fff;"><u>Privacy policy</u></a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="becomeacreator-foot">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{-- <button class="becomeacreator-btn" data-bs-toggle="modal"
									data-bs-target="#becomeacreator">Become a Creator</button> --}}
                                    <button class="becomeacreator-btn" type="submit">Become a Creator</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p>Already Have an account? <a href="{{ route('login') }}">LOGIN</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!-- Add card -->
    <div class="modal ro-modal fade" id="becomeacreator" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <i class="isax isax-dcube"></i>
                        <h2>Great!! We have receive your Creator Enrollment request</h2>
                        <p>Your creator Account is in under review process it will be ready once the System
                            Administrator approve your account.. </p>
                        <div class="becomeacreator-btn-action">
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                            <a href="#" class="Login-btn">Login as Creator</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        let arkansasUrl = "{{ env('APP_URL') }}";

        $.validator.addMethod("emailValidate", function(value) {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
        }, 'Please enter valid email address.');

        $.validator.addMethod("AtLeastOnenumber", function(value) {
            return /(?=.*[0-9])/.test(value);
        }, 'At least 1 number is required.');

        $.validator.addMethod("AtLeastOneUpperChar", function(value) {
            return /^(?=.*[A-Z])/.test(value);
        }, 'At least 1 uppercase character is required.');

        $.validator.addMethod("AtLeastOneSpecialChar", function(value) {
            return !/^[A-Za-z0-9 ]+$/.test(value);
        }, 'At least 1 special character is required.');

        $.validator.addMethod("AtLeastOneLowerChar", function(value) {
            return /^(?=.*[a-z])/.test(value);
        }, 'At least 1 lower character is required.');

        $('#register_form').validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                CreatorType: {
                    required: true,
                },
                terms_conditions: {
                    required: true,
                },
                privacy_policy: {
                    required: true,
                },
                email: {
                    required: true,
                    minlength: 10,
                    maxlength: 50,
                    emailValidate: true,
                    remote: {
                        type: 'get',
                        url: arkansasUrl + '/check_email',
                        data: {
                            'email': function () { return $("#email_add").val(); }
                        },
                        dataType: 'json'
                    }
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 30,
                    AtLeastOnenumber: true,
                    AtLeastOneUpperChar: true,
                    AtLeastOneLowerChar: true,
                    AtLeastOneSpecialChar: true
                },
                password_confirmation: {
                    required: true,
                    equalTo: "input[name='password']"
                },
            },
            messages: {
                first_name: {
                    required: 'Please enter first name',
                },
                last_name: {
                    required: 'Please enter last name',
                },
                CreatorType: {
                    required: 'Please select creator type',
                },
                terms_conditions: {
                    required: 'Please accept terms & condition',
                },
                privacy_policy: {
                    required: 'Please accept privacy policy',
                },
                email: {
                    required: 'Please enter email address',
                },
                password: {
                    required: 'Please enter password',
                },
                password_confirmation: {
                    required: 'Please enter confirm password',
                    equalTo: "Password and Confirm password must be same."
                },
            },
            submitHandler: function(form) {
                // This function will be called when the form is valid and ready to be submitted
                form.submit();
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                error.css("font-size", '0.9rem');
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
</script>

</html>