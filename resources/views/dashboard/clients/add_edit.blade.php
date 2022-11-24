@extends('dashboard.layouts.app')

@section('title')
    Edit Orders
@endsection

@section('create_btn'){{route('dashboard_clients.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <h4 class="mb-2 mb-sm-0 pt-1">
                @yield("title")
            </h4>
            <hr>
            <form class="ajaxForm orders" enctype="multipart/form-data" data-name="orders"
                  action="{{route('dashboard_clients.post_data')}}" method="post">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title title_info">

                    </h5>
                    <div class="stud"></div>
                </div>
                <div class="modal-body">
                    <input id="id" name="id" class="cls" type="hidden">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="cls form-control" name="name" id="name"
                               placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="cls form-control" name="phone" id="phone"
                               placeholder="Phone">
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <a href="{{route('dashboard_clients.index')}}"
                       class="btn btn-default">
                        Close
                    </a>
                    <button type="submit" class="btn btn-primary btn-load">
                       Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>


@endsection


@section('js')

    <script type="text/javascript">
        $(document).ready(function () {

            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var name_form = $('.ajaxForm').data('name');

            if (isNaN(last_part) == false) {
                if (last_part != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last_part);
                }
            } else {
                $('.title_info').html("Close");
            }


        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_clients.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('.title').html(result.success.name);
                        $('#phone').val(result.success.phone);
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_clients.index')}}";
                    }
                }
            });
        };

    </script>


@endsection
