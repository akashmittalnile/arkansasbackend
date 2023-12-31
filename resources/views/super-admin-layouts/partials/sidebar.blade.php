<div class="sidebar-wrapper">
    <?php
    $currentURL = Route::currentRouteName();
    ?>
    <div class="sidebar-logo">
        <a href="index.html">
            <img class="" src="{!! assets('assets/superadmin-images/logo-2.png') !!}" alt="">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
    </div>
    <div class="sidebar-nav">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item @if ($currentURL == 'SA.Dashboard') active @endif">
                    <a class="nav-link" href="{{ route('SA.Dashboard') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/dashboard.svg') !!}"></span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.ContentCreators' || $currentURL == 'SA.ListedCourse' || $currentURL == 'SA.AccountApprovalRequest' || $currentURL == 'SA.Addcourse2' || $currentURL == 'SA.CourseList' || $currentURL == 'SA.Payment.Request') active @endif">
                    <a class="nav-link" href="{{ route('SA.ContentCreators') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/creators.svg') !!}"></span>
                        <span class="menu-title">Content Creators</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Content-Creator.Course' || $currentURL == 'SA.Content-Creator.Course.Chapter') active @endif">
                    <a class="nav-link" href="{{ route('SA.Content-Creator.Course') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/book.svg') !!}"></span>
                        <span class="menu-title">Creators Course</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Course' || $currentURL == 'SA.AddCourse' || $currentURL == 'SA.Course.Chapter' || $currentURL == 'SA.view.course' || $currentURL == 'SA.edit.course') active @endif">
                    <a class="nav-link" href="{{ route('SA.Course') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/book.svg') !!}"></span>
                        <span class="menu-title">Manage Course</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Students' ||$currentURL ==  'SA.StudentDetail' || $currentURL == 'SA.progress.report') active @endif">
                    <a class="nav-link" href="{{ route('SA.Students') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/students.svg') !!}"></span>
                        <span class="menu-title">Students</span>
                    </a>
                </li> 

                <li class="nav-item @if ($currentURL == 'SA.Earnings') active @endif">
                    <a class="nav-link" href="{{ route('SA.Earnings') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/earnings.svg') !!}"></span>
                        <span class="menu-title">Earnings</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Product.Orders' || $currentURL == 'SA.Product.order.details') active @endif">
                    <a class="nav-link" href="{{ route('SA.Product.Orders') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/products.svg') !!}"></span>
                        <span class="menu-title">Orders</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Category'||$currentURL == 'SA.AddCategory'||$currentURL == 'SA.EditCategory') active @endif">
                    <a class="nav-link" href="{{ route('SA.Category') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/chart.svg') !!}"></span>
                        <span class="menu-title">Manage Category</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.TagListing') active @endif">
                    <a class="nav-link" href="{{ route('SA.TagListing') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/book.svg') !!}"></span>
                        <span class="menu-title">Manage Tags</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Products' || $currentURL =='SA.AddProduct' || $currentURL == 'SA.Edit.Products' || $currentURL == 'SA.Product.View.Details') active @endif">
                    <a class="nav-link" href="{{ route('SA.Products') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/products.svg') !!}"></span>
                        <span class="menu-title">Manage Products</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Coupons') active @endif">
                    <a class="nav-link" href="{{ route('SA.Coupons') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/products.svg') !!}"></span>
                        <span class="menu-title">Manage Coupons</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Notifications' || $currentURL == 'SA.Create.Notifications') active @endif">
                    <a class="nav-link" href="{{ route('SA.Notifications') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/notification1.svg') !!}"></span>
                        <span class="menu-title">Manage Notifications</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Performance') active @endif">
                    <a class="nav-link" href="{{ route('SA.Performance') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/chart.svg') !!}"></span>
                        <span class="menu-title">Performance</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.Posts' || $currentURL == 'SA.Edit.Post' || $currentURL == 'SA.Create.Post') active @endif">
                    <a class="nav-link" href="{{ route('SA.Posts') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/chart.svg') !!}"></span>
                        <span class="menu-title">Pages</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'SA.HelpSupport') active @endif">
                    <a class="nav-link" href="{{ route('SA.HelpSupport') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/help.svg') !!}"></span>
                        <span class="menu-title">Help & Support</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('SA.logout.perform') }}">
                        <span class="menu-icon"><img src="{!! assets('assets/superadmin-images/logout.svg') !!}"></span>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
