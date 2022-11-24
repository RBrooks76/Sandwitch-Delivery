<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php if ($__env->exists('dashboard.layouts.css')) echo $__env->make('dashboard.layouts.css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body class="app sidebar-mini">

<!-- The core Firebase JS SDK is always required and must be listed first -->

<!-- GLOBAL-LOADER
<div id="global-loader">
    <img src="<?php echo e($path); ?>files/dash_board/images/loader.svg" class="loader-img" alt="Loader">
</div>
 /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">

    <div class="page-main">


        <!--APP-SIDEBAR-->
    <!--<?php if($user->role == 1 || true): ?>-->
        <?php if ($__env->exists('dashboard.layouts.sidebar')) echo $__env->make('dashboard.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--<?php endif; ?>-->
    <!--/APP-SIDEBAR-->

        <!-- Mobile Header -->
    <?php if ($__env->exists('dashboard.layouts.mobile')) echo $__env->make('dashboard.layouts.mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- /Mobile Header -->

        <!--app-content open-->
        <div class="app-content">
            <div class="side-app">

                <?php if ($__env->exists('dashboard.layouts.breadcrumb')) echo $__env->make('dashboard.layouts.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if ($__env->exists('layouts.msg')) echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="col-lg-12">

                    <?php if(current_route('dashboard_admin.index') == 'active'): ?>
                        <?php echo $__env->yieldContent("content"); ?>
                    <?php elseif(current_route('dashboard_store_menu.index') == 'active'): ?>
                        <?php echo $__env->yieldContent("content"); ?>
                    <?php elseif(current_route('dashboard_store_menu.view') == 'active'): ?>
                        <?php echo $__env->yieldContent("content"); ?>
                    <?php elseif(current_route('dashboard_store_cart.view') == 'active'): ?>
                        <?php echo $__env->yieldContent("content"); ?>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-header border-bottom-0 p-4">
                                <h2 class="card-title"><?php echo $__env->yieldContent("title"); ?></h2>
                            </div>
                            <div class="e-table px-5 pb-5">
                                <div class="table-responsive table-lg">
                                    <?php if(trim($__env->yieldContent('create_btn'))): ?>
                                        <a href="<?php echo $__env->yieldContent('create_btn'); ?>" class="btn btn-primary">
                                            <?php echo $__env->yieldContent('create_btn_btn'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php echo $__env->yieldContent("content"); ?>
                    <?php endif; ?>


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
                <h5 class="modal-title"><?php echo app('translator')->get('delete.title'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo e(csrf_field()); ?>

                <input id="iddel" name="id" type="hidden">
                <p class="text-danger">
                    <?php echo app('translator')->get('delete.desc'); ?>
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
        <img src="<?php echo e($path); ?>login_style/assets/img/whatsapp_icon.png" width="56px"/>
    </a>
    <div class="light"></div>
</div>

<audio id="myAudio" style="display: none;">
    <source src="<?php echo e(path()); ?>mp3/juntos1.mp3" type="audio/ogg">
    <source src="<?php echo e(path()); ?>mp3/juntos1.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

<?php if ($__env->exists('dashboard.layouts.js')) echo $__env->make('dashboard.layouts.js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if ($__env->exists('dashboard.layouts.editor')) echo $__env->make('dashboard.layouts.editor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('js'); ?>
<script>
    /* $(function() { $('.sumernote').froalaEditor({
         // Set the image upload URL.
         imageUploadURL: '<?php echo e($setting->public); ?>upload_image.php',

        imageUploadParams: {
            id: 'my_editor'
        }
    }) });*/
</script>
<script>
    var currect_id = "<?php echo e(user()->id); ?>";
    var step_wizard = 1;
    var geturlphoto = function () {
        return "<?php echo e($setting->public); ?>";
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
<?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/layouts/app.blade.php ENDPATH**/ ?>