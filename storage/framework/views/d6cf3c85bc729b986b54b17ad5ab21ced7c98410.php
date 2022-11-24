<?php $__env->startSection('title'); ?>
    Coupon
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_city.index')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <form class="ajaxForm city" enctype="multipart/form-data" data-name="city" action="<?php echo e(URL('dashboard/coupons/post_data')); ?>" method="post">
                <?php echo e(csrf_field()); ?>


                <input id="id" name="id" class="cls" type="hidden">

                <div class="form-group">
                    <label for="name">Code</label>
                    <input type="text" class="cls form-control" name="code" id="code" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Amount</label>
                    <input type="number" class="cls form-control" name="amount" id="amount" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Expiry Date</label>
                    <input type="date" class="cls form-control" name="expiry_date" id="expiry_date" required>
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
            var url = $(location).attr('href'),
                parts = url.split("?"),
                last_part = parts[parts.length - 2];

            var parts2 = last_part.split("/"), last_part2 = parts2[parts2.length - 1];
            

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
                url: "<?php echo e(URL('dashboard/coupons/get_data_by_id')); ?>",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#code').val(result.success.code);
                        $('#code').attr("readonly", true);
                        $('#amount').val(result.success.amount);
                        $('#expiry_date').val(result.success.expiry_date);
                        $('.title').html(result.success.code);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "<?php echo e(route('dashboard_city.index')); ?>";
                    }
                }
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/coupons/add_edit.blade.php ENDPATH**/ ?>