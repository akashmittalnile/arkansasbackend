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
    <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/header-footer.css') !!}">
    
    @stack('css')

    @if ($currentURL == 'SA.Dashboard')
        
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-plugins/apexcharts/apexcharts.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/home.css') !!}">
        <script src="{!! url('assets/superadmin-plugins/apexcharts/apexcharts.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/superadmin-js/dashboard.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'SA.HelpSupport')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/help-support.css') !!}">
    @endif

    @if ($currentURL == 'SA.Performance')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-plugins/apexcharts/apexcharts.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/performance.css') !!}">
        <script src="{!! url('assets/superadmin-js/jquery-3.7.0.min.js" type="text/javascript') !!}"></script>
        <script src="{!! url('assets/superadmin-plugins/apexcharts/apexcharts.min.js') !!}" type="text/javascript"></script>
        <script src="{!! url('assets/superadmin-js/performance.js" type="text/javascript') !!}"></script>
    @endif

    @if ($currentURL == 'SA.ContentCreators'|| $currentURL == 'SA.TagListing' ||$currentURL ==  'SA.Category')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/creators.css')!!}">
    @endif

    @if ($currentURL == 'SA.Course')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/course.css') !!}">
    @endif

    @if ($currentURL == 'SA.Students')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/students.css') !!}">
    @endif

    @if ($currentURL == 'SA.Earnings')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/earnings.css') !!}">
    @endif

    @if ($currentURL == 'SA.Products'||$currentURL == 'SA.Coupons')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/products.css') !!}">
    @endif

    @if ($currentURL == 'SA.Notifications')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/notifications.css') !!}">
    @endif

    @if ($currentURL == 'SA.ListedCourse')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/course.css') !!}">
        <script src="{!! url('assets/superadmin-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'SA.AddCourse'||$currentURL == 'SA.AddProduct' ||$currentURL == 'SA.AddCategory'||$currentURL == 'SA.EditCategory' || $currentURL == 'SA.view.course' || $currentURL == 'SA.edit.course' || $currentURL == 'SA.Edit.Products')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/course.css') !!}">
        <script src="{!! url('assets/superadmin-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'SA.AccountApprovalRequest')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/creators.css') !!}">
    @endif

    @if ($currentURL == 'SA.Addcourse2' || $currentURL == 'SA.CourseList' ||  $currentURL == 'SA.Course'||$currentURL == 'SA.AddCourse' || $currentURL == 'SA.view.course' || $currentURL == 'SA.edit.course')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/website-css/home.css') !!}">
        <script src="{!! url('assets/website-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
    @endif

    @if ($currentURL == 'SA.StudentDetail')
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-plugins/fancybox/jquery.fancybox.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! url('assets/superadmin-css/students.css') !!}">
        <script src="{!! url('assets/superadmin-plugins/fancybox/jquery.fancybox.min.js') !!}" type="text/javascript"></script>
    @endif

    <script src="{!! url('assets/superadmin-js/jquery-3.7.0.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/superadmin-plugins/bootstrap/js/bootstrap.bundle.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/superadmin-js/function.js') !!}" type="text/javascript"></script>

</head>

<body class="main-site">
    <div class="page-body-wrapper">
        @include('super-admin-layouts.partials.sidebar')
        <div class="body-wrapper">
            @include('super-admin-layouts.partials.header')
            @yield('content')
        </div>
    </div>
</body>

<script type="text/javascript">
    let arkansasUrl = "{{ url('/') }}";
    console.log("url => ", arkansasUrl);
</script>

</html>
