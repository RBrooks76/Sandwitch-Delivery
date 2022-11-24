@extends('dashboard.layouts.app')

@section('title')
    Driver
@endsection

@section('create_btn'){{route('dashboard_city.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <form class="ajaxForm city" enctype="multipart/form-data" data-name="city" action="{{ URL('dashboard/driver/post_data')}}" method="post">
                {{csrf_field()}}

                <input id="id" name="id" class="cls" type="hidden">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="cls form-control" name="name" id="name" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" class="cls form-control" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Email</label>
                    <input type="text" class="cls form-control" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Mobile</label>
                    <input type="text" class="cls form-control" name="mobile" id="mobile" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Password</label>
                    <input type="text" class="cls form-control" name="password" id="password" required>
                </div>
                
                <div class="form-group">
                    <label for="name">City</label>
                    <select class="cls form-control" name="city" id="city" required>
                        <option value="">Select</option>
                        @foreach($CityList as $CL)
                            <option value="{{ $CL->id }}">{{ $CL->name }}</option>
                        @endforeach
                    </select>
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
            var url = $(location).attr('href'),
                parts = url.split("?"),
                last_part = parts[parts.length - 2];

            var parts2 = last_part.split("/"), last_part2 = parts2[parts2.length - 1];
            

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
                url: "{{URL('dashboard/driver/get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('#email').val(result.success.email);
                        $('#mobile').val(result.success.mobile);
                        $('#password').val(result.success.password);
                        $('#username').val(result.success.username);
                        $('#city').val(result.success.city);
                        $('.title').html(result.success.code);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{URL('dashboard/driver')}}";
                    }
                }
            });
        };

    </script>


@endsection
