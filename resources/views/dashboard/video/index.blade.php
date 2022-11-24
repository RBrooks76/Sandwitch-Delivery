@extends('dashboard.layouts.app')

@section('title')
    Video
@endsection

@section('create_btn')

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
							</span>
                            <h3 class="m-portlet__head-text">
                                @yield('title')
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <form class="ajaxForm dashboard_video" enctype="multipart/form-data"
                              data-name="dashboard_video"
                              action="{{route('dashboard_video.post_data')}}" method="post">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input id="id" name="id" class="cls" type="hidden">
                                <div class="row">

                                    <div class="form-group col-md-12">
                                        <label for="summary">Summary</label>
                                        <textarea rows="4" class="cls sumernote form-control" name="summary"
                                                  id="summary" placeholder="Summary"></textarea>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="avatar">Avatar</label>
                                        <input type="file" class="cls form-control" name="avatar" id="avatar">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <img style="width: 80px;height: 80px;"
                                             class="img_usres avatar_view d-none img-thumbnail">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="link">Link Video</label>
                                        <input type="text" class="cls form-control" name="link" id="link">
                                    </div>

                                </div>

                            </div>
                            @includeIf("dashboard.layouts.seo")
                            <div class="modal-footer">
                                <input type="hidden" name="button_action" id="button_action" value="insert">
                                <a href="{{route('dashboard_video.index')}}" class="btn btn-default">
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
    </div>

@endsection


@section('js')

    <script type="text/javascript">
        $(document).ready(function () {
            "use strict";

            Render_Data();
        });

        var Render_Data = function () {
            $.ajax({
                url: "{{ route('dashboard_video.get_data_by_id') }}",
                method: "get",
                data: {},
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#link').val(result.success.link);
                        $('#setting_id').val(result.success.id);
                        $('#summary').summernote("code",result.success.summary);

                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar1);

                    }
                }
            });
        };

    </script>


@endsection
