<?php $__env->startSection('title'); ?>
    Category
<?php $__env->stopSection(); ?>

<?php $__env->startSection('create_btn'); ?><?php echo e(route('dashboard_category.add_edit')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('create_btn_btn'); ?> Create new <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">

                        <button type="button" class="btn btn-sm btn-dark ajax_delete_all">
                            <i class="fa fa-trash"></i>
                        </button>
                        <table
                            class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table"
                            id="data_Table">
                            <thead>
                            <th>
                                <label>
                                    <input type="checkbox" class="btn_select_all">
                                </label>
                            </th>
                            <th>Name</th>
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
        var alert_w = "Modal_Lock_Title}}";
        var alert_war = "Warning}}";
        var array = [];
        $(document).ready(function () {

            var datatabe;

            var type = getUrlParameter('type');
            "use strict";
            //Code here.
            Render_Data(type);


            var name_form = $('.ajaxForm').data('name');


            $(document).on('click', '.ajax_delete_all', function () {
                $.ajax({
                    url: "<?php echo e(route('dashboard_category.deleted_all')); ?>",
                    method: "get",
                    data: {
                        "array": array,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                            $("input:checkbox").prop('checked', false);
                            $('#data_Table').DataTable().ajax.reload();
                        } else {
                            toastr.success(result.success);
                            $("input:checkbox").prop('checked', false);
                            $('#data_Table').DataTable().ajax.reload();
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

            $(document).on('click', '.btn_delete_current', function () {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if (id) {
                    $('#data_Table tbody tr').css('background', 'transparent');
                    $('#data_Table tbody #item_' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });

            $('.btn_deleted').on("click", function () {
                var id = $('#iddel').val();
                $.ajax({
                    url: "<?php echo e(route('dashboard_category.deleted')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        $('#data_Table').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on('click', '.btn_confirm_email_current', function () {
                var id = $(this).data("id");
                if (id) {
                    $('#data_Table tbody #item_' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }

                $.ajax({
                    url: "<?php echo e(route('dashboard_category.confirm_email')); ?>",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error, "category");
                        } else {
                            toastr.success(result.success, "category");
                        }
                        $('#data_Table').DataTable().ajax.reload();
                    }
                });
            });

        });

        var Render_Data = function (type) {
            datatabe = $('#data_Table').DataTable({
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', 'item_' + aData['id']);
                },
                "ajax": {
                    "url": "<?php echo e(route('dashboard_category.get_data')); ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "<?php echo e(csrf_token()); ?>",
                        'type': type,
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "confirm_email"},
                    {"data": "options"}
                ]
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mapstore/public_html/resources/views/dashboard/category/index.blade.php ENDPATH**/ ?>