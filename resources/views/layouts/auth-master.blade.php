<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap core CSS -->
    {{-- <link href="{!! assets('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet"> --}}
    {{-- <link href="{!! assets('assets/css/signin.css') !!}" rel="stylesheet"> --}}

    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/header-footer.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-plugins/iconsax/iconsax.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/auth.css') !!}">
    <script src="{!! assets('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
    <script src="{!! assets('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
    <script src="{!! assets('assets/website-js/function.js') !!}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{!! assets('assets/website-css/becomeacreator.css') !!}">
</head>
<body>

  <div class="header">
		<div class="container">
			<div class="logo">
				<a href="#"><img src="{!! assets('assets/website-images/logo-2.png') !!}" /></a>
			</div>
		</div>
	</div>
  @yield('content')

</body>
</html>