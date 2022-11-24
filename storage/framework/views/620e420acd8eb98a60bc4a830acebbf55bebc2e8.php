<?php $__env->startSection('title'); ?>
    Driver
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_city.index')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <form class="ajaxForm city" enctype="multipart/form-data" data-name="city" action="<?php echo e(URL('dashboard/driver/post_data')); ?>" method="post">
                <?php echo e(csrf_field()); ?>


                <input id="id" name="id" class="cls" type="hidden">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="cls form-control" name="name" id="name" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" class="cls form-control" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Email</label>
                    <input type="text" class="cls form-control" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Mobile</label>
                    <input type="text" class="cls form-control" name="mobile" id="mobile" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Password</label>
                    <input type="text" class="cls form-control" name="password" id="password" required>
                </div>
                
                <div class="form-group">
                    <label for="name">City</label>
                    <select class="cls form-control" name="city" id="city" required>
                        <option value="">Select</option>
                        <?php $__currentLoopData = $CityList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $CL): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($CL->id); ?>"><?php echo e($CL->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
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
                url: "<?php echo e(URL('dashboard/driver/get_data_by_id')); ?>",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('#email').val(result.success.email);
                        $('#mobile').val(result.success.mobile);
                        $('#password').val(result.success.password);
                        $('#username').val(result.success.username);
                        $('#city').val(result.success.city);
                        $('.title').html(result.success.code);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "<?php echo e(URL('dashboard/driver')); ?>";
                    }
                }
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/Drivers/add_edit.blade.php ENDPATH**/ ?>