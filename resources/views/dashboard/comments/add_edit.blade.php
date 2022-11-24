@extends('dashboard.layouts.app')

@section('title')
    Products
@endsection

@section('create_btn'){{route('dashboard_comments.index',['id'=>null,'restaurant_id'=>app('request')->input('restaurant_id')])}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users"
          action="{{route('dashboard_comments.post_data')}}" method="post">
        {{csrf_field()}}

        <input id="id" name="id" class="cls" type="hidden">
        <input id="restaurant_id" name="restaurant_id" class="cls"
               value="{{app('request')->input('restaurant_id')}}" type="hidden">

        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="form-group col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="comment" rows="5"
                                  name="comment"
                                  placeholder="Enter Description here ..."></textarea>
                    </div>

                </div>
            </div>


            <div class="card">
                <div class="card-footer">
                    <button type="submit" class="btn btn-success mt-1">+ Edit Comment</button>
                </div>
            </div>

    </form>
@endsection


@section('js')
    <script type="text/javascript">
        var isizes = 1;
        $(document).ready(function () {

            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var last = last_part.split("?")[0];


            if (isNaN(last) == false) {
                if (last != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last);
                }
            } else {
                $('.title_info').html("Create new");
            }

        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_comments.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('#comment').val(result.success.comment);

                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_comments.index')}}";
                    }
                }
            });
        };

    </script>
@endsection
