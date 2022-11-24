<?php $__env->startSection('title'); ?>
    Social Media
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_social_media.add_edit')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Create new <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row row-cards row-deck">

        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 data_Table" id="data_Table<?php echo e($r->id); ?>">
                <div class="card">
                    <img class="card-img-topbr-tr-0 br-tl-0" src="<?php echo e(path()); ?>sm.jpg" alt="<?php echo e($r->name); ?>">
                    <div class="card-header">
                        <h5 class="card-title"><?php echo e($r->name); ?></h5>
                    </div>
                    <div class="card-body">
                        <a class="btn btn-sm btn-primary" href="<?php echo e(route('dashboard_social_media.add_edit',['id' => $r->id])); ?>"><i class="fa fa-edit"></i> Edit</a>
                        <a class="btn btn-sm btn-danger btn_delete_current" data-id='<?php echo e($r->id); ?>' href="#"><i class="fa fa-trash"></i> Delete</a>
                        <div class="material-switch pull-right">
                            <input id="someSwitchOptionSuccess<?php echo e($r->id); ?>" class="btn_featured" data-id="<?php echo e($r->id); ?>" type="checkbox" <?php echo e($r->active == 1 ? "checked" : ""); ?>>
                            <label for="someSwitchOptionSuccess<?php echo e($r->id); ?>" class="label-success"></label>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        var array = [];
        $(document).ready(function() {

            var datatabe ;

            "use strict";
            //Code here.

            var name_form = $('.ajaxForm').data('name');

            $(document).on('click', '.btn_delete_current', function () {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if(id){
                    $('.data_Table').css('background', 'transparent');
                    $('#data_Table' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });

            $(document).on('click', '.btn_featured', function () {
                var id = $(this).data("id");
                if (id) {
                    $('.data_Table').css('background', 'transparent');
                    $('#data_Table' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
                $.ajax({
                    url: "<?php echo e(route('dashboard_social_media.featured')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        if(result.redirect != null){
                            window.location.href = result.redirect;
                        }
                    }
                });
            });

            $('.btn_deleted').on("click",function () {
                var id = $('#iddel').val();
                $.ajax({
                    url:"<?php echo e(route('dashboard_social_media.deleted')); ?>",
                    method:"get",
                    data : {
                        "id" : id,
                    },
                    dataType:"json",
                    success:function(result)
                    {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        if(result.redirect != null){
                            window.location.href = result.redirect;
                        }
                    }
                });
            });

            $(document).on('click', '.ajax_delete_all', function () {
                $.ajax({
                    url: "<?php echo e(route('dashboard_social_media.deleted_all')); ?>",
                    method: "get",
                    data: {
                        "array": array,
                    },
                    dataType: "json",
                    success: function (result) {
                        if(result.error != null){
                            toastr.error(result.error);
                        }
                        else{
                            toastr.success(result.success);
                            if(result.redirect != null){
                                window.location.href = result.redirect;
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.btn_select_all', function () {
                array = [];
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                $('.btn_select_btn_deleted').each(function (index, value) {
                    var id = $(value).data("id");
                    var status = $(value).prop("checked");
                    if(status == true){
                        array.push(id);
                    }
                    else{
                        var index2 = array.indexOf(id);
                        if (index2 > -1) {
                            array.splice(index2, 1);
                        }
                    }
                });
            });

            $(document).on('click', '.btn_select_btn_deleted', function () {
                var id = $(this).data("id");
                var status = $(this).prop("checked");
                var numberOfChecked = $('input:checkbox:checked').length;
                var numberOftext = $('.btn_select_btn_deleted').length;
                if(status == true){
                    array.push(id);
                }
                else{
                    var index = array.indexOf(id);
                    if (index > -1) {
                        array.splice(index, 1);
                    }
                }
                if(numberOftext != array.length){
                    $(".btn_select_all").prop('checked',false);
                }
                if(numberOftext == array.length){
                    $(".btn_select_all").prop('checked',$(this).prop('checked'));
                }
            });

        });

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/social_media/index.blade.php ENDPATH**/ ?>