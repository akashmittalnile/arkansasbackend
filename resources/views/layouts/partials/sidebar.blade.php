<div class="sidebar-wrapper">
    <?php
      $currentURL = Route::currentRouteName();
    ?>
    <div class="sidebar-logo">
        <a href="{{ route('home.index') }}">
            <img class="" src="{!! url('assets/website-images/logo-2.png') !!}" alt="">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
    </div>
    <div class="sidebar-nav">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                @if ($currentURL == 'home.index' || $currentURL == 'Home.Addcourse' || $currentURL == 'Home.Addcourse2' || $currentURL == 'Home.CourseList' || $currentURL == 'Home.view.course' || $currentURL == 'Home.edit.course')
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('home.index') }}">
                            <span class="menu-icon"><img src="{!! url('assets/website-images/book.svg') !!}"></span>
                            <span class="menu-title">Courses</span>
                        </a>

                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.index') }}">
                            <span class="menu-icon"><img src="{!! url('assets/website-images/book.svg') !!}"></span>
                            <span class="menu-title">Courses</span>
                        </a>

                    </li>
                @endif

                @if ($currentURL == 'Home.Performance')
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('Home.Performance') }}">
                            <span class="menu-icon"><img src="{!! url('assets/website-images/chart.svg') !!}"></span>
                            <span class="menu-title">Performance</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('Home.Performance') }}">
                            <span class="menu-icon"><img src="{!! url('assets/website-images/chart.svg') !!}"></span>
                            <span class="menu-title">Performance</span>
                        </a>
                    </li>
                @endif

                @if ($currentURL == 'Home.HelpSupport')
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('Home.HelpSupport') }}">
                            <span class="menu-icon"><img src="{!! url('assets/website-images/help.svg') !!}"></span>
                            <span class="menu-title">Help & Support</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('Home.HelpSupport') }}">
                            <span class="menu-icon"><img src="{!! url('assets/website-images/help.svg') !!}"></span>
                            <span class="menu-title">Help & Support</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item @if ($currentURL == 'Home.earnings' || $currentURL == 'Home.payment.request' || $currentURL == 'Home.order.details') active @endif">
                    <a class="nav-link" href="{{ route('Home.earnings') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/earnings.svg') !!}"></span>
                        <span class="menu-title">Earnings</span>
                    </a>
                </li>

                <li class="nav-item @if ($currentURL == 'Home.students' || $currentURL == 'Home.student.details' || $currentURL == 'Home.progress.report') active @endif">
                    <a class="nav-link" href="{{ route('Home.students') }}">
                        <span class="menu-icon"><img src="{!! url('assets/superadmin-images/students.svg') !!}"></span>
                        <span class="menu-title">Students</span>
                    </a>
                </li>
                
                

                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout.perform') }}">
                        <span class="menu-icon"><img src="{!! url('assets/website-images/logout.svg') !!}"></span>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>
            </ul>
            </ul>
        </nav>
    </div>
</div>
