
<footer class="wow animate__animated animate__fadeIn">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="about">
                    <img src="{{setting()->avatar1()}}" alt="{{setting()->name}}">
                    <p>{{setting()->summary}}</p>
                    <h6>Socials</h6>
                    <ul class="socials">
                        @foreach(social_media() as $r)
                            <li><a href="{{$r->link}}"><i class="{{$r->icon}}"></i></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pages">
                    <h6>Pages</h6>
                    <ul class="link-pages">
                        @foreach(posts("2") as $r)
                            <li><a href="{{$r->route()}}">{{$r->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pages">
                    <h6>Recent Episodes</h6>
                    <ul class="link-pages">
                        @foreach(posts("3") as $r)
                            <li><a href="{{$r->route()}}">{{$r->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="btn-links">
                    <h6>Listen My Podcasts Also In</h6>
                    <a href="{{setting()->apple}}" id="apple">
                        <img src="{{path()}}files/home/img/apple.png" alt="">
                        <span>Available on the</span>
                        <span>App Store</span>
                    </a>
                    <a href="{{setting()->google}}" id="google-play">
                        <img src="{{path()}}files/home/img/google_play.png" alt="">
                        <span>GET IT ON</span>
                        <span>Google Play</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="copy">
        {{date('Y')}} Icons Point &copy; All Rights Reserved
    </div>
</footer>
