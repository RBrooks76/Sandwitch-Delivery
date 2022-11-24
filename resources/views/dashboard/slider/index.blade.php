@extends('dashboard.layouts.app')

@section('title')
    Slider
@endsection

@section('css')
@endsection

@section('create_btn'){{route('dashboard_slider.add_edit')}}@endsection
@section('create_btn_btn') Create new @endsection

@section('content')

    <div class="row row-cards row-deck">

        @foreach($items as $r)
            <div class="col-md-4 data_Table" id="data_Table{{$r->id}}">
                <div class="card">
                    <img class="card-img-topbr-tr-0 br-tl-0" src="{{$r->img1()}}" alt="{{$r->name}}">
                    <div class="card-header">
                        <h5 class="card-title">{{$r->name}}</h5>
                    </div>
                    <div class="card-body">
                        <a class="btn btn-sm btn-primary" href="{{route('dashboard_slider.add_edit',['id' => $r->id])}}"><i class="fa fa-edit"></i> Edit</a>
                        <a class="btn btn-sm btn-danger btn_delete_current" data-id='{{$r->id}}' href="#"><i class="fa fa-trash"></i> Delete</a>
                        <div class="material-switch pull-right">
                            <input id="someSwitchOptionSuccess{{$r->id}}" class="btn_featured" data-id="{{$r->id}}" type="checkbox" {{$r->active == 1 ? "checked" : ""}}>
                            <label for="someSwitchOptionSuccess{{$r->id}}" class="label-success"></label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>


@endsection


@section('js')
    <script type="text/javascript">
        var array = [];
        $(document).ready(function () {

            var datatabe;

            "use strict";
            //Code here.

            var name_form = $('.ajaxForm').data('name');

            $(document).on('click', '.btn_delete_current', function () {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if (id) {
                    $('.data_Table').css('background', 'transparent');
                    $('#data_Table' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });

            $(document).on('click', '.btn_featured', function () {
                var id = $(this).data("id");
                if (id) {
                    $('.data_Table').css('background', 'transparent');
                    $('#data_Table' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
                $.ajax({
                    url: "{{ route('dashboard_slider.featured') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        if(result.redirect != null){
                            window.location.href = result.redirect;
                        }
                    }
                });
            });

            $('.btn_deleted').on("click", function () {
                var id = $('#iddel').val();
                $.ajax({
                    url: "{{ route('dashboard_slider.deleted') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function (result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        if(result.redirect != null){
                            window.location.href = result.redirect;
                        }
                    }
                });
            });

            $(document).on('click', '.ajax_delete_all', function () {
                $.ajax({
                    url: "{{ route('dashboard_slider.deleted_all') }}",
                    method: "get",
                    data: {
                        "array": array,
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        if(result.redirect != null){
                            window.location.href = result.redirect;
                        }
                    }
                });
            });

            $(document).on('click', '.btn_select_all', function () {
                array = [];
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                $('.btn_select_btn_deleted').each(function (index, value) {
                    var id = $(value).data("id");
                    var status = $(value).prop("checked");
                    if (status == true) {
                        array.push(id);
                    } else {
                        var index2 = array.indexOf(id);
                        if (index2 > -1) {
                            array.splice(index2, 1);
                        }
                    }
                });
            });

            $(document).on('click', '.btn_select_btn_deleted', function () {
                var id = $(this).data("id");
                var status = $(this).prop("checked");
                var numberOfChecked = $('input:checkbox:checked').length;
                var numberOftext = $('.btn_select_btn_deleted').length;
                if (status == true) {
                    array.push(id);
                } else {
                    var index = array.indexOf(id);
                    if (index > -1) {
                        array.splice(index, 1);
                    }
                }
                if (numberOftext != array.length) {
                    $(".btn_select_all").prop('checked', false);
                }
                if (numberOftext == array.length) {
                    $(".btn_select_all").prop('checked', $(this).prop('checked'));
                }
            });

        });

    </script>


@endsection
