<?php $__env->startSection('title'); ?>
    <?php echo e($item->Restaurant->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_comments.index',['id'=>null,'restaurant_id'=>$item->restaurant_id])); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="h3">Details Comment</p>
                            <address>
                                <strong>Name Customer</strong> :     <?php echo e($item->Restaurant->name); ?>

                                <br>
                                <hr>
                                <strong>Phone</strong> :     <?php echo e($item->Restaurant->phone); ?>

                                <br>
                                <hr>
                                <strong>Date</strong> : <?php echo e($item->date()); ?><br>
                                <hr>
                                <strong>Comment Content</strong>:<br><br>
                                <?php echo $item->comment; ?>

                            </address>
                        </div>


                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/comments/view.blade.php ENDPATH**/ ?>