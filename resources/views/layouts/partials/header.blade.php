<div class="header-1">
    <nav class="navbar">
        <div class="navbar-menu-wrapper">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link toggle-sidebar mon-icon-bg">
                        <img src="{!! assets('assets/website-images/sidebartoggle.svg') !!}">
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item noti-dropdown dropdown">
                    <a class="nav-link  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="noti-icon">
                            <img src="{!! assets('assets/website-images/notification.svg') !!}" alt="user">
                            @if(count(getNotification()) > 0)
                            <span class="noti-badge"></span>
                            @endif
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" data-bs-popper="none">
                        <div class="notification-head">
                            <h2>Notifications</h2>
                        </div>
                        <div class="notification-body">
                            
                            @forelse(getNotification() as $val)
                            <div class="notification-item">

                                @if($val->image == "" || $val->image == null)
                                    <div class="notification-item-icon">
                                        <i class="la la-bell"></i>
                                    </div>
                                @else
                                    <img src="{{ $val->image }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; margin-right: 10px; line-height: 32px; text-align: center;" >
                                @endif
                               
                                <div class="notification-item-text">
                                    <h2>{{ $val->title ?? "NA" }}</h2>
                                    <p style="color: #e0b220;">{{ $val->message ?? "NA" }}</p>
                                    <p><span><i class="fas fa-clock"></i>{{ date('d M, Y H:i') }}</span></p>
                                </div>
                            </div>
                            @empty
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div>
                                    <img src="{{ assets('/assets/website-images/nodata.svg') }}" alt="">
                                </div>
                                <div class="font-weight-bold">
                                    <p class="font-weight-bold" style="font-size: 1.2rem;">No notifications found </p> 
                                </div>
                            </div>
                            @endforelse
                            
                        </div>
                        @if(!empty(getNotification()) && count(getNotification()) > 0)
                        <a href="{{ route('Home.clear.notification') }}" onclick="return confirm('Are you sure you want to clear all notifications?');">
                            <div class="notification-foot">
                                Clear All Notifications 
                            </div>   
                        </a> 
                        @endif
                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic">
                            @php $profile_img = auth()->user()->profile_image;  @endphp
                            @if($profile_img != "" && $profile_img != null)
                            <img src="{!! uploadAssets('upload/profile-image/'.$profile_img) !!}" alt="user">
                            @else
                            <img src="{!! assets('assets/website-images/user.jpg') !!}" alt="user">
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
