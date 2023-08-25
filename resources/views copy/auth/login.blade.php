@extends('layouts.auth-master')

@section('content')
    <div class="auth-form-section">
        <div class="container">
            <div class="auth-form-card">
                <div class="auth-form">
                    <h2>Login as Creator</h2>
                    <p>Please Login with your registered Email & Created Password!</p>
                    @include('layouts.partials.messages')
                    <div class="row">
                        <form method="post" action="{{ route('login.perform') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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

                            @include('auth.partials.copy')
                        

                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <a class="ForgotPassword-text" href="#">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="auth-foot">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="becomeacreator-btn" data-bs-toggle="modal"
                                    data-bs-target="#becomeacreator">Become a Creator</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Already Have an account? <a href="#">LOGIN</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    {{-- <!-- Add card -->
	<div class="modal ro-modal fade" id="becomeacreator" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="becomeacreator-form-info">
                    <img src="{!! url('assets/website-images/tick-circle.svg') !!}">
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
    </div> --}}
</div>
@endsection
