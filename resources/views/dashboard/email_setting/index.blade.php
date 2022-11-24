@extends('dashboard.layouts.app')

@section('title')
    Email Setting
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
                        <form class="ajaxForm dashboard_email_setting" enctype="multipart/form-data" data-name="dashboard_email_setting"
                              action="{{route('dashboard_email_setting.post_data')}}" method="post">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input id="id" name="id" class="cls" type="hidden">
                                <div class="row">
                                    <div class="form-group col-md-6 col-6">
                                        <label for="email">Email</label>
                                        <input type="text" class="cls form-control" name="email" id="email"
                                               placeholder="Email">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="password">Password</label>
                                        <input type="text" class="cls form-control" name="password" id="password"
                                               placeholder="Password">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="driver">MAIL_DRIVER</label>
                                        <input type="text" class="cls form-control" name="driver" id="driver"
                                               placeholder="MAIL_DRIVER">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="host">MAIL_HOST</label>
                                        <input type="text" class="cls form-control" name="host" id="host"
                                               placeholder="MAIL_HOST">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="port">MAIL_PORT</label>
                                        <input type="text" class="cls form-control" name="port" id="port"
                                               placeholder="MAIL_PORT">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="encrption">MAIL_ENCRYPTION</label>
                                        <input type="text" class="cls form-control" name="encrption" id="encrption"
                                               placeholder="MAIL_ENCRYPTION">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="button_action" id="button_action" value="insert">
                                <a href="{{route('dashboard_email_setting.index')}}" class="btn btn-default">
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
        $(document).ready(function() {
            "use strict";

            Render_Data();

        });

        var Render_Data = function () {
            $.ajax({
                url: "{{ route('dashboard_email_setting.get_data_by_id') }}",
                method: "get",
                data: {},
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#email').val(result.success.email);
                        $('#driver').val(result.success.driver);
                        $('#host').val(result.success.host);
                        $('#port').val(result.success.port);
                        $('#encrption').val(result.success.encrption);
                        $('#password').val(result.success.password);
                        if(result.success.active == 1){
                            $('#active').attr("checked", "checked");
                        }
                    }
                }
            });
        };

    </script>


@endsection
