@extends('dashboard.layouts.app')

@section('title')
    Social Media
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
                        <form class="ajaxForm dashboard_hp_contact_us" enctype="multipart/form-data" data-name="dashboard_hp_contact_us"
                              action="{{route('dashboard_hp_contact_us.post_data')}}" method="post">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <input id="id" name="id" class="cls" type="hidden">
                                <div class="row">
                                    <div class="form-group col-md-6 col-6">
                                        <label for="whatsapp">Whats app</label>
                                        <input type="text" class="cls form-control" name="whatsapp" id="whatsapp"
                                               placeholder="Whats app">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="cls form-control" name="phone" id="phone"
                                               placeholder="Phone">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="email">Email Address</label>
                                        <input type="text" class="cls form-control" name="email" id="email"
                                               placeholder="Email Address">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="address">Address</label>
                                        <input type="text" class="cls form-control" name="address" id="address"
                                               placeholder="Address">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-6">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" class="cls form-control" name="facebook" id="facebook"
                                               placeholder="Facebook">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" class="cls form-control" name="instagram" id="instagram"
                                               placeholder="Instagram">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" class="cls form-control" name="twitter" id="twitter"
                                               placeholder="Twitter">
                                    </div>
                                    <div class="form-group col-md-6 col-6">
                                        <label for="pinterest">Youtube</label>
                                        <input type="text" class="cls form-control" name="pinterest" id="pinterest"
                                               placeholder="Youtube">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="button_action" id="button_action" value="insert">
                                <a href="{{route('dashboard_hp_contact_us.index')}}" class="btn btn-default">
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
                url: "{{ route('dashboard_hp_contact_us.get_data_by_id') }}",
                method: "get",
                data: {},
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#summary').val(result.success.summary);
                        $('#email').val(result.success.email);
                        $('#phone').val(result.success.phone);
                        $('#whatsapp').val(result.success.whatsapp);
                        $('#address').val(result.success.address);
                        $('#iframe').val(result.success.iframe);
                        $('#instagram').val(result.success.instagram);
                        $('#twitter').val(result.success.twitter);
                        $('#facebook').val(result.success.facebook);
                        $('#hours').val(result.success.hours);
                        $('#pinterest').val(result.success.pinterest);
                        $('#fax').val(result.success.fax);
                        //$('#hours').summernote('code',result.success.hours);
                    }
                }
            });
        };

    </script>


@endsection
