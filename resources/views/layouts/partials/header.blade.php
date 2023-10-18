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

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" data-bs-popper="none">
                        <div class="notification-head">
                            <h2>Notifications</h2>
                        </div>
                        <div class="notification-body">
                            
                            <a href=""  target="_blank"><div class="notification-item">
                                <div class="notification-item-icon"><i class="la la-bell"></i></div>
                                <div class="notification-item-text">
                                    <h2>Dishant registerd</h2>
                                    <p><span><i class="fas fa-clock"></i>13-10-2023</span></p>
                                </div>
                            </div></a>
                            
                            <div class="notification-item">
                                <div class="notification-item-icon"><i class="la la-bell"></i></div>
                                <div class="notification-item-text">
                                    <h2>No new notification yet</h2>
                                </div>
                            </div>
                            
                        </div>
                        <a href=""  target="_blank">
                            <div class="notification-foot">
                                Clear All Notifications 
                            </div>   
                        </a> 
                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic">
                            @php $profile_img = auth()->user()->profile_image;  @endphp
                            @if($profile_img != "" && $profile_img != null)
                            <img src="{!! url('upload/profile-image/'.$profile_img) !!}" alt="user">
                            @else
                            <img src="{!! url('assets/website-images/user.jpg') !!}" alt="user">
                            @endif
                        </div>
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
