<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="author" content=" {{ $setting->name }} ">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $setting->name }}-@yield('title')
</title>
@yield("seo")
<meta charset="utf-8">
<link rel="shortcut icon" href="{{ url($setting->fav) }}">

<link rel="stylesheet" href="{{ $path }}files/home/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ $path }}files/home/css/line-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="{{ $path }}files/home/css/nicepage.css" media="screen">
<link rel="stylesheet" href="{{ $path }}files/home/css/Page-1.css" media="screen">
<link rel="stylesheet" href="{{ $path }}files/home/css/style.css">
<link rel="stylesheet" href="{{ $path }}files/home/css/responsive.css">
<link rel="icon" type="image/png" href="{{ $path }}files/home/img/favicon.png">

<!-- Your custom styles (optional) -->
<link rel="stylesheet" href="{{ $path }}css/toastr.min.css">
<link rel="stylesheet" href="{{ $path }}nprogress-master/nprogress.css" />
<style>
    .toast,
    #toast-container {
        z-index: 9999999999999999;
    }

</style>
