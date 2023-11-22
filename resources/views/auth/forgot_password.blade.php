<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permanent Makeup University - Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/iconsax/iconsax.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/auth.css') !!}">
    <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
</head>

<body>
    <div class="header">
        <div class="container">
            <div class="logo">
                <a href="#"><img src="{!! url('assets/website-images/logo-2.png') !!}" /></a>
            </div>
        </div>
    </div>
    <div class="auth-form-section">
        <div class="container">
            <div class="auth-form-card">
                <div class="auth-form">
                    <h2>Forgot Password</h2>
                    <p>Please enter your registered Email</p>
                    @include('layouts.partials.messages')
                    <div class="row">
                        <form method="post" action="{{ route('admin.forgot_password.email') }}" id="Form_Login">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="role" value="2" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email ID" required>
                                    @if ($errors->has('email'))
                                    <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <a class="becomeacreator-btn" href="{{ route('login') }}">Cancel</a>
                                    <button class="becomeacreator-btn" type="submit" id="LoginCheck">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <!-- Submit Form with ajax -->
    <script>
        $(document).ready(function() {

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
                },
                messages: {
                    email: {
                        required: 'Please enter email address',
                        minlength: 'Please enter valid email address.',
                        maxlength: 'Please enter valid email address.',
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
        });
    </script>

</body>

</html>