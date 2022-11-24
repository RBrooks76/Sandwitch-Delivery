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

@section('create_btn'){{route('dashboard_users.index',['type'=>app('request')->input('type')])}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <h4 class="mb-2 mb-sm-0 pt-1">
                @yield("title")
            </h4>
            <hr>
            <form class="ajaxForm users" enctype="multipart/form-data" data-name="users"
                  action="{{route('dashboard_users.post_data')}}" method="post">
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
                               placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="cls form-control" name="email" id="email"
                               placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="cls form-control" name="phone" id="phone"
                               placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <label for="avatar">Avaar</label>
                        <input type="file" class="cls form-control" name="avatar" id="avatar">
                        <br>
                        <img class="img_usres avatar_view d-none img-thumbnail">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="cls form-control" name="password" id="password"
                               placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="text" class="cls form-control" name="password_confirmation"
                               id="password_confirmation" placeholder="Confirm Password">
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <a href="{{route('dashboard_users.index',['type'=>app('request')->input('type')])}}"
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
            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];
                newlp = last_part.split("?");
                console.log(newlp[0]);
                
            var name_form = $('.ajaxForm').data('name');

            if (isNaN(newlp[0]) == false) {
                if (last_part != null) {
                    $('.title_info').html("Edit");
                    Render_Data(newlp[0]);
                }
            } else {
                $('.title_info').html("Close");
            }


        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_users.get_data_by_id')}}",
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
                        $('#role').val(result.success.role);
                        $('#email').val(result.success.email);
                        $('#password').val(result.success.show_password);
                        $('#password_confirmation').val(result.success.show_password);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
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
