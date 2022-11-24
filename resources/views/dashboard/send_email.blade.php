@extends('dashboard.layouts.app')

@section('title')
    Send Email
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
                        <form class="ajaxForm send_email_send" enctype="multipart/form-data" data-name="send_email_send"
                              action="{{route('dashboard_send_email.send')}}" method="post">
                            {{csrf_field()}}
                            <div class="modal-header">
                                <h5 class="modal-title title_info"></h5>
                            </div>
                            <div class="modal-body row">
                                <input id="id" name="id" class="cls" type="hidden">
                                <div class="form-group col-md-6">
                                    <label for="name">Name Person</label>
                                    <input type="text" class="cls form-control" name="name" id="name"
                                           placeholder="Name Person">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email Address</label>
                                    <input type="text" class="cls form-control" name="email" id="email"
                                           placeholder="Email Address">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="name_temp">Name Template</label>
                                    <input type="text" class="cls form-control" name="name_temp" id="name_temp"
                                           placeholder="Name Template">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="summary">Summary</label>
                                    <textarea rows="4" class="cls sumernote form-control" name="summary"
                                              id="summary" placeholder="Summary"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-load">
                                   Send Now
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

            var datatabe;

            "use strict";
            //Code here.
            var emails = getUrlParameter('emails');
            var emails_d = emails.split(",");
            var c_emails = emails_d.length;
            if (c_emails != 0) {
                for (var i = 0; i < c_emails; i++) {
                    var s = emails_d[i];
                    var old = $("#email").val();
                    var ss = old + "," + s;
                    $("#email").val(ss);
                }
            }

        });

    </script>
@endsection
