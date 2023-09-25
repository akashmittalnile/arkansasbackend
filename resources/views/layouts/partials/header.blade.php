<div class="header-1">
    <nav class="navbar">
        <div class="navbar-menu-wrapper">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link toggle-sidebar mon-icon-bg">
                        <img src="{!! url('assets/website-images/sidebartoggle.svg') !!}">
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item noti-dropdown dropdown">
                    <a class="nav-link  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="noti-icon">
                            <img src="{!! url('assets/website-images/notification.svg') !!}" alt="user">
                            <span class="noti-badge"></span>
                        </div>
                    </a>

                    <div class="dropdown-menu">

                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic"><img src="{!! url('assets/website-images/user.jpg') !!}" alt="user"> </div>
                    </a>

                    <div class="dropdown-menu">
                        <a href="{{ route('Home.my.account') }}" class="dropdown-item">
                            <i class="las la-user"></i> Profile
                        </a>
                        <a href="{{ route('logout.perform') }}" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>

            </ul>

            </ul>
        </div>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </nav>
</div>
