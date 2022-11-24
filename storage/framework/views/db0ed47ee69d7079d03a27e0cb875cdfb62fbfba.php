<?php $__env->startSection('title'); ?>
    Category
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_category.index')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <form class="ajaxForm category" enctype="multipart/form-data" data-name="category"
                  action="<?php echo e(route('dashboard_category.post_data')); ?>" method="post">
                <?php echo e(csrf_field()); ?>


                <input id="id" name="id" class="cls" type="hidden">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="cls form-control" name="name" id="name"
                           placeholder="Name">
                </div>

                <button type="submit" class="btn btn-primary btn-load">
                    Save Changes
                </button>
            </form>

        </div>

    </div>


<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

    <script type="text/javascript">
        $(document).ready(function () {

            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("?"),
                last_part = parts[parts.length - 2];

            var parts2 = last_part.split("/"),
                last_part2 = parts2[parts2.length - 1];

            var name_form = $('.ajaxForm').data('name');

            if (isNaN(last_part2) == false) {
                if (last_part2 != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last_part2);
                }
            } else {
                $('.title_info').html("Create New");
            }


        });

        var Render_Data = function (id) {
            $.ajax({
                url: "<?php echo e(route('dashboard_category.get_data_by_id')); ?>",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('.title').html(result.success.name);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "<?php echo e(route('dashboard_category.index')); ?>";
                    }
                }
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/category/add_edit.blade.php ENDPATH**/ ?>