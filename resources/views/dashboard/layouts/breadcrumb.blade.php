<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">@yield('title')</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
        </ol>
    </div>
    <div class="d-flex header-right-icons header-search-icon">
        <div class="dropdown d-sm-flex">
            <!--
            <a href="javascript:void(0)" onclick="introJs().start();" class="nav-link icon">
                <i class="fa fa-support"></i>
            </a>-->
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

        <div class="dropdown d-sm-flex">
          <ul class="nav navbar-nav">
            <li class="dropdown dropdown-notifications">
                <a class="nav-link icon" data-toggle="dropdown">
                    <i class="fe fe-bell" data-count = "0"></i>
                    <span class="notif-count-b">0</span>
                </a>
            <ul class="dropdown-menu" style="width:290px; padding: 15px 15px 0 15px;">
              <div class="dropdown-container">
                <div class="dropdown-toolbar">
                  <div class="dropdown-toolbar-actions">
                    <a href="#">Mark all as read</a>
                  </div>
                  <h5 class="dropdown-toolbar-title">Notifications (<span class="notif-count">0</span>)</h5>
                </div>
                
                <ul class="notilist">
                    
                </ul>
                
                <div class="dropdown-footer text-center">
                  <a href="#">View All Notifications</a>
                </div>
              </div>
              </ul>
            </li>
          </ul>
        </div>
      
        
        <div class="dropdown profile-1">
         
            <a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none">
    			<span>
    				<img src="{{$path.$user->avatar}}" alt="{{$user->name}}" class="avatar  profile-user brround cover-image" width="35">
    			</span>
    		
            </a>
           
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <div class="drop-heading">
                    <div class="text-center">
                        <h5 class="text-dark mb-0">{{$user->name}}</h5>
                        <small class="text-muted">{{$user->email}}</small>
                    </div>
                </div>
                <div class="dropdown-divider m-0"></div>
                <a href="{{route('dashboard_users.index')}}" class="dropdown-item">
                    <i class="fe fe-users"></i>
                    Users
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
						  document.getElementById('logout-form').submit();">
                    <i class="dropdown-icon mdi  mdi-logout-variant"></i> LogOff
                </a>
                <form id="logout-form" action="{{ route('logout') }}"
                      method="POST"
                      style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <div class="dropdown d-md-flex header-settings">
            <a href="#" class="nav-link icon " data-toggle="sidebar-right" data-target=".sidebar-right">
                <i class="fe fe-align-right"></i>
            </a>
        </div><!-- SIDE-MENU -->
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//js.pusher.com/3.1/pusher.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
    .notif-count-b{
        margin-top: -16px;
        margin-left: -10px;
        background: red;
        height: 17px;
        width: 18px;
        color: #fff;
        font-weight: 900;
        font-family: sans-serif;
        font-size: 12px;
        border-radius: 9px;
    }
</style>
<script type="text/javascript">
  var notificationsWrapper   = $('.dropdown-notifications');
  
  var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
  var notificationsCountElem = notificationsToggle.find('i[data-count]');
  
  var notificationsCount     = parseInt(notificationsCountElem.data('count'));
  var notifications          = notificationsWrapper.find('ul.notilist');

  
//   if (notificationsCount <= 0) {
//     notificationsWrapper.hide();
//   }

  // Enable pusher logging - don't include this in production
  // Pusher.logToConsole = true;

  var pusher = new Pusher('97edbb16c72cc6cddabb', {
    encrypted: true
  });

  // Subscribe to the channel we specified in our Laravel Event
  var channel = pusher.subscribe('status-liked');
  // Audio Define
  function playSound() { 

        /* Audio link for notification */ 
        var audio = new Audio("http://sandwichmap-control.me/public/mp3/juntos.mp3"); 
        audio.play(); 
} 
  // Bind a function to a Event (the full Laravel class)
  channel.bind('App\\Events\\StatusLiked', function(data) {
    // console.log("----------HI----------", data);
    var existingNotifications = notifications.html();
    
    var newNotificationHtml = `
      <li class="notification active">
          <div class="media">
            <div class="media-body">
              <strong class="notification-title">`+data.message+`</strong>
              
              <div class="notification-meta">
                <small class="timestamp">about a minute ago</small>
              </div>
            </div>
          </div>
      </li>
    `;
    playSound();
    notifications.html(newNotificationHtml + existingNotifications);
    
    notificationsCount += 1;
   
    notificationsCountElem.attr('data-count', notificationsCount);
    notificationsWrapper.find('.notif-count').text(notificationsCount);
    $(".notif-count-b").text(notificationsCount)
    
    if(notificationsCount < 1){
          $(".notif-count-b").css("display","none");
          console.log("is 0", notificationsCount);
      }else{
          $(".notif-count-b").css("display","block");
          console.log("noti count", notificationsCount);
      }
    
    notificationsWrapper.show();
  });
      if(notificationsCount < 1){
          $(".notif-count-b").css("display","none");
          console.log("is 0", notificationsCount);
      }else{
          $(".notif-count-b").css("display","block");
          console.log("noti count", notificationsCount);
      }
</script>
<!-- PAGE-HEADER END -->
