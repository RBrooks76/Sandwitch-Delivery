

<?php $__env->startSection('title'); ?>
    Products
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_products.add_edit', ['id' => null, 'restaurant_id' => app('request')->input('restaurant_id')])); ?><?php $__env->stopSection(); ?>
    <?php $__env->startSection('create_btn_btn'); ?> Create new <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .active-cookies {
            width: 100%;
            text-align: center;
            color: white;
            background-color: #15be15b3;
            font-weight: bold;
            padding: 0.2rem;
            border-radius: 0.2rem;
        }

    </style>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="post" action="<?php echo e(route('dashboard_products.export')); ?>">
                            <input type="hidden" value="<?php echo e(app('request')->input('restaurant_id')); ?>" name="restaurant_id">
                            <?php echo e(csrf_field()); ?>

                            <div class="filter-custom">
                                <div class="row">
                                    <div class="col-lg-1">
                                        <button type="button" class="btn btn-sm btn-dark ajax_delete_all">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input name="from" class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input name="to" class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-info" type="submit"><i class="fe fe-download mr-2"></i>
                                            Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <a href="<?php echo e(route('dashboard_comments.index', ['id' => null, 'restaurant_id' => app('request')->input('restaurant_id')])); ?>" class="btn btn-info"
                            style="float: right">
                            <i class="fa fa-commenting-o"></i>
                            Comments
                        </a>

                        <br />

                        <div class="filter-custom">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select class="form-control ajax_cat select2-show-search" data-placeholder="Choose one (with searchbox)">
                                            <optgroup label="Choose sub">
                                                <?php if($sub_categories->count() != 0): ?>
                                                    <?php $__currentLoopData = $sub_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" id="category_id_select_2_<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table" id="data_Table">
                            <thead>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Product Info</th>
                                <th>Active</th>
                                <th>Price</th>
                                <th>Menu Category</th>
                                <th>Option</th>
                                <th>Priority</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $all_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td colspan="3">
                                            <div style="font-weight: bold;">MAIN CATEGORY</div>
                                        </td>
                                        <td colspan="2">
                                            <div class="active-cookies"><?php echo e($item->category->name); ?></div>
                                        </td>
                                        <td colspan="2">
                                            <div class="material-switch" style="margin-left: 1rem;">
                                                <input type="checkbox" class="btn_featured category_active" data-id="<?php echo e($item->category->id); ?>"
                                                    id="category_active_<?php echo e($item->category->id); ?>" <?php echo e($item->category->active ? 'checked' : ''); ?> /> <label
                                                    for="category_active_<?php echo e($item->category->id); ?>" class="label-success"></label>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control input-priority category-priority" data-id="<?php echo e($item->category->id); ?>"
                                                style="width: 75px; font-weight: bold; font-size: 1.2rem; text-align: center;" value="<?php echo e($item->category->priority); ?>" />
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $item->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <img style="width: 50px;height: 50px;" src="<?php echo e($product->avatar); ?>" class="img-circle img_data_tables" />
                                            </td>
                                            <td><?php echo e($product->name); ?></td>
                                            <td><?php echo $product->description; ?></td>
                                            <td>
                                                <div class="material-switch">
                                                    <input type="checkbox" class="btn_featured product_active" <?php echo e($product->featured ? 'checked' : ''); ?>

                                                        data-id="<?php echo e($product->id); ?>" id="product_active_<?php echo e($product->id); ?>" />
                                                    <label for="product_active_<?php echo e($product->id); ?>" class="label-success"></label>
                                                </div>
                                            </td>
                                            <td><?php echo e($product->price); ?></td>
                                            <td><?php echo $product->sub_category; ?></td>
                                            <td><?php echo $product->options; ?></td>
                                            <td><?php echo $product->priority; ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn_deleted').on("click", function() {
                var id = $('#iddel').val();
                $.ajax({
                    url: "<?php echo e(route('dashboard_products.deleted')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function(result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        window.location.reload();
                    }
                });
            });
            $(document).on('click', '.btn_delete_current', function() {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if (id) {
                    $('#data_Table tbody tr').css('background', 'transparent');
                    $('#data_Table tbody #' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });
            $(document).on('change', '.category-priority', function() {
                const priority = $(this).val();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: "<?php echo e(route('dashboard_sub_category.priority')); ?>",
                    method: "get",
                    data: {
                        id,
                        priority,
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
            $(document).on('change', '.input-priority', function(e) {
                const id = $(this).attr("data-id");
                const priority = $(this).val();
                $.ajax({
                    url: "<?php echo e(route('dashboard_products.priority')); ?>",
                    method: "get",
                    data: {
                        id,
                        priority,
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
            $(document).on('change', '.category_active', function() {
                const id = $(this).attr('data-id');
                const active = $(this).prop('checked') ? 1 : 0;
                $.ajax({
                    url: "<?php echo e(URL('dashboard/sub_category/active')); ?>" + `/${id}`,
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        active
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
            $(document).on('change', '.product_active', function() {
                const id = $(this).attr('data-id');
                const active = $(this).prop('checked') ? 1 : 0;
                $.ajax({
                    url: "<?php echo e(URL('dashboard/products/active')); ?>" + `/${id}`,
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        active
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
        });

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/products/index.blade.php ENDPATH**/ ?>