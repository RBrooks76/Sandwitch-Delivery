<?php $__env->startSection('title'); ?>
    Send Push Notification
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <form class="ajaxForm send_email_send" enctype="multipart/form-data" data-name="send_email_send"
                              action="<?php echo e(URL('dashboard/SendPushNoticitaionUser')); ?>" method="post">
                            <?php echo e(csrf_field()); ?>

                            <div class="modal-header">
                                <h5 class="modal-title title_info"></h5>
                            </div>
                            <div class="modal-body row">
                                <div class="form-group col-md-12">
                                    <input type="radio" checked name="AppType" value="SandwichMap"> <label for="summary">Sandwich Map</label>
                                    <input type="radio" name="AppType" value="SandwichMenu"> <label for="summary">Sandwich Menu</label>
                                    
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label for="summary">Title</label>
                                    <input class="form-control" name="title" required>
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label for="summary">Summary</label>
                                    <textarea rows="4" class="form-control" required name="notification"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-load">
                                   Send Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/push_notification.blade.php ENDPATH**/ ?>