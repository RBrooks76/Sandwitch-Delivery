@extends('dashboard.layouts.app')

@section('title')
    Coupon
@endsection

@section('create_btn'){{route('dashboard_city.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="card mb-4 wow fadeIn">
        <div class="card-body">

            <form class="ajaxForm city" enctype="multipart/form-data" data-name="city" action="{{ URL('dashboard/coupons/post_data')}}" method="post">
                {{csrf_field()}}

                <input id="id" name="id" class="cls" type="hidden">

                <div class="form-group">
                    <label for="name">Code</label>
                    <input type="text" class="cls form-control" name="code" id="code" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Amount</label>
                    <input type="number" class="cls form-control" name="amount" id="amount" required>
                </div>
                
                <div class="form-group">
                    <label for="name">Expiry Date</label>
                    <input type="date" class="cls form-control" name="expiry_date" id="expiry_date" required>
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
                url: "{{URL('dashboard/coupons/get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#code').val(result.success.code);
                        $('#code').attr("readonly", true);
                        $('#amount').val(result.success.amount);
                        $('#expiry_date').val(result.success.expiry_date);
                        $('.title').html(result.success.code);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                        $('#button_action').val('edit');
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_city.index')}}";
                    }
                }
            });
        };

    </script>


@endsection
