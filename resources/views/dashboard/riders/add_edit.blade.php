@extends('dashboard.layouts.app')

@section('title')
    Rider
@endsection

@section('create_btn'){{route('dashboard_riders.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <h4 class="mb-2 mb-sm-0 pt-1">
                @yield("title")
            </h4>
            <hr>
            <form class="ajaxForm user_ride" enctype="multipart/form-data" data-name="user_ride"
                  action="{{route('dashboard_riders.post_data')}}" method="post">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title title_info">

                    </h5>
                    <div class="stud"></div>
                </div>
                <div class="modal-body">
                    <input id="id" name="id" class="cls" type="hidden">
                    <input id="role" value="{{app('request')->input('type')}}" name="role" class="cls" type="hidden">
                    <input id="type" value="{{app('request')->input('type')}}" name="type" class="cls" type="hidden">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="cls form-control" name="name" id="name"
                               placeholder="Name...">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="cls form-control" name="phone" id="phone"
                               placeholder="Phone...">
                    </div>
                    <div class="form-group">
                        <label for="role">Nationality</label>
                        <select class="form-control" id="country" name="country">
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city_id">City</label>
                        <select class="form-control" id="city_id" name="city_id">
                            @foreach($city_id as $r)
                                <option value="{{$r->id}}">{{$r->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="cls form-control" name="email" id="email"
                               placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="role">Driving License Type</label>
                        <select class="form-control" id="license" name="license">
                            <option value="1">Car driving license</option>
                            <option value="2">Bike driving license</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <a href="{{route('dashboard_riders.index')}}"
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
            $.ajax({
                url: "https://restcountries.eu/rest/v2/all",
                type: 'get',
                dataType: 'json',
                async: false,
                success: function(res) {
                    res.map(info => {
                        $('#country').append($('<option>', {value: info.name, text: info.name}));
                    });
                }
            });
        
            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("?"),
                    last_part = parts[parts.length - 2];

            var parts2 = url.split("/"),
                last_part2 = parts2[parts2.length - 1];

            var name_form = $('.ajaxForm').data('name');

            if (isNaN(last_part2) == false) {
                if (last_part2 != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last_part2);
                }
            } else {
                $('.title_info').html("Close");
            }


        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_riders.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        console.log(result.success)
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('#phone').val(result.success.phone);
                        $('#email').val(result.success.email);
                        $('#license').val(result.success.license);
                        $('#country').val(result.success.country);
                        $('#city_id').val(result.success.city_id);
                        $('#button_action').val('edit');

                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_users.index')}}";
                    }
                }
            });
        };

    </script>


@endsection
