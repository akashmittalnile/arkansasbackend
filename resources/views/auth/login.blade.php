<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permanent Makeup University - Content Creator Login</title>
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/header-footer.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-plugins/iconsax/iconsax.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/auth.css') !!}">
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
    <div class="auth-form-section">
        <div class="container">
            <div class="auth-form-card">
                <div class="auth-form">
                    <h2>Login as Content Creator</h2>
                    <p>Please Login with your registered Email & Created Password!</p>
                    @include('layouts.partials.messages')
                    <div class="row">
                        <form method="post" action="{{ route('login.perform') }}" id="Form_Login">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="role" value="2" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" value="{{ old('email') }}"
                                        placeholder="Email ID" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="Password" class="form-control" name="password" value=""
                                        placeholder="Password" required>
                                    @if ($errors->has('password'))
                                        <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <button class="becomeacreator-btn" type="button" id="LoginCheck">Login</button>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <a class="ForgotPassword-text" href="{{ route('admin.forgot.password') }}">Forgot Password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="auth-foot">
                    <div class="row">
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <p style="font-weight: 600;">Become a Creator?</p>
                            </div>
                        </div> -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <a class="becomeacreator-btn" href="{{ route('register.show') }}">Become a Creator</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add card -->
    <div class="modal ro-modal fade" id="becomeacreator" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <img src="{!! assets('assets/website-images/tick-circle.svg') !!}">
                        <h2>We have received your Creator Enrollment request</h2>
                        <p>Your account is under review please wait for admin to approve the request we will notify you once it is approved via email.</p>
                        <div class="becomeacreator-btn-action">
                            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                            {{-- <a href="#" class="Login-btn">Login as Creator</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <!-- Submit Form with ajax -->
    <script>
        $(document).ready(function(){

            let arkansasUrl = "{{ env('APP_URL') }}";

            $.validator.addMethod("emailValidate", function(value) {
                return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
            }, 'Please enter valid email address.');

            $('#Form_Login').validate({
                rules: {
                    email: {
                        required: true,
                        minlength: 10,
                        maxlength: 50,
                        emailValidate: true
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 30,
                    },
                },
                messages: {
                    email: {
                        required: 'Please enter email address',
                    },
                    password: {
                        required: 'Please enter password',
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

            $('#LoginCheck').click(function(event){
                var admin_email = $('input[name="email"]').val();
                $.ajax({
                    url: arkansasUrl + "/check_status",
                    method: 'GET',
                    data: {
                        admin_email: admin_email,
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data == 1) {
                            $('#becomeacreator').modal('show');
                            e.preventDefault();
                        } else {
                            $('#Form_Login').submit();
                        }
                    }
                });
            });
        });
    </script>

    </body>

    </html>
