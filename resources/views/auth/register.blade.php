<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arkanasas</title>
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/iconsax/iconsax.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/becomeacreator.css') !!}">
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
    <div class="becomeacreator-form-section">
        <div class="container">
            <div class="becomeacreator-form-card">
                <form method="post" action="{{ route('register.perform') }}">
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
                                    <input type="text" class="form-control" name="first_name"
                                        placeholder="First Name" required>
									@if ($errors->has('first_name'))
										<span class="text-danger text-left">{{ $errors->first('first_name') }}</span>
									@endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="last_name" placeholder="Last Name"
                                        required>
									@if ($errors->has('last_name'))
										<span class="text-danger text-left">{{ $errors->first('last_name') }}</span>
									@endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email ID"
                                        required>
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
                                                <input type="radio" id="Permanent Makeup Training" name="CreatorType" value="1" required>
                                                <label for="Permanent Makeup Training">
                                                    Permanent Makeup Training
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="arkradio">
                                                <input type="radio" id="Tattooing & Piercing Institute"
                                                    name="CreatorType" value="2">
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
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Create Password" required>
									@if ($errors->has('password'))
										<span class="text-danger text-left">{{ $errors->first('password') }}</span>
									@endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password_confirmation"
                                        placeholder="Confirm New Password" required>
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
                                                <input type="checkbox" id="Accept the terms & conditions."
                                                    name="terms_conditions" checked="checked">
                                                <label for="Accept the terms & conditions.">
                                                    Accept the terms & conditions.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="arkCheckbox">
                                                <input type="checkbox" id="privacy_policy" name="privacy_policy"
                                                    checked="checked">
                                                <label for="Privacy policy.">
                                                    Privacy policy.
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
    <div class="modal ro-modal fade" id="becomeacreator" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

</html>
