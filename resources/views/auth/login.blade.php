<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arkanasas</title>
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
                    <h2>Login as Creator</h2>
                    <p>Please Login with your registered Email & Created Password!</p>
                    @include('layouts.partials.messages')
                    <div class="row">
                        <form method="post" action="{{ route('login.perform') }}" id="Form_Login">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="role" value="2" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" value=""
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
                                    <button class="becomeacreator-btn" id="LoginCheck" type="button">Login</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <a class="ForgotPassword-text" href="#">Forgot Password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="auth-foot">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="becomeacreator-btn" href="{{ route('register.show') }}">Become a Creator</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Already Have an account? <a href="#">LOGIN</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Add card -->
    <div class="modal ro-modal fade" id="becomeacreator_login" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="becomeacreator-form-info">
                        <img src="{!! url('assets/website-images/tick-circle.svg') !!}">
                        {{-- <h2>Great!! We have receive your Creator Enrollment request</h2> --}}
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

    <!-- Submit Form with ajax -->
    <script>
        $('#LoginCheck').on('click', function() {
            var admin_email = $('input[name="email"]').val();
            $.ajax({
                url: "{{ route('check_status') }}",
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
                        $('#becomeacreator_login').modal('show');
                        e.preventDefault();
                    } else {
                        $('#Form_Login').submit();
                    }
                }
            });
        });
    </script>
    
</body>
</html>
