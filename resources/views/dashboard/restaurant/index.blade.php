@extends('dashboard.layouts.app')

@section('title')
    Restaurant
@endsection

@section('create_btn'){{route('dashboard_restaurant.add_edit')}}@endsection
@section('create_btn_btn') Create new @endsection

@section('content')

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
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <select class="form-control ajax_city select2-show-search">
                                        <optgroup label="Choose City">
                                            <option value="">All City</option>
                                            @foreach($city as $r)
                                                <option value="{{$r->id}}">{{$r->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <select class="form-control ajax_cat select2-show-search">
                                        <optgroup label="Choose Restaurant">
                                            <option value="">All Category</option>
                                            @foreach($category_id as $r)
                                                <option value="{{$r->id}}">{{$r->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table"
                           id="data_Table">
                        <thead>
                        <th>
                            <label>
                                <input type="checkbox" class="btn_select_all">
                            </label>
                        </th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>City</th>
                        <th>Priority</th>
                        <th>Orders</th>
                        <th>Total Category</th>
                        <th>Comments</th>
                        <th>Review</th>
                        <th>Sales</th>
                        <th>Pending</th>
                        <th>Accepted</th>
                        <th>Rejected</th>
                        <th>Active</th>
                        <th>Option</th>
                        <th>Add Products</th>
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
        var array = [];
        $(document).ready(function () {

            var datatable = $('#data_Table').DataTable({
                "processing": true,
                "serverSide": true,
                "bStateSave": true,
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData['id']);
                },
                "ajax": {
                    "url": "{{ route('dashboard_restaurant.get_data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d._token = "{{csrf_token()}}";
                        d.cat = $('.ajax_cat').val();
                        d.city = $('.ajax_city').val();
                        d.filter_role = $('#filter_role').val();
                    }
                },
                "columnDefs": [{
                    "targets": [0, 1, 6, 7, 14, 15, 16],
                    "orderable": false
                }],
                "order": [[ 5, "asc" ]],
                "columns": [
                    {"data": "id"},
                    {"data": "avatar"},
                    {"data": "name"},
                    {"data": "Category"},
                    {"data": "City"},
                    {"data": "priority"},
                    {"data": "orders"},
                    {"data": "c_t"},
                    {"data": "comments"},
                    {"data": "reviewc"},
                    {"data": "sales"},
                    {"data": "pending"},
                    {"data": "accepted"},
                    {"data": "rejected"},
                    {"data": "featured"},
                    {"data": "options"},
                    {"data": "add"},
                ]
            });

            datatable.column(5).visible(false);
            "use strict";
            //Code here.
            $(document).on('change', '.ajax_cat', function () {
                datatable.ajax.reload();
                datatable.column(5).visible($('.ajax_city').val() != "");
            });
            
            $(document).on('change', '.input-priority', function(e){
            //   console.log(e.target.value, $(this).data('id'));
               var id = $(this).data("id");
               var priority = e.target.value;
                $.ajax({
                    url: "{{ route('dashboard_restaurant.priority') }}",
                    method: "get",
                    data: {
                        id,
                        priority,
                        flag: $('.ajax_cat').val()
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
                console.log('ll')
                var id = $(this).data("id");
                if (id) {
                    $('#data_Table tbody #' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
                $.ajax({
                    url: "{{ route('dashboard_restaurant.featured') }}",
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

            $('.btn_deleted').on("click", function () {
                var id = $('#iddel').val();
                $.ajax({
                    url: "{{ route('dashboard_restaurant.deleted') }}",
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
                    url: "{{ route('dashboard_restaurant.deleted_all') }}",
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


@endsection
