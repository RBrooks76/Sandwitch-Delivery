<?php $__env->startSection('title'); ?>
    <?php if(app('request')->input('type') == 1): ?>
        Admin
    <?php elseif(app('request')->input('type') == 2): ?>
        Clients
    <?php elseif(app('request')->input('type') == 3): ?>
        Rider
    <?php elseif(app('request')->input('type') == 4): ?>
        Restaurants
    <?php else: ?>
        User
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_users.index',['type'=>app('request')->input('type')])); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <h4 class="mb-2 mb-sm-0 pt-1">
                <?php echo $__env->yieldContent("title"); ?>
            </h4>
            <hr>
            <form class="ajaxForm users" enctype="multipart/form-data" data-name="users"
                  action="<?php echo e(route('dashboard_users.post_data')); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <div class="modal-header">
                    <h5 class="modal-title title_info">

                    </h5>
                    <div class="stud"></div>
                </div>
                <div class="modal-body">
                    <input id="id" name="id" class="cls" type="hidden">
                    <input id="role" value="<?php echo e(app('request')->input('type')); ?>" name="role" class="cls" type="hidden">
                    <input id="type" value="<?php echo e(app('request')->input('type')); ?>" name="type" class="cls" type="hidden">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="cls form-control" name="name" id="name"
                               placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="cls form-control" name="email" id="email"
                               placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="cls form-control" name="phone" id="phone"
                               placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avaar</label>
                        <input type="file" class="cls form-control" name="avatar" id="avatar">
                        <br>
                        <img class="img_usres avatar_view d-none img-thumbnail">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="cls form-control" name="password" id="password"
                               placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="text" class="cls form-control" name="password_confirmation"
                               id="password_confirmation" placeholder="Confirm Password">
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <a href="<?php echo e(route('dashboard_users.index',['type'=>app('request')->input('type')])); ?>"
                       class="btn btn-default">
                        Close
                    </a>
                    <button type="submit" class="btn btn-primary btn-load">
                       Save Changes
                    </button>
                </div>
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
                $('.title_info').html("Close");
            }


        });

        var Render_Data = function (id) {
            $.ajax({
                url: "<?php echo e(route('dashboard_users.get_data_by_id')); ?>",
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
                        $('#phone').val(result.success.phone);
                        $('#role').val(result.success.role);
                        $('#email').val(result.success.email);
                        $('#password').val(result.success.show_password);
                        $('#password_confirmation').val(result.success.show_password);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');

                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "<?php echo e(route('dashboard_users.index')); ?>";
                    }
                }
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/users/add_edit.blade.php ENDPATH**/ ?>