<?php $__env->startSection('title'); ?>
    Setting General
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <form class="ajaxForm dashboard_setting" enctype="multipart/form-data"
          data-name="dashboard_setting"
          action="<?php echo e(route('dashboard_setting.post_data')); ?>" method="post">
        <?php echo e(csrf_field()); ?>

        <input id="id" name="id" class="cls" type="hidden">

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">General Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Site's Name</label>
                            <input type="text" class="form-control" name="name"
                                   id="name"
                                   placeholder="Name your site!">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Footer Text</label>
                            <textarea class="form-control" id="summary" rows="3"
                                      name="summary"
                                      placeholder="Enter Footer Text ..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h3 class="mb-0 card-title">Logo Header upload</h3>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" class="dropify" id="avatar" name="avatar" data-height="300"/>
                                    </div>
                                </div>
                            </div><!-- COL END -->
                            <div class="col-lg-6">
                                <img style="width: 80px;height: 80px;"
                                     class="img_usres avatar_view d-none img-thumbnail">
                            </div>
                            <div class="col-lg-6">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h3 class="mb-0 card-title">Favicon upload</h3>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" class="dropify" id="fav" name="fav" data-height="300"/>
                                    </div>
                                </div>
                            </div><!-- COL END -->
                            <div class="col-lg-6">
                                <img style="width: 80px;height: 80px;"
                                     class="img_usres fav_view d-none img-thumbnail">
                            </div>
                            <div class="col-lg-6">
                                <div class="card shadow">
                                    <div class="card-header">
                                        <h3 class="mb-0 card-title">Logo Footer upload</h3>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" class="dropify" id="avatar1" name="avatar1" data-height="300"/>
                                    </div>
                                </div>
                            </div><!-- COL END -->
                            <div class="col-lg-6">
                                <img style="width: 80px;height: 80px;"
                                     class="img_usres avatar1_view d-none img-thumbnail">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Info</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Link App AppStore</label>
                            <input type="text" class="form-control" name="google"
                                   id="google"
                                   placeholder="Enter Link App AppStore">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Link App Google Play</label>
                            <input type="text" class="form-control" name="apple"
                                   id="apple"
                                   placeholder="Enter Link App Google Play">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mobile</label>
                            <input type="text" class="form-control" name="phone"
                                   id="phone"
                                   placeholder="Enter Phone Number">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Call Center Number</label>
                            <input type="text" class="form-control" name="CallCenterNumber"
                                   id="CallCenterNumber"
                                   placeholder="Enter Phone Number">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Currency</label>
                            <input type="text" class="form-control" name="currency"
                                   id="currency"
                                   placeholder="Enter Currency">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email"
                                   id="email"
                                   placeholder="Enter E-mail">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="address"></textarea>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
            <div class="card-footer">
                <button type="submit" class="btn btn-success mt-1">Save</button>
            </div>
        </div><!--ROW END-->

    </form>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>

    <script type="text/javascript">
        $(document).ready(function () {
            "use strict";

            Render_Data();
        });

        var Render_Data = function () {
            $.ajax({
                url: "<?php echo e(route('dashboard_setting.get_data_by_id')); ?>",
                method: "get",
                data: {},
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#setting_id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('#apple').val(result.success.apple);
                        $('#google').val(result.success.google);
                        $('#summary').val(result.success.summary);
                        $('#currency').val(result.success.currency);
                        $('#phone').val(result.success.phone);
                        $('#CallCenterNumber').val(result.success.CallCenterNumber);
                        
                        $('#email').val(result.success.email);
                        $('#address').val(result.success.address);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar1_view').removeClass('d-none');
                        $('.bunner_view').removeClass('d-none');
                        $('.contact_view').removeClass('d-none');
                        $('.fav_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('.avatar1_view').attr('src', geturlphoto() + result.success.avatar1);
                        $('.bunner_view').attr('src', geturlphoto() + result.success.bunner);
                        $('.contact_view').attr('src', geturlphoto() + result.success.contact);
                        $('.fav_view').attr('src', geturlphoto() + result.success.fav);
                    }
                }
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/setting/index.blade.php ENDPATH**/ ?>