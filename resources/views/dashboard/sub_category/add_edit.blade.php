@extends('dashboard.layouts.app')

@section('title')
    Sub category
@endsection

@section('create_btn'){{route('dashboard_sub_category.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <form class="ajaxForm sub_category" enctype="multipart/form-data" data-name="sub_category"
                  action="{{route('dashboard_sub_category.post_data')}}" method="post">
                {{csrf_field()}}

                <input id="id" name="id" class="cls" type="hidden">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="cls form-control" name="name" id="name"
                           placeholder="Name">
                </div>

                <button type="submit" class="btn btn-primary btn-load">
                    Save Changes
                </button>
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
                parts = url.split("?"),
                last_part = parts[parts.length - 2];

            var parts2 = last_part.split("/"),
                last_part2 = parts2[parts2.length - 1];

            var name_form = $('.ajaxForm').data('name');

            if (isNaN(last_part2) == false) {
                if (last_part2 != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last_part2);
                }
            } else {
                $('.title_info').html("Create New");
            }


        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_sub_category.get_data_by_id')}}",
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
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_sub_category.index')}}";
                    }
                }
            });
        };

    </script>


@endsection
