@extends('dashboard.layouts.app')

@section('title')
    Slider
@endsection

@section('css')
@endsection

@section('create_btn'){{route('dashboard_slider.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">


                    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users"
                          action="{{route('dashboard_slider.post_data')}}" method="post">
                        {{csrf_field()}}
                        <div class="modal-header">
                            <h5 class="modal-title title_info"></h5>
                        </div>
                        <div class="modal-body row">
                            <input id="id" name="id" class="cls" type="hidden">
                            <div class="form-group col-md-12">
                                <label for="name">Name</label>
                                <input type="text" class="cls form-control" name="name" id="name">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="summary">Summary</label>
                                <textarea rows="4" class="cls form-control sumernote" name="summary"
                                          id="summary"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="link">Google Store</label>
                                <input type="text" class="cls form-control" name="link" id="link">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="video">Apple Store</label>
                                <input type="text" class="cls form-control" name="video" id="video">
                            </div>
                            <div class="form-group col-6">
                                <label for="avatar1">Avatar</label>
                                <input type="file" class="cls form-control" name="avatar1" id="avatar1">
                            </div>
                            <div class="form-group col-6">
                                <img class="img_usres avatar1_view d-none img-thumbnail">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="button_action" id="button_action" value="insert">
                            <a href="{{route('dashboard_slider.index')}}" class="btn btn-default">
                                Close
                            </a>
                            <button type="submit" class="btn btn-primary btn-load">
                                Save Changes
                            </button>
                        </div>
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
                    $('.title_info').html("Edit");
                    Render_Data(last_part);
                }
            } else {
                $('.title_info').html("Create new");
            }

        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_slider.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#link').val(result.success.link);
                        $('#video').val(result.success.video);
                        $('#name').val(result.success.name);
                        $('#summary').summernote("code", result.success.summary);
                        $('.avatar1_view').removeClass('d-none');
                        $('.avatar1_view').attr('src', geturlphoto() + result.success.avatar1);
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_slider.index')}}";
                    }
                }
            });
        };

    </script>
@endsection
