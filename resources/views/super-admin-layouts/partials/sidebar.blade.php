<div class="sidebar-wrapper">
    <?php
    $currentURL = Route::currentRouteName();
    ?>
    <div class="sidebar-logo">
        <a href="index.html">
            <img class="" src="{!! url('assets/superadmin-images/logo-2.png') !!}" alt="">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
    </div>
    <div class="sidebar-nav">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item @if ($currentURL == 'SA.Dashboard') active @endif">
                    <a class="nav-link" href="{{ route('SA.Dashboard') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/dashboard.svg') !!}"></span>
                        <span class="menu-title">Dashboard</span>
                    </a>

                </li>
                <li class="nav-item @if ($currentURL == 'SA.ContentCreators' || $currentURL == 'SA.ListedCourse' || $currentURL == 'SA.AccountApprovalRequest' || $currentURL == 'SA.Addcourse2' || $currentURL == 'SA.CourseList') active @endif">
                    <a class="nav-link" href="{{ route('SA.ContentCreators') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/creators.svg') !!}"></span>
                        <span class="menu-title">Content Creators</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Course' || $currentURL == 'SA.AddCourse' || $currentURL == 'SA.Course.Chapter' ) active @endif">
                    <a class="nav-link" href="{{ route('SA.Course') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/book.svg') !!}"></span>
                        <span class="menu-title">Manage Course</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Students' ||$currentURL ==  'SA.StudentDetail') active @endif">
                    <a class="nav-link" href="{{ route('SA.Students') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/students.svg') !!}"></span>
                        <span class="menu-title">Students</span>
                    </a>
                </li> 

                <li class="nav-item @if ($currentURL == 'SA.Earnings') active @endif">
                    <a class="nav-link" href="{{ route('SA.Earnings') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/earnings.svg') !!}"></span>
                        <span class="menu-title">Earnings</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Category'||$currentURL == 'SA.AddCategory'||$currentURL == 'SA.EditCategory') active @endif">
                    <a class="nav-link" href="{{ route('SA.Category') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/chart.svg') !!}"></span>
                        <span class="menu-title">Manage Category</span>
                    </a>
                </li>
                <li class="nav-item @if ($currentURL == 'SA.TagListing') active @endif">
                    <a class="nav-link" href="{{ route('SA.TagListing') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/book.svg') !!}"></span>
                        <span class="menu-title">Manage Tags</span>
                    </a>
                </li>
                <li class="nav-item @if ($currentURL == 'SA.Products' || $currentURL =='SA.AddProduct') active @endif">
                    <a class="nav-link" href="{{ route('SA.Products') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/products.svg') !!}"></span>
                        <span class="menu-title">Manage Products</span>
                    </a>
                </li>


                <li class="nav-item @if ($currentURL == 'SA.Notifications') active @endif">
                    <a class="nav-link" href="{{ route('SA.Notifications') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/notification1.svg') !!}"></span>
                        <span class="menu-title">Manage Notifications</span>
                    </a>
                </li>
                <li class="nav-item @if ($currentURL == 'SA.Performance') active @endif">
                    <a class="nav-link" href="{{ route('SA.Performance') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/chart.svg') !!}"></span>
                        <span class="menu-title">Performance</span>
                    </a>
                </li>
                <li class="nav-item @if ($currentURL == 'SA.HelpSupport') active @endif">
                    <a class="nav-link" href="{{ route('SA.HelpSupport') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/help.svg') !!}"></span>
                        <span class="menu-title">Help & Support</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout.perform') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/logout.svg') !!}"></span>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
