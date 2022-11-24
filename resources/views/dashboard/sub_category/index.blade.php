@extends('dashboard.layouts.app')

@section('title')
    Sub category
@endsection

@section('create_btn'){{ route('dashboard_sub_category.add_edit') }}@endsection
    @section('create_btn_btn') Create new @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <button type="button" class="btn btn-sm btn-dark ajax_delete_all">
                            <i class="fa fa-trash"></i>
                        </button>
                        <table class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table" id="data_Table">
                            <thead>
                                <th>
                                    <label>
                                        <input type="checkbox" class="btn_select_all">
                                    </label>
                                </th>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Option</th>
                                <th>Priority</th>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')

    <script type="text/javascript">
        var alert_w = "Modal_Lock_Title}}";
        var alert_war = "Warning}}";
        var array = [];
        $(document).ready(function() {

            var datatabe;

            var type = getUrlParameter('type');
            "use strict";
            //Code here.
            Render_Data(type);

            var name_form = $('.ajaxForm').data('name');

            $(document).on('click', '.ajax_delete_all', function() {
                $.ajax({
                    url: "{{ route('dashboard_sub_category.deleted_all') }}",
                    method: "get",
                    data: {
                        "array": array,
                    },
                    dataType: "json",
                    success: function(result) {
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

            $(document).on('click', '.btn_select_all', function() {
                array = [];
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                $('.btn_select_btn_deleted').each(function(index, value) {
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

            $(document).on('click', '.btn_select_btn_deleted', function() {
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

            $(document).on('click', '.btn_delete_current', function() {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if (id) {
                    $('#data_Table tbody tr').css('background', 'transparent');
                    $('#data_Table tbody #item_' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });

            $('.btn_deleted').on("click", function() {
                var id = $('#iddel').val();
                $.ajax({
                    url: "{{ route('dashboard_sub_category.deleted') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function(result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        $('#data_Table').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on('click', '.btn_confirm_email_current', function() {
                var id = $(this).data("id");
                if (id) {
                    $('#data_Table tbody #item_' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }

                $.ajax({
                    url: "{{ route('dashboard_sub_category.confirm_email') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error, "sub_category");
                        } else {
                            toastr.success(result.success, "sub_category");
                        }
                        $('#data_Table').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on('change', '.input-priority', function(e) {
                const id = $(this).data("id");
                const priority = e.target.value;
                $.ajax({
                    url: "{{ route('dashboard_sub_category.priority') }}",
                    method: "get",
                    data: {
                        id,
                        priority,
                        flag: $('.ajax_cat').val()
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        $('#data_Table').DataTable().ajax.reload();
                    }
                });
            });
        });

        var Render_Data = function(type) {
            datatabe = $('#data_Table').DataTable({
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                }],
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('id', 'item_' + aData['id']);
                },
                "ajax": {
                    "url": "{{ route('dashboard_sub_category.get_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        'type': type,
                    }
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "confirm_email"
                    },
                    {
                        "data": "options"
                    }, {
                        "data": "priority"
                    }
                ]
            });
        };

    </script>


@endsection
