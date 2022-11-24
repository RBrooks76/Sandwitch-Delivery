@extends('dashboard.layouts.app')

@section('title')
    {{$lang->Newsletter}}
@endsection

@section('create_btn')

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
							</span>
                            <h3 class="m-portlet__head-text">
                                @yield('title')
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <table class="table data_Table table-bordered" id="data_Table">
                            <thead>
                            <th>الرقم التسلسلي</th>
                            <th>البريد</th>
                            <th>الخيارات</th>
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
                    <h4 class="modal-title">{{$lang->Details}}
                    </h4>
                </div>
                <div class="modal-body row">
                    <ul class="list-group" id="res_de">

                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        {{$lang->Close}}
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
                    url: "{{ route('dashboard_newsletter.deleted') }}",
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
                language: {
                    aria: {
                        sortAscending: ": فعال لترتيب العمود تصاعديا", sortDescending: ": فعال لترتيب العمود تنازليا"
                    }
                    , emptyTable: "لا يوجد بيانات لعرضها", info: "عرض _START_ الى _END_ من _TOTAL_ صف", infoEmpty: "لا يوجد نتائج لعرضها", infoFiltered: "(filtered1 من _MAX_ اجمالي صفوف)", lengthMenu: "_MENU_", search: "بحث", zeroRecords: "لا يوجد نتائج لعرضها",
                    paginate: { sFirst: "الاول", sLast: "الاخير", sNext: "التالي", sPrevious: "السابق" }
                },
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "{{ route('dashboard_newsletter.get_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {
                        _token: "{{csrf_token()}}",
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "email"},
                    {"data": "options"}
                ]
            });
        };

    </script>


@endsection
