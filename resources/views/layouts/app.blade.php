<!DOCTYPE html>
<html>
<head>
    @includeIf('layouts.css')
    @yield('css')
</head>
<body>


@yield('content')



@includeIf('layouts.js')
<script>
    var geturlphoto = function () {
        return "{{$setting->public}}";
    };
    $(document).ready(function () {

        $(document).on('keypress', '#serarch', function (e) {
            if (e.which == 13) {
                var val = $(this).val();
                window.location.href = geturlphoto() + "products?q=" + val;
            }
        });

        $(document).ajaxStart(function () {
            NProgress.start();
        });
        $(document).ajaxStop(function () {
            NProgress.done();
        });
        $(document).ajaxError(function () {
            NProgress.done();
        });

    });
</script>
@yield('js')

</body>

</html>
