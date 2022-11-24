<?php $__env->startSection('title'); ?>
    View Client
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_orders.index')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Close <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-left">
                            <h3 class="card-title mb-0">#<?php echo e($item->name); ?></h3>
                        </div>
                        <div class="float-right">
                            <h3 class="card-title">Date: <?php echo e($item->date()); ?></h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="h3">Details Clients</p>
                            <address>
                                <strong>Name</strong> : <?php echo e($item->name); ?><br>
                                <hr>
                                <strong>Phone</strong> : <?php echo e($item->phone); ?><br>
                            </address>
                        </div>
                        <div class="col-lg-6">
                            <p class="h3">Details Restaurant:</p>
                            <address>
                                <strong>Name Restaurant</strong>
                                :
                                <?php if($item->Items->count() != 0 ): ?>
                                    <?php $__currentLoopData = $item->Items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($r->Products->Restaurant->name); ?>

                                        ,
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                <br>
                                <hr>
                                <strong>Name Food</strong> :

                                <?php if($item->Items->count() != 0 ): ?>
                                    <?php $__currentLoopData = $item->Items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($r->Products->name); ?>

                                        ,
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <br>
                                <hr>
                                <strong>Price</strong> : <?php echo e($item->total); ?><br>
                                <hr>

                                <strong>Photos</strong>: <br>
                                <div class="box-imgs">
                                    <ul id="lightgallery" class="list-unstyled row" lg-uid="lg0">


                                        <?php if($item->Items->count() != 0 ): ?>
                                            <?php $__currentLoopData = $item->Items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="col-md-2 border-bottom-0"
                                                    data-responsive="assets/images/media/12.jpg"
                                                    data-src="assets/images/media/12.jpg"
                                                    data-sub-html="<h4>Gallery Image 12</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>"
                                                    lg-event-uid="&amp;1">
                                                    <a href="">
                                                        <img class="img-responsive mb-0" src="<?php echo e($r->Products->img()); ?>"
                                                             alt="Thumb-2">
                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                            </address>
                        </div>

                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-hover mb-0 text-nowrap">
                            <tbody>
                            <tr class=" ">
                                <th>Name Order</th>
                                <th class="text-center">Description</th>
                                <th class="text-right">Quantity</th>
                            </tr>
                            <tr>

                                <?php if($item->Items->count() != 0 ): ?>
                                    <?php $__currentLoopData = $item->Items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center"><?php echo e($r->Products->name); ?></td>
                                        <td>
                                            <div class="text-muted">
                                                <div class="text-muted">
                                                    <?php echo $r->Products->summary; ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo e($r->qun); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-info mb-1" onclick="javascript:window.print();"><i
                            class="si si-printer"></i> Print
                    </button>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/clients/view_order.blade.php ENDPATH**/ ?>