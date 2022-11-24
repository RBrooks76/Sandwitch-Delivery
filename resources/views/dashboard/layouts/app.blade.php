<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    @includeIf('dashboard.layouts.css')
    @yield('css')
</head>

<body class="app sidebar-mini">

<!-- The core Firebase JS SDK is always required and must be listed first -->

<!-- GLOBAL-LOADER
<div id="global-loader">
    <img src="{{$path}}files/dash_board/images/loader.svg" class="loader-img" alt="Loader">
</div>
 /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">

    <div class="page-main">


        <!--APP-SIDEBAR-->
    <!--@if($user->role == 1 || true)-->
        @includeIf('dashboard.layouts.sidebar')
    <!--@endif-->
    <!--/APP-SIDEBAR-->

        <!-- Mobile Header -->
    @includeIf('dashboard.layouts.mobile')
    <!-- /Mobile Header -->

        <!--app-content open-->
        <div class="app-content">
            <div class="side-app">

                @includeIf('dashboard.layouts.breadcrumb')
                @includeIf('layouts.msg')
                <div class="col-lg-12">

                    @if(current_route('dashboard_admin.index') == 'active')
                        @yield("content")
                    @elseif(current_route('dashboard_store_menu.index') == 'active')
                        @yield("content")
                    @elseif(current_route('dashboard_store_menu.view') == 'active')
                        @yield("content")
                    @elseif(current_route('dashboard_store_cart.view') == 'active')
                        @yield("content")
                    @else
                        <div class="card">
                            <div class="card-header border-bottom-0 p-4">
                                <h2 class="card-title">@yield("title")</h2>
                            </div>
                            <div class="e-table px-5 pb-5">
                                <div class="table-responsive table-lg">
                                    @if (trim($__env->yieldContent('create_btn')))
                                        <a href="@yield('create_btn')" class="btn btn-primary">
                                            @yield('create_btn_btn')
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @yield("content")
                    @endif


                </div><!-- COL-END -->


            </div>
        </div>

    </div>


    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    Copyright © 2020 <a href="#">Icons Point</a> All rights reserved.
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER END -->
</div>


<div class="modal" id="ModDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('delete.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{csrf_field()}}
                <input id="iddel" name="id" type="hidden">
                <p class="text-danger">
                    @lang('delete.desc')
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn_deleted btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- whatsapp_fixed -->
<div id="whatsapp">
    <a href="https://api.whatsapp.com/send?1=pt_BR&phone=+971501212770" target="_blank">
        <img src="{{$path}}login_style/assets/img/whatsapp_icon.png" width="56px"/>
    </a>
    <div class="light"></div>
</div>

<audio id="myAudio" style="display: none;">
    <source src="{{path()}}mp3/juntos1.mp3" type="audio/ogg">
    <source src="{{path()}}mp3/juntos1.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

@includeIf('dashboard.layouts.js')
@includeIf('dashboard.layouts.editor')
@yield('js')
<script>
    /* $(function() { $('.sumernote').froalaEditor({
         // Set the image upload URL.
         imageUploadURL: '{{$setting->public}}upload_image.php',

        imageUploadParams: {
            id: 'my_editor'
        }
    }) });*/
</script>
<script>
    var currect_id = "{{user()->id}}";
    var step_wizard = 1;
    var geturlphoto = function () {
        return "{{$setting->public}}";
    };
    var sweet_alert = function (title, text, icon, button) {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: button,
        });
    }
    $(document).ready(function () {
        "use strict";
        //Code here.
        $('.sumernote').summernote();


        $(".date").datepicker();
        $(document).on('click', '.btn_current_lan', function () {
            $('.trans').val('');
            $('.trans2').summernote('code', '');
        });

        $('.PopUp').on("click", function () {
            $('#button_action').val('insert');
            $('.form-control').val('');
            $('#id').val('');
            $('.sumernote').summernote('code', '');
            $('.avatar_view').addClass('d-none');
            $('.error').remove();
            $('.form-control').removeClass('border-danger');
        });

        $(document).on('click', '.ajaxLink', function () {
            $.ajax({
                url: $(this).data("href"),
                method: "get",
                dataType: "json",
                success: function (result) {
                    if (result.error != null) {
                        toastr.error(result.error);
                    } else {
                        toastr.success(result.success);
                        if (result.url != null) {
                            window.setTimeout(function () {
                                window.location.href = data.url;
                            }, 2000);
                        }
                    }
                }
            });
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

        $('.modal .close').on("click", function () {
            $('#data_Table tbody tr').css('background', 'transparent');
        });

        $('.modal .btn-secondary').on("click", function () {
            $('#data_Table tbody tr').css('background', 'transparent');
        });

        $('.modal .btn-default').on("click", function () {
            $('#data_Table tbody tr').css('background', 'transparent');
        });

        $(document).on('keyup', function (evt) {
            if (evt.keyCode == 27) {
                $('#data_Table tbody tr').css('background', 'transparent');
            }
        });

    });
</script>
</body>

</html>
