<header>
    <div class="top-bar">
        <div class="container">
            <div class="logo">
                <a href="{{route('index')}}"><img src="{{setting()->avatar()}}" alt="{{setting()->name}}"></a>
            </div>
            <div class="menu-bar">
                <strong>X</strong>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul class="menu">
                <li class="active"><a href="{{route('index')}}">Home</a></li>
            </ul>

            <ul class="menu-mobile">
                <li class="close">X</li>
                <li class="active"><a href="{{route('index')}}">Home</a></li>
            </ul>

            <div class="button-top">
                @if(user())
                    <a href="{{user()->route()}}">
                        {{user()->name}}
                    </a>
                    <a href="{{route('log_out')}}">
                        Logout
                    </a>
                @else
                    <a href="#" id="login">Login</a>
                @endif
            </div>
        </div>
    </div>
    <!-- End Top Bar -->
</header>
