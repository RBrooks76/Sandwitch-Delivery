
<div class="mobile-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->
            <a class="header-brand" href="index.html">
                <img src="{{$path}}login_style/assets/img/Sandwich Map-1.png" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{$path}}login_style/assets/img/Sandwich Map-1.png" class="header-brand-img desktop-logo mobile-light" alt="logo">
            </a>
            <div class="d-flex order-lg-2 ml-auto header-right-icons">
                <button class="navbar-toggler navresponsive-toggler d-md-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4"
                        aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon fe fe-more-vertical text-white"></span>
                </button>
                <div class="dropdown profile-1">
                    <a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
										<span>
											<img src="{{$path.$user->avatar}}" alt="profile-user" class="avatar  profile-user brround cover-image">
										</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <div class="drop-heading">
                            <div class="text-center">
                                <h5 class="text-dark mb-0">Elizabeth Dyer</h5>
                                <small class="text-muted">Administrator</small>
                            </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a class="dropdown-item" href="#">
                            <i class="dropdown-icon mdi mdi-account-outline"></i> Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="dropdown-icon  mdi mdi-settings"></i> Settings
                        </a>
                        <a class="dropdown-item" href="#">
                            <span class="float-right"></span>
                            <i class="dropdown-icon mdi  mdi-message-outline"></i> Inbox
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="dropdown-icon mdi mdi-comment-check-outline"></i> Message
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="dropdown-icon mdi mdi-compass-outline"></i> Need help?
                        </a>
                        <a class="dropdown-item" href="login.html">
                            <i class="dropdown-icon mdi  mdi-logout-variant"></i> Sign out
                        </a>
                    </div>
                </div>
                <div class="dropdown d-md-flex header-settings">
                    <a href="#" class="nav-link icon " data-toggle="sidebar-right" data-target=".sidebar-right">
                        <i class="fe fe-align-right"></i>
                    </a>
                </div><!-- SIDE-MENU -->
            </div>
        </div>
    </div>
</div>
<div class="mb-1 navbar navbar-expand-lg  responsive-navbar navbar-dark d-md-none bg-white">
    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
        <div class="d-flex order-lg-2 ml-auto">
            <div class="dropdown d-sm-flex">
                <a href="#" class="nav-link icon" data-toggle="dropdown">
                    <i class="fe fe-search"></i>
                </a>
                <div class="dropdown-menu header-search dropdown-menu-left">
                    <div class="input-group w-100 p-2">
                        <input type="text" class="form-control " placeholder="Search....">
                        <div class="input-group-append ">
                            <button type="button" class="btn btn-primary ">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- SEARCH -->
            <div class="dropdown d-md-flex">
                <a class="nav-link icon full-screen-link nav-link-bg">
                    <i class="fe fe-maximize fullscreen-button"></i>
                </a>
            </div><!-- FULL-SCREEN -->
            <div class="dropdown d-md-flex">
                <a class="nav-link icon">
                    <i class="fe fe-credit-card"></i>
                </a>
            </div><!-- Credit-Card -->
            <div class="dropdown d-md-flex notifications">
                <a class="nav-link icon" data-toggle="dropdown">
                    <i class="fe fe-bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <div class="notifications-menu">

                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center">View all Notification</a>
                </div>
            </div><!-- NOTIFICATIONS -->
            <div class="dropdown d-md-flex message">
                <a class="nav-link icon text-center" data-toggle="dropdown">
                    <i class="fe fe-mail"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <div class="message-menu">
                        <a class="dropdown-item d-flex pb-3" href="#">
                            <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="assets/images/users/1.jpg"></span>
                            <div>
                                <strong>Madeleine</strong> Hey! there I am available....
                                <div class="small text-muted">
                                    3 hours ago
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex pb-3" href="#">
                            <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="assets/images/users/12.jpg"></span>
                            <div>
                                <strong>Anthony</strong> New product Launching...
                                <div class="small text-muted">
                                    5 hour ago
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex pb-3" href="#">
                            <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="assets/images/users/4.jpg"></span>
                            <div>
                                <strong>Olivia</strong> New Schedule Realease......
                                <div class="small text-muted">
                                    45 mintues ago
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex pb-3" href="#">
                            <span class="avatar avatar-md brround mr-3 align-self-center cover-image" data-image-src="assets/images/users/15.jpg"></span>
                            <div>
                                <strong>Sanderson</strong> New Schedule Realease......
                                <div class="small text-muted">
                                    2 days ago
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center">See all Messages</a>
                </div>
            </div><!-- MESSAGE-BOX -->
        </div>
    </div>
</div>