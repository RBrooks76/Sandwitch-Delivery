

<?php $__env->startSection('title'); ?>
    Dashboard
<?php $__env->stopSection(); ?>

<?php
$selctor = 'ltr';
if (app()->getLocale() == 'ar') {
    $selctor = 'rtl';
}
?>

<?php $__env->startSection('css'); ?>
    <!--C3.JS CHARTS CSS -->
    <link href="<?php echo e($path); ?>files/dash_board/<?php echo e($selctor); ?>/plugins/charts-c3/c3-chart.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.2.1/dist/chart.min.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row" style="padding-top: 20px;">
        <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="card bg-success img-card box-success-shadow x-box-cards">
                <div class="card-body card-taps">
                    <a href="<?php echo e(route('dashboard_restaurant.index')); ?>"></a>
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"><?php echo e($rest); ?></h2>
                            <p class="mb-0 number-font custom-number">Total Restaurant</p>
                            <div class="progress h-2" style="margin-top:20px">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo e(($Restaurants / 10000) * 100); ?>%!important;background-color: #24dcc2"></div>
                            </div>
                            <span class="d-inline-block" style="color: #FFF">Target: 10000</span>
                        </div>
                        <div class="ml-auto custom-icon"><i class="fa fa-cutlery text-white fs-30 mr-2 mt-2" style="transform: translateY(32px);font-size: 24px !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->

        <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="card bg-secondary img-card box-secondary-shadow x-box-cards">
                <div class="card-body card-taps">
                    <a href="<?php echo e(route('dashboard_restaurant.index')); ?>"></a>
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"><?php echo e($pro); ?></h2>
                            <p class="mb-0 number-font custom-number">Total Products</p>
                            <div class="progress h-2" style="margin-top:20px">
                                <div class="progress-bar" role="progressbar" style="width:<?php echo e(($pro / 10000) * 100); ?>%!important;background-color: #24dcc2"></div>
                            </div>
                            <span class="d-inline-block" style="color: #FFF">Target: 10000</span>
                        </div>
                        <div class="ml-auto custom-icon"><i class="fa fa-shopping-bag text-white fs-30 mr-2 mt-2" style="transform: translateY(32px);font-size: 24px !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->

        <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="card bg-info img-card box-info-shadow x-box-cards">
                <div class="card-body card-taps">
                    <a href="<?php echo e(route('dashboard_clients.index')); ?>"></a>
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"><?php echo e($users); ?></h2>
                            <p class="mb-0 number-font custom-number">Total Users</p>
                            <div class="progress h-2" style="margin-top:20px">
                                <div class="progress-bar w-50" role="progressbar" style="width:<?php echo e(($users / 10000) * 100); ?>%!important;background-color: #24dcc2"></div>
                            </div>
                            <span class="d-inline-block" style="color: #FFF">Target: 10000</span>
                        </div>
                        <div class="ml-auto custom-icon"><i class="fe fe-users text-white fs-30 mr-2 mt-2"
                                style="transform: translateY(32px);font-size: 24px !important;display: block"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->

        <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="card bg-primary img-card box-primary-shadow x-box-cards">
                <div class="card-body card-taps">
                    <a href="<?php echo e(route('dashboard_orders.index')); ?>"></a>
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"><?php echo e($orrder); ?></h2>
                            <p class="mb-0 number-font custom-number">Total Sales</p>
                            <div class="progress  " style="margin-top:20px">
                                <div class="progress-bar" role="progressbar" style="width:<?php echo e(($orrder / 100000) * 100); ?>%!important;background-color: #24dcc2"></div>
                            </div>
                            <span class="d-inline-block" style="color: #FFF">Target: 100000</span>
                        </div>
                        <div class="ml-auto custom-icon"><i class="fa fa-dollar text-white fs-30 mr-2 mt-2" style="transform: translateY(32px);font-size: 24px !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
    </div>

    <div class="row x-hand">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <a href="<?php echo e(route('dashboard_orders.index')); ?>" style="text-decoration: none;color: #000">
                                <h6 class="">All Orders</h6>
                            </a>
                            <h3 class="mb-2 number-font"><?php echo e($ordersCount->all); ?></h3>
                            <p class="text-muted">
                                <span class="text-success"><i class="fa fa-chevron-circle-up text-success ml-1"></i> <?php echo e($orrder); ?>%</span>
                                last month
                            </p>
                            <div class="progress h-2">
                                <div class="progress-bar bg-success" style="width:<?php echo e($orrder_this_month); ?>%!important;" role="progressbar"></div>
                            </div>
                            <span class="d-inline-block mt-2 text-muted"><?php echo e($orrder_this_month); ?>% increase</span>
                        </div>
                        <div class="col col-auto">
                            <a href="<?php echo e(route('dashboard_orders.index')); ?>">
                                <div class="counter-icon bg-success text-success box-success-shadow ml-auto">
                                    <i class="fe fe-shopping-cart text-white mb-5 "></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <a href="<?php echo e(route('dashboard_orders.index', ['status' => 3])); ?>" style="text-decoration: none;color: #000">
                                <h6 class="">Rejected Orders</h6>
                            </a>
                            <h3 class="mb-2 number-font"><?php echo e($ordersCount->rejected); ?></h3>
                            <p class="text-muted">
                                <span class="text-success"><i class="fa fa-chevron-circle-up text-success ml-1"></i> <?php echo e($orrder_this_month3); ?>%</span>
                                last month
                            </p>
                            <div class="progress h-2">
                                <div class="progress-bar bg-secondary" style="width:<?php echo e($orrder_this_month3); ?>%!important;" role="progressbar"></div>
                            </div>
                            <span class="d-inline-block mt-2 text-muted"><?php echo e($orrder_this_month3); ?>% increase</span>
                        </div>
                        <div class="col col-auto">
                            <a href="<?php echo e(route('dashboard_orders.index', ['status' => 3])); ?>">
                                <div class="counter-icon bg-secondary text-secondary box-secondary-shadow ml-auto">
                                    <i class="fe fe-x text-white mb-5 "></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <a href="<?php echo e(route('dashboard_orders.index', ['status' => 5])); ?>" style="text-decoration: none;color: #000">
                                <h6 class="">Accepted Orders</h6>
                            </a>
                            <h3 class="mb-2 number-font"><?php echo e($ordersCount->accepted); ?></h3>
                            <p class="text-muted">
                                <span class="text-success"><i class="fa fa-chevron-circle-up text-success ml-1"></i> <?php echo e($orrder_this_month5); ?>%</span>
                                last month
                            </p>
                            <div class="progress h-2">
                                <div class="progress-bar bg-info" style="width:<?php echo e($orrder_this_month3); ?>%!important;" role="progressbar"></div>
                            </div>
                            <span class="d-inline-block mt-2 text-muted"><?php echo e($orrder_this_month5); ?>% increase</span>
                        </div>
                        <div class="col col-auto">
                            <a href="<?php echo e(route('dashboard_orders.index', ['status' => 5])); ?>">
                                <div class="counter-icon bg-info text-info box-info-shadow ml-auto">
                                    <i class="fe fe-thumbs-up text-white mb-5 "></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <a href="<?php echo e(route('dashboard_orders.index', ['status' => 1])); ?>" style="text-decoration: none;color: #000">
                                <h6 class="">Pending Orders</h6>
                            </a>
                            <h3 class="mb-2 number-font"><?php echo e($ordersCount->pending); ?></h3>
                            <p class="text-muted">
                                <span class="text-success"><i class="fa fa-chevron-circle-up text-success ml-1"></i> <?php echo e($orrder_this_month1); ?>%</span>
                                last month
                            </p>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary" style="width:<?php echo e($orrder_this_month1); ?>%!important;" role="progressbar"></div>
                            </div>
                            <span class="d-inline-block mt-2 text-muted"><?php echo e($orrder_this_month1); ?>% increase</span>
                        </div>
                        <div class="col col-auto">
                            <a href="<?php echo e(route('dashboard_orders.index', ['status' => 1])); ?>">
                                <div class="counter-icon bg-primary text-primary box-primary-shadow ml-auto">
                                    <i class="fe fe-loader text-white mb-5 "></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="" style="display: none">
            <div class="card overflow-hidden bg-white work-progress">
                <canvas id="deals" class="chart-dropshadow-success chartjs-render-monitor" height="0" style="display: block; width: 0px; height: 0px;" width="0"></canvas>
            </div>
        </div><!-- COL END -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Transactions</h3>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                        <canvas id="total-coversations" width="400" height="100" style="display: block;"></canvas>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Information</h3>
                </div>
                <!-- TABLE WRAPPER -->
                <div class="card-body">

                    <div>
                        <form method="post" action="<?php echo e(route('dashboard_restaurant.export')); ?>">

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
                                            <select class="ajax_city form-control select2-show-search bg-white" data-placeholder="Filter City">
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
                                            <select class="ajax_cat form-control select2-show-search" data-placeholder="Filter Category">
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
                                    <th>Restaurant</th>
                                    <th>Orders</th>
                                    <th>Sales</th>
                                    <th>Pending</th>
                                    <th>Accepted</th>
                                    <th>Rejected</th>
                                </thead>
                            </table>

                        </div>

                    </div>
                </div>

            </div>
            <!-- SECTION WRAPPER -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        var array = [];
        const data = {
            labels: <?php echo $chartOrderLabels; ?> ,
            datasets: [{
                label: 'Total conversion',
                data: <?php echo $chartOrderValues; ?> ,
                fill: true,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        };

        $(document).ready(function() {
            var ctx = document.getElementById('total-coversations').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            var datatable = $('#data_Table').DataTable({
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "<?php echo e(route('dashboard_restaurant.get_data')); ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d._token = "<?php echo e(csrf_token()); ?>";
                        d.cat = $('.ajax_cat').val();
                        d.city = $('.ajax_city').val();
                        d.filter_role = $('#filter_role').val();
                    }
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "columns": [{
                        "data": "name"
                    },
                    {
                        "data": "orders"
                    },
                    {
                        "data": "sales"
                    },
                    {
                        "data": "pending"
                    },
                    {
                        "data": "accepted"
                    },
                    {
                        "data": "rejected"
                    },
                ]
            });;

            "use strict";
            //Code here.

            $(document).on('change', '.ajax_cat', function() {
                datatable.ajax.reload();
            });

            $(document).on('change', '.ajax_city', function() {
                datatable.ajax.reload();
            });
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/index.blade.php ENDPATH**/ ?>