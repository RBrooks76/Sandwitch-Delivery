<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('index') }}">
            <img src="{{ url($setting->avatar) }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ url($setting->avatar) }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ url($setting->avatar) }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ url($setting->avatar) }}" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
        <a aria-label="Hide Sidebar" class="app-sidebar__toggle ml-auto" data-toggle="sidebar" href="#"></a>
        <!-- sidebar-toggle-->
    </div>
    <div class="app-sidebar__user">
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                <img src="{{ $path . $user->avatar }}" alt="{{ $user->name }}" class="avatar-xl rounded-circle">
            </div>
            <div class="user-info">
                <h6 class=" mb-0 text-dark">{{ $user->name }}</h6>
                <span class="text-muted app-sidebar__user-name text-sm">{{ $user->email }}</span>
            </div>
        </div>
    </div>
    <div class="sidebar-navs">
        <ul class="nav  nav-pills-circle">
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Home">
                <a href="{{ route('dashboard_admin.index') }}" target="_blank" class="nav-link text-center m-2">
                    <i class="fe fe-navigation"></i>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Users">
                <a href="{{ route('dashboard_users.index') }}" class="nav-link text-center m-2">
                    <i class="fe fe-users"></i>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Home Page">
                <a href="{{ route('index') }}" class="nav-link text-center m-2">
                    <i class="fa fa-server"></i>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="LogOff">
                <a class="nav-link text-center m-2" href="{{ route('logout') }}" onclick="event.preventDefault();
                                  document.getElementById('logout-form2').submit();">
                    <i class="fe fe-power"></i>
                </a>
                <form id="logout-form2" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    <ul class="side-menu">
        <li>
            <h3>Dashboard</h3>
        </li>
        <li class="slide" data-step="1" data-intro="This is the first feature">
            <a class="side-menu__item" href="{{ route('dashboard_admin.index') }}"><i class="side-menu__icon ti-home"></i><span class="side-menu__label">
                    Dashboard</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon mdi mdi-cart"></i>
                <span class="side-menu__label">Orders</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('dashboard_orders.index') }}" class="slide-item"> <i class="side-menu__icon ion-grid"></i>All </a></li>
                <li><a href="{{ route('dashboard_orders.index', ['status' => 1]) }}" class="slide-item"> <i class="side-menu__icon ion-grid"></i>Pending </a></li>
                <li><a href="{{ route('dashboard_orders.index', ['status' => 5]) }}" class="slide-item"> <i class="side-menu__icon ion-grid"></i>Accepted </a></li>
                <li><a href="{{ route('dashboard_orders.index', ['status' => 3]) }}" class="slide-item"> <i class="side-menu__icon ion-grid"></i>Rejected </a></li>
            </ul>
        </li>
        
        <li>
            <a class="side-menu__item" href="{{ URL('dashboard/push-notification') }}">
                <i class="side-menu__icon fe fe-trending-down"></i>
                <span class="side-menu__label">Push Notification</span>
            </a>
        </li>
        @if (Auth::user()->role == '1')
        <li>
            <a class="side-menu__item" href="{{ route('dashboard_offers.index') }}">
                <i class="side-menu__icon fe fe-trending-down"></i>
                <span class="side-menu__label">Offers</span>
            </a>
        </li>
        @endif
        <li>
            <a class="side-menu__item" href="{{ route('dashboard_clients.index') }}">
                <i class="side-menu__icon fe fe-users"></i>
                <span class="side-menu__label">Clients</span>
            </a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('dashboard_restaurant.index') }}">
                <i class="side-menu__icon fa fa-cutlery"></i>
                <span class="side-menu__label">Restaurant</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('dashboard_riders.index') }}">
                <i class="side-menu__icon fa fa-car"></i>
                <span class="side-menu__label">Rider</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('dashboard_ownrestaurant.index') }}">
                <i class="side-menu__icon fa fa-cutlery"></i>
                <span class="side-menu__label">Register your restaurant</span></a>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon mdi mdi-tune"></i>
                <span class="side-menu__label">Control Site</span>
                <i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('dashboard_join_us.index') }}" class="slide-item"><i class="side-menu__icon fa fa-home"></i>Join US</a></li>
                <li><a href="{{ route('dashboard_video.index') }}" class="slide-item"><i class="side-menu__icon fa fa-home"></i>Video</a></li>
                <li><a href="{{ route('dashboard_posts.index') }}" class="slide-item"><i class="side-menu__icon fa fa-file"></i>Pages</a></li>
                <li><a href="{{ route('dashboard_slider.index') }}" class="slide-item"><i class="side-menu__icon fe fe-align-center"></i>Slider</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon mdi mdi-tune"></i>
                <span class="side-menu__label">General</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ route('dashboard_category.index') }}" class="slide-item"><i class="side-menu__icon fa fa-th-large"></i>Categories</a></li>
                <li><a href="{{ route('dashboard_sub_category.index') }}" class="slide-item"><i class="side-menu__icon fa fa-th-large"></i>Sub Category</a></li>
                <li><a href="{{ route('dashboard_city.index') }}" class="slide-item"><i class="side-menu__icon fa fa-flag"></i>Cities</a></li>
                <li><a href="{{ route('dashboard_social_media.index') }}" class="slide-item"> <i class="side-menu__icon fe fe-twitter"></i>Social Media</a></li>
                <li><a href="{{ route('dashboard_setting.index') }}" class="slide-item"><i class="side-menu__icon fe fe-align-center"></i>General</a></li>
            </ul>
        </li>
        @if (Auth::user()->role == '1')
            <li class="slide" data-step="1" data-intro="This is the first feature">
                <a class="side-menu__item" href="{{ route('dashboard_users.index', ['type' => '1']) }}"><i class="side-menu__icon fa fa-user"></i><span class="side-menu__label">
                        Admins</span>
                </a>
            </li>
            
            <li>
                <a class="side-menu__item" href="{{ URL('dashboard/Adverts') }}">
                    <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                    <span class="side-menu__label">Advert Rquest</span>
                </a>
            </li>
            
            <li>
                <a class="side-menu__item" href="{{ URL('dashboard/Crew') }}">
                    <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                    <span class="side-menu__label">Crew</span>
                </a>
            </li>
            
            
            <li>
                <a class="side-menu__item" href="{{ URL('dashboard/coupons') }}">
                    <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                    <span class="side-menu__label">Discount Coupon</span>
                </a>
            </li>
            
            <li>
                <a class="side-menu__item" href="{{ URL('dashboard/driver') }}">
                    <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                    <span class="side-menu__label">Driver</span>
                </a>
            </li>
            
            <li>
                <a class="side-menu__item" href="{{ URL('dashboard/driver/rating') }}">
                    <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                    <span class="side-menu__label">Driver Rating</span>
                </a>
            </li>
            
            <li>
                <a class="side-menu__item" href="{{ URL('dashboard/driver/complaint') }}">
                    <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                    <span class="side-menu__label">Driver Complaint</span>
                </a>
            </li>
            
        @endif
        <li>
            <a class="side-menu__item" href="{{ route('dashboard_contact_us.index') }}">
                <i class="side-menu__icon side-menu__icon fe fe-mail"></i>
                <span class="side-menu__label">Contact</span></a>
        </li>
        
        
        <li class="slide">
            <a class="side-menu__item" href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form2').submit();">
                <i class="side-menu__icon ti-lock"></i><span class="side-menu__label">
                    Sign Out</span>
            </a>
            <form id="logout-form2" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>
