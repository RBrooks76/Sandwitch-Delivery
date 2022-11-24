@extends('dashboard.layouts.app')

@section('title')
    Contact US
@endsection

@section('create_btn')

@endsection

@section('content')

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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModDelatils" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Details
                    </h4>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-12">
                        <ul class="list-group" id="res_de">

                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

@endsection


@section('js')

    <script type="text/javascript">
        $(document).ready(function () {

            var datatabe;

            "use strict";
            //Code here.
            Render_Data();
            var name_form = $('.ajaxForm').data('name');

            $(document).on('click', '.btn_eye', function () {
                var id = $(this).data("id");
                $.ajax({
                    url: "{{ route('dashboard_contact_us.details') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.success != null) {
                            $('#ModDelatils').modal('show');
                            $('#res_de').html('');
                            $('#res_de').html('' +
                                '<li class="list-group-item">f_name : ' + result.success.f_name + '</li>' +
                                '<li class="list-group-item">l_name : ' + result.success.l_name + '</li>' +
                                '<li class="list-group-item">Email : ' + result.success.email + '</li>' +
                                '<li class="list-group-item">Phone : ' + result.success.phone + '</li>' +
                                '<li class="list-group-item">Summary : ' + result.success.summary + '</li>');
                        }
                    }
                });
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

            $('.btn_deleted').on("click", function () {
                var id = $('#iddel').val();
                $.ajax({
                    url: "{{ route('dashboard_contact_us.deleted') }}",
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

        });

        var Render_Data = function () {
            datatabe = $('#data_Table').DataTable({
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "{{ route('dashboard_contact_us.get_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{csrf_token()}}",
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "email"},
                    {"data": "phone"},
                    {"data": "options"}
                ]
            });
        };

    </script>


@endsection
