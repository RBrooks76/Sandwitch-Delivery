@extends('dashboard.layouts.app')

@section('title')
    @if(app('request')->input('type') == 1)
        Admin
    @elseif(app('request')->input('type') == 2)
        Clients
    @elseif(app('request')->input('type') == 3)
        Rider
    @elseif(app('request')->input('type') == 4)
        Restaurants
    @else
        User
    @endif
@endsection

@section('create_btn'){{route('dashboard_users.add_edit',['id'=>null,'type'=>app('request')->input('type')])}}@endsection
@section('create_btn_btn') Create New @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="filter-custom">
                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-sm btn-dark ajax_delete_all">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
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
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Avatar</th>
                            <th>Role</th>
                            <th>Active</th>
                            <th>Option</th>
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
        $(document).ready(function () {

            var datatabe;

            var type = getUrlParameter('type');
            "use strict";
            //Code here.
            Render_Data(type);


            var name_form = $('.ajaxForm').data('name');

            /*$('#data_Table tbody').sortable({
                axis: 'y',
                update: function (event, ui) {
                    var data = $(this).sortable('serialize');
                    console.log(data);
                }
            });*/


            $(document).on('click', '.ajax_delete_all', function () {
                $.ajax({
                    url: "{{ route('dashboard_users.deleted_all') }}",
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

            $(document).on("click", ".PopUp", function () {
                $('#PopUp .modal-title').html($(this).attr("title"));
                $('.modal .title').html('انشاء مستخدم جديد');
                $("#PopUp").modal({show: true, backdrop: "static"});
            });

            $(document).on("click", ".btn_edit_current", function () {
                $('#PopUp .modal-title').html($(this).attr("title"));
                $("#PopUp").modal({show: true, backdrop: "static"});
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
                    url: "{{ route('dashboard_users.deleted') }}",
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
                    url: "{{ route('dashboard_users.confirm_email') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error, "@lang('table.confirm_email')");
                        } else {
                            toastr.success(result.success, "@lang('table.confirm_email')");
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
                    "url": "{{ route('dashboard_users.get_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{csrf_token()}}",
                        'type': type,
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "phone"},
                    {"data": "email"},
                    {"data": "avatar"},
                    {"data": "role"},
                    {"data": "confirm_email"},
                    {"data": "options"}
                ]
            });
        };

    </script>


@endsection
