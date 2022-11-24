@extends('dashboard.layouts.app')

@section('title')
    Posts
@endsection

@section('css')
@endsection

@section('create_btn'){{route('dashboard_posts.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users"
                          action="{{route('dashboard_posts.post_data')}}" method="post">
                        {{csrf_field()}}
                        <div class="modal-header">
                            <h5 class="modal-title title_info"></h5>
                        </div>
                        <div class="modal-body row">
                            <input id="id" name="id" class="cls" type="hidden">
                            <div class="form-group col-12">
                                <label for="name">Name</label>
                                <input type="text" class="cls form-control" name="name" id="name" placeholder="Name">
                            </div>
                            <div class="form-group col-12">
                                <label for="tags">Tags</label>
                                <input type="text" class="cls form-control" name="tags" id="tags"
                                       placeholder="Tags">
                            </div>
                            <div class="form-group col-12">
                                <label for="type">Type</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="">Type</option>
                                    <option value="1">Header</option>
                                    <option value="2">Footer 1</option>
                                    <option value="3">Footer 2</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="summary">Summary</label>
                                <textarea rows="4" class="cls sumernote form-control" name="summary"
                                          id="summary" placeholder="Summary"></textarea>
                            </div>
                            <div class="form-group col-6">
                                <label for="avatar">Avatar</label>
                                <input type="file" class="cls form-control" name="avatar" id="avatar">
                            </div>
                            <div class="form-group col-6">
                                <img class="img_usres avatar_view d-none img-thumbnail">
                            </div>
                        </div>
                        @includeIf("dashboard.layouts.seo")
                        <div class="modal-footer">
                            <input type="hidden" name="button_action" id="button_action" value="insert">
                            <a href="{{route('dashboard_posts.index')}}" class="btn btn-default">
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
                $('.title_info').html("Create New");
            }

        });

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_posts.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#type').val(result.success.type);
                        $('#tags').val(result.success.tags);
                        $('#name').val(result.success.name);
                        $('#keywords').val(result.success.keywords);
                        $('#description').val(result.success.description);
                        //tinymce.get('summary').setContent(result.success.summary);
                        $('#summary').summernote('code', result.success.summary);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);
                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_posts.index')}}";
                    }
                }
            });
        };

    </script>
@endsection
