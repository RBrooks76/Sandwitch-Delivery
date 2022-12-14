<?php $__env->startSection('title'); ?>
    Offers
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_offers.add_edit')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Create new <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">

                        <div class="filter-custom">
                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-sm btn-dark ajax_delete_all">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="col-lg-4 offset-lg-4 offset-md-3 mt-xs-2 mt-sm-3 mt-md-0">
                                    <div class="form-group">
                                        <select class="form-control ajax_city select2-show-search">
                                            <optgroup label="Choose City">
                                                <option value="">All City</option>
                                                <?php $__currentLoopData = $city; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <!--<div class="col-lg-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <select class="form-control ajax_cat select2-show-search">-->
                                <!--            <optgroup label="Choose Category">-->
                                <!--                <option value="">All Category</option>-->
                                <!--                <?php $__currentLoopData = $category_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->
                                <!--                    <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>-->
                                <!--                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
                                <!--            </optgroup>-->
                                <!--        </select>-->
                                <!--    </div>-->
                                <!--</div>-->
                            </div>
                        </div>

                        <table
                            class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table"
                            id="data_Table">
                            <thead>
                            <th>
                                <label>
                                    <input type="checkbox" class="btn_select_all">
                                </label>
                            </th>
                            <th>Name Products</th>
                            <th>Restaurant</th>
                            <th>Category</th>
                            <th>City</th>
                            <th id="priority-field">Priority</th>
                            <th>Price</th>
                            <th>Photo</th>
                            <th>Active</th>
                            <th>Option</th>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        var array = [];
        $(document).ready(function () {

            var datatable = $('#data_Table').DataTable({
                language: {
                    aria: {
                        sortAscending: ": ???????? ???????????? ???????????? ??????????????", sortDescending: ": ???????? ???????????? ???????????? ??????????????"
                    }
                    ,
                    emptyTable: "???? ???????? ???????????? ????????????",
                    info: "?????? _START_ ?????? _END_ ???? _TOTAL_ ????",
                    infoEmpty: "???? ???????? ?????????? ????????????",
                    infoFiltered: "(filtered1 ???? _MAX_ ???????????? ????????)",
                    lengthMenu: "_MENU_",
                    search: "??????",
                    zeroRecords: "???? ???????? ?????????? ????????????",
                    paginate: {sFirst: "??????????", sLast: "????????????", sNext: "????????????", sPrevious: "????????????"}
                },
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "<?php echo e(route('dashboard_offers.get_data')); ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data":  function(d) {
                        d._token = "<?php echo e(csrf_token()); ?>";
                        // d.cat = $('.ajax_cat').val();
                        d.city = $('.ajax_city').val();
                        d.filter_role = $('#filter_role').val();
                    },
                },
                "columnDefs": [{
                    "targets": [0, 7, 8],
                    "orderable": false
                }],
                "order": [[ 5, "asc" ]],
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "restaurant"},
                    {"data": "cat"},
                    {"data": "city"},
                    {"data": "priority"},
                    {"data": "price"},
                    {"data": "avatar"},
                    {"data": "featured"},
                    {"data": "options"}
                ]
            });
            datatable.column(5).visible($('.ajax_city').val() != "");
            "use strict";
            //Code here.

            // $(document).on('change', '.ajax_cat', function () {
            //     datatable.ajax.reload();
            // });

            $(document).on('change', '.ajax_city', function () {
                datatable.ajax.reload();
                datatable.column(5).visible($('.ajax_city').val() != "");
            });

            $(document).on('click', '.btn_delete_current', function () {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if (id) {
                    $('#data_Table tbody tr').css('background', 'transparent');
                    $('#data_Table tbody #' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });

            $(document).on('click', '.btn_featured', function () {
                var id = $(this).data("id");
                if (id) {
                    $('#data_Table tbody #' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
                $.ajax({
                    url: "<?php echo e(route('dashboard_offers.featured')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        datatable.ajax.reload();
                    }
                });
            });

            $(document).on('change', '.input-priority', function(e){
            //   console.log(e.target.value, $(this).data('id'));
               var id = $(this).data("id");
               var priority = e.target.value;
                $.ajax({
                    url: "<?php echo e(route('dashboard_offers.priority')); ?>",
                    method: "get",
                    data: {
                        id,
                        priority
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        datatable.ajax.reload();
                    }
                });
            });

            $('.btn_deleted').on("click", function () {
                var id = $('#iddel').val();
                $.ajax({
                    url: "<?php echo e(route('dashboard_offers.deleted')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                       datatable.ajax.reload();
                    }
                });
            });


            $(document).on('click', '.ajax_delete_all', function () {
                $.ajax({
                    url: "<?php echo e(route('dashboard_offers.deleted_all')); ?>",
                    method: "get",
                    data: {
                        "array": array,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                            $("input:checkbox").prop('checked', false);
                            datatable.ajax.reload();
                        } else {
                            toastr.success(result.success);
                            $("input:checkbox").prop('checked', false);
                            datatable.ajax.reload();
                        }
                    }
                });
            });

            $(document).on('click', '.btn_select_all', function () {
                array = [];
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                $('.btn_select_btn_deleted').each(function (index, value) {
                    var id = $(value).data("id");
                    var status = $(value).prop("checked");
                    if (status == true) {
                        array.push(id);
                    } else {
                        var index2 = array.indexOf(id);
                        if (index2 > -1) {
                            array.splice(index2, 1);
                        }
                    }
                });
            });

            $(document).on('click', '.btn_select_btn_deleted', function () {
                var id = $(this).data("id");
                var status = $(this).prop("checked");
                var numberOfChecked = $('input:checkbox:checked').length;
                var numberOftext = $('.btn_select_btn_deleted').length;
                if (status == true) {
                    array.push(id);
                } else {
                    var index = array.indexOf(id);
                    if (index > -1) {
                        array.splice(index, 1);
                    }
                }
                if (numberOftext != array.length) {
                    $(".btn_select_all").prop('checked', false);
                }
                if (numberOftext == array.length) {
                    $(".btn_select_all").prop('checked', $(this).prop('checked'));
                }
            });


        });

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/offers/index.blade.php ENDPATH**/ ?>