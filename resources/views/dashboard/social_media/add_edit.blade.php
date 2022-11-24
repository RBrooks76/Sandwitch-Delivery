@extends('dashboard.layouts.app')

@section('title')
    Social Media
@endsection

@section('css')
@endsection

@section('create_btn'){{route('dashboard_social_media.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users"
                          action="{{route('dashboard_social_media.post_data')}}" method="post">
                        {{csrf_field()}}

                        <input id="id" name="id" class="cls" type="hidden">
                        <div class="form-group col-md-12">
                            <label for="name">Name</label>
                            <input type="text" class="cls  form-control" name="name"
                                      id="name" placeholder="Name">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="icon">Enter icone from : https://fontawesome.com/icons</label>
                            <input type="text" class="cls  form-control" name="icon"
                                      id="icon" placeholder="Enter icone from : https://fontawesome.com/icons">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="link">Enter Link</label>
                            <input type="text" class="cls  form-control" name="link"
                                      id="link" placeholder="Enter Link">
                        </div>

                        <button type="submit" class="btn btn-primary btn-load">
                           Save Changes
                        </button>

                    </form>

                </div>
            </div>
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
                    $('.title_info').html("تعديل");
                    Render_Data(last_part);
                }
            } else {
                $('.title_info').html("انشاء جديد");
            }

        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_social_media.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#name').val(result.success.name);
                        $('#icon').val(result.success.icon);
                        $('#link').val(result.success.link);
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_social_media.index')}}";
                    }
                }
            });
        };

    </script>
@endsection
