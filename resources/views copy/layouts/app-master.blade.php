<!doctype html>
<html>

<head>
    <?php
    $currentURL = Route::currentRouteName();
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arkanasas</title>
    @if ($currentURL == 'home.index')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/home.css') !!}">
        <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'Home.Performance')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/apexcharts/apexcharts.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/performance.css') !!}">
        <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/apexcharts/apexcharts.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/performance.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'Home.HelpSupport')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/help-support.css') !!}">
        <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'Home.Addcourse')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/home.css') !!}">
        <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'Home.Addcourse2')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/home.css') !!}">
        <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>
    @endif

</head>

<body class="main-site">
    <div class="page-body-wrapper">
        @include('layouts.partials.sidebar')
        <div class="body-wrapper">
            @include('layouts.partials.header')
            @yield('content')
        </div>
    </div>
</body>

</html>
