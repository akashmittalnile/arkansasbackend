<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Arkanasas</title>
	<link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/header-footer.css') !!}">
	<link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-plugins/iconsax/iconsax.css') !!}">
	<link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/auth.css') !!}">
	<script src="{!! url('assets/superadmin-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
	<script src="{!! url('assets/superadmin-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
	<script src="{!! url('assets/superadmin-js/function.js') !!}" type="text/javascript"></script>
</head>
<body>
	<div class="header">
		<div class="container">
			<div class="logo">
				<a href="#"><img src="{!! url('assets/superadmin-images/logo-2.png') !!}" /></a>
			</div>
		</div>
	</div>
	<div class="auth-form-section">
		<div class="container">
			<div class="auth-form-card">
				<div class="auth-form">
					<h2>Login as Creator</h2>
					<p>Please Login with your registered Email & Created Password!</p>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" class="form-control" name="" value=""placeholder="Email ID">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="Password" class="form-control" name="" value=""placeholder="Password">
							</div>
						</div>
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
								<button class="becomeacreator-btn" data-bs-toggle="modal" data-bs-target="#becomeacreator">Become a Creator</button>
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
<div class="modal ro-modal fade" id="becomeacreator" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="becomeacreator-form-info">
        		<img src="{!! url('assets/superadmin-images/tick-circle.svg') !!}">
            <h2>Great!! We have receive your Creator Enrollment request</h2>
            <p>Your creator Account is in under review process it will be ready once the System Administrator approve your account.. </p>
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