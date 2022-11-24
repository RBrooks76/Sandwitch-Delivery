<header>
    <div class="box-login">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h4>Sign In</h4>
            @includeIf("layouts.msg")
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Your Email Address">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Your password">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck">
                            <label class="form-check-label" for="invalidCheck">
                                Keep me logged in
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="{{ route('password.request') }}">Forget Password?</a>
                    </div>
                </div>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    <!-- Start Top Bar -->
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
                @foreach(posts("1") as $r)
                    <li><a href="{{$r->route()}}">{{$r->name}}</a></li>
                @endforeach
            </ul>

            <ul class="menu-mobile">
                <li class="close">X</li>
                <li class="active"><a href="{{route('index')}}">Home</a></li>
                @foreach(posts("1") as $r)
                    <li><a href="{{$r->route()}}">{{$r->name}}</a></li>
                @endforeach
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
