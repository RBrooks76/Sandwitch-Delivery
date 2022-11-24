<?php $__env->startSection('title'); ?>
    Comments
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">

                        <table
                            class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table"
                            id="data_Table">
                            <thead>
                            <th>#</th>
                            <th>Name Customer</th>
                            <th>Mobile Customer</th>
                            <th>Time</th>
                            <th>Review</th>
                            <th>Comment</th>
                            <th>Option</th>
                            <th>View</th>
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

            var datatabe;

            "use strict";
            //Code here.

            var restaurant_id = getUrlParameter('restaurant_id');

            if (restaurant_id) {
                Render_Data(restaurant_id, null);
            }

            $(document).on('change', '.ajax_cat', function () {
                var id = $(this).val();
                $('#data_Table').dataTable().fnClearTable();
                $('#data_Table').dataTable().fnDestroy();
                Render_Data(restaurant_id, id);
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
                    url: "<?php echo e(route('dashboard_comments.featured')); ?>",
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
                        $('#data_Table').DataTable().ajax.reload();
                    }
                });
            });

            $('.btn_deleted').on("click", function () {
                var id = $('#iddel').val();
                $.ajax({
                    url: "<?php echo e(route('dashboard_comments.deleted')); ?>",
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

            $(document).on('click', '.ajax_delete_all', function () {
                $.ajax({
                    url: "<?php echo e(route('dashboard_comments.deleted_all')); ?>",
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

        });

        var Render_Data = function (restaurant_id, cat = null) {
            datatabe = $('#data_Table').DataTable({
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "<?php echo e(route('dashboard_comments.get_data')); ?>",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "<?php echo e(csrf_token()); ?>",
                        restaurant_id: restaurant_id,
                        cat: cat,
                        'filter_role': $('#filter_role').val(),
                    }
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "phone"},
                    {"data": "date"},
                    {"data": "desc"},
                    {"data": "review"},
                    {"data": "options"},
                    {"data": "view"},
                ]
            });
        };

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sandwichmap/public_html/resources/views/dashboard/comments/index.blade.php ENDPATH**/ ?>