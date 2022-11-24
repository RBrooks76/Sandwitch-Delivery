<?php $__env->startSection('title'); ?>
    Orders
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div>
                        <form method="post" action="<?php echo e(route('dashboard_orders.export')); ?>">

                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="from" placeholder="MM/DD/YYYY" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="to" placeholder="MM/DD/YYYY" type="text">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-info" type="submit"><i class="fe fe-download mr-2"></i>
                                        Export
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <div class="filter-custom">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <select class="ajax_city form-control select2-show-search">
                                                <optgroup label="Choose City">
                                                    <option value="">All City</option>
                                                    <?php $__currentLoopData = $city; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <select class="ajax_cat form-control select2-show-search">
                                                <optgroup label="Choose Category">
                                                    <option value="">All Category</option>
                                                    <?php $__currentLoopData = $category_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table data_Table table-bordered" id="data_Table">
                                <thead>
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th>Category</th>
                                    <th>Restaurants Names</th>
                                    <th>Name Client</th>
                                    <th>City</th>
                                    <th>Phone</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Option</th>
                                    <th>View Order</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        var array = [];
        $(document).ready(function() {
            var status = getUrlParameter('status');
            var datatable = $('#data_Table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, 0],
                    [10, 25, 50, "All"]
                ],
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "<?php echo e(route('dashboard_orders.get_data')); ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d._token = "<?php echo e(csrf_token()); ?>";
                        d.cat = $('.ajax_cat').val();
                        d.city = $('.ajax_city').val();
                        d.status = status;
                        d.filter_role = $('#filter_role').val();
                    }
                },
                "columnDefs": [{
                    "targets": [0, 1, 6, 11],
                    "orderable": false
                }],
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "logo"
                    },
                    {
                        "data": "cat"
                    },
                    {
                        "data": "restaurant_r"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "city"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "total"
                    },
                    {
                        "data": "date"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "options"
                    },
                    {
                        "data": "view_order"
                    },
                ]
            });

            "use strict";
            //Code here.


            $('.btn_deleted').on("click", function() {
                var id = $('#iddel').val();
                $.ajax({
                    url: "<?php echo e(route('dashboard_orders.deleted')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function(result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        datatable.ajax.reload();
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

            $(document).on('change', '.ajax_cat', function() {
                datatable.ajax.reload();
            });

            $(document).on('change', '.ajax_city', function() {
                datatable.ajax.reload();
            });

        });

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/orders/index.blade.php ENDPATH**/ ?>