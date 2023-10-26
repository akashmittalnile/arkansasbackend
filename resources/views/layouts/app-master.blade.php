<!doctype html>
<html>

<head>
    <?php
    $currentURL = Route::currentRouteName();
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> @yield('title', config('app.name'))</title>
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/line-awesome/css/line-awesome.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/header-footer.css') !!}">

    @stack('css')

    @if ($currentURL == 'check_status')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/auth.css') !!}">
    @endif

    @if ($currentURL == 'Home.Performance')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/apexcharts/apexcharts.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/performance.css') !!}">
        <script src="{!! url('assets/website-plugins/apexcharts/apexcharts.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/website-js/performance.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'Home.HelpSupport')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/help-support.css') !!}">
    @endif

    @if ($currentURL == 'Home.order.details')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/product-details.css') !!}">
    @endif

    @if ($currentURL == 'Home.students' || $currentURL == 'Home.student.details')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/students.css') !!}">
    @endif

    @if ($currentURL == 'Home.earnings' || $currentURL == 'Home.payment.request' || $currentURL == 'Home.CourseList' || $currentURL == 'Home.progress.report')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/earnings.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/course.css') !!}">
    @endif

    @if ($currentURL == 'home.index' || $currentURL == 'Home.Addcourse'|| $currentURL == 'Home.Addcourse2' || $currentURL == 'Home.CourseList' || $currentURL == 'Home.edit.course' || $currentURL == 'Home.view.course' || $currentURL == 'Home.progress.report')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/home.css') !!}">
        <script src="{!! url('assets/website-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
    @endif

    <script src="{!! url('assets/website-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/website-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/website-js/function.js') !!}" type="text/javascript"></script>

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

<script type="text/javascript">
    let arkansasUrl = "{{ env('APP_URL') }}";
    console.log("url => ", arkansasUrl);
</script>

</html>
