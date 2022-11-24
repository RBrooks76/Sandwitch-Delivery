@extends('dashboard.layouts.app')

@section('title')
    Orders
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <div class="table-responsive">
                            <div class="filter-custom">

                                <table class="table data_Table table-bordered" id="data_Table">
                                    <thead>
                                    <th>
                                        #
                                    </th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Orders</th>
                                    <th>City</th>
                                    <th>Cost of purchases</th>
                                    <th>Total</th>
                                    <th>Option</th>
                                    </thead>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection


        @section('js')
            <script type="text/javascript">
                var array = [];
                $(document).ready(function () {

                    var datatabe;

                    "use strict";
                    //Code here.


                    Render_Data();


                    $('.btn_deleted').on("click", function () {
                        var id = $('#iddel').val();
                        $.ajax({
                            url: "{{ route('dashboard_clients.deleted') }}",
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


                    $(document).on('click', '.btn_delete_current', function () {
                        var id = $(this).data("id");
                        $('#ModDelete').modal('show');
                        $('#iddel').val(id);
                        if (id) {
                            $('#data_Table tbody tr').css('background', 'transparent');
                            $('#data_Table tbody #' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                        }
                    });

                    $(document).on('change', '.ajax_Restaurant', function () {
                        var id = $(this).val();
                        $('#data_Table').dataTable().fnClearTable();
                        $('#data_Table').dataTable().fnDestroy();
                        Render_Data(id, status);
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
                            "url": "{{ route('dashboard_clients.get_data') }}",
                            "dataType": "json",
                            "type": "POST",
                            "data": {
                                _token: "{{csrf_token()}}",
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
                            {"data": "orders1"},
                            {"data": "city"},
                            {"data": "count"},
                            {"data": "total"},
                            {"data": "options"},
                        ]
                    });
                };

            </script>


@endsection
