@extends('dashboard.layouts.app')

@section('title')
    Offers
@endsection

@php
    $selctor = "ltr";
@endphp

@section('create_btn'){{route('dashboard_offers.index')}}@endsection
@section('create_btn_btn') Close @endsection

@section('content')

    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users" action="{{route('dashboard_offers.post_data')}}" method="post">
        {{csrf_field()}}

        <input id="id" name="id" class="cls" type="hidden">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="modal-body row">

                            <div class="form-group col-md-12">
                                <label for="city_id">City</label>
                                <select name="city_id" id="city_id" class="single-filter-select">
                                    @if($city_id->count() != 0)
                                        @foreach($city_id as $item)
                                            <option value="{{$item->id}}"
                                                    id="category_id_select_2_{{$item->id}}"
                                            >{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="category_id">Categories</label>
                                <select name="category_id" id="category_id"
                                        class="single-filter-select">
                                    @if($category_id->count() != 0)
                                        @foreach($category_id as $item)
                                            <option value="{{$item->id}}"
                                                    id="category_id_select_2_{{$item->id}}"
                                            >{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="restaurant_id">Name Restaurant</label>
                                <select name="restaurant_id" id="restaurant_id">
                                    @if($restaurant_id->count() != 0)
                                        @foreach($restaurant_id as $item)
                                            <option value="{{$item->id}}"
                                                    id="category_id_select_2_{{$item->id}}"
                                            >{{$item->user->name ?? ""}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="sub_category_id">Sub Category</label>
                                <select name="sub_category_id" id="sub_category_id">
                                    @if($sub_category_id->count() != 0)
                                        @foreach($sub_category_id as $item)
                                            <option value="{{$item->id}}"
                                                    id="category_id_select_2_{{$item->id}}"
                                            >{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="products_id">Name Product</label>
                                <select name="products_id" id="products_id">
                                    @if($products_id->count() != 0)
                                        @foreach($products_id as $item)
                                            <option value="{{$item->id}}"
                                                    id="category_id_select_2_{{$item->id}}"
                                            >{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            
                            <div class="form-group col-md-12">
                                <label for="products_id">Discounted Price</label>
                                <input type="number" step="any" name="amount" id="amount" class="form-control">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for="products_id">Percentage Off</label>
                                <input type="text" name="off_percentage" id="off_percentage" class="form-control">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for="products_id">End Date</label>
                                <input type="date" name="ending_in_date" id="ending_in_date" class="form-control">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for="products_id">End Time</label>
                                <input type="time" name="ending_in_time" id="ending_in_time" class="form-control">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for="products_id">Image</label>
                                <input type="file" name="coverImage" class="form-control">
                                <img src="" width="200" id="CoverImageImg">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label for="products_id">Video</label>
                                <input type="file" name="Video" class="form-control">
                                
                                <div id="LoadVide"></div>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <input name="PushNoti" value="1" type="checkbox"> Send Push Notification?
                                
                                <div class="mb-2">
                                    <input type="text" class="form-control" placeholder="Enter Push Notification Title" name="PushTitle">
                                </div>
                                
                                <textarea type="text" class="form-control" placeholder="Enter Push Notification Message" name="PushMessage"></textarea>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-footer">
                <button type="submit" class="btn btn-success mt-1">Add</button>
            </div>
        </div>

    </form>
@endsection


@section('js')
    <script type="text/javascript">
        
        var $selectRes = $('#restaurant_id').multipleSelect({single: true, filter: true});
        var $selectSub = $('#sub_category_id').multipleSelect({single: true, filter: true});
        var $selectPro = $('#products_id').multipleSelect({single: true, filter: true});
        
        $(document).ready(function () {

            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var name_form = $('.ajaxForm').data('name');
            
            var cityIndex = $('#city_id').val();
            var catIndex = $('#category_id').val();
            var resIndex = $('#restaruant_id').val();
            var subIndex = $('#sub_category_id').val();
            
            
            $('#city_id').change(function (e) {
                if(cityIndex != $('#city_id').val()) {
                    cityIndex = $('#city_id').val();
                     $.ajax({
                        url: "{{route('dashboard_restaurant.get_by_cat_city')}}",
                        method: "post",
                        async: false,
                        data: { 
                            _token: "{{csrf_token()}}",
                            city: $('#city_id').val(),
                            cat: $('#category_id').val()
                        },
                        dataType: "json",
                        success: function (result) {
                            console.log(result);
                            $selectRes.find('option').remove();
                            result.map((res, index) => {
                              $selectRes.append($("<option/>", {value: res.id, text: res.user.name}));
                            });
                            $selectRes.multipleSelect('refresh');
                        }
                    });
                }
            });
            
             $('#category_id').change(function (e) {
                if(catIndex != $('#category_id').val()) {
                    catIndex = $('#category_id').val();
                     $.ajax({
                        url: "{{route('dashboard_restaurant.get_by_cat_city')}}",
                        method: "post",
                        async: false,
                        data: { 
                            _token: "{{csrf_token()}}",
                            city: $('#city_id').val(),
                            cat: $('#category_id').val()
                        },
                        dataType: "json",
                        success: function (result) {
                            console.log(result);
                            $selectRes.find('option').remove();
                            result.map((res, index) => {
                              $selectRes.append($("<option/>", {value: res.id, text: res.user.name}));
                            });
                            $selectRes.multipleSelect('refresh');
                        }
                    });
                }
            });
            
            $('#restaurant_id').change(function (e) {
                if(resIndex != $('#restaurant_id').val()) {
                    resIndex = $('#restaurant_id').val();
                    $.ajax({
                        url: "{{route('dashboard_restaurant.get_sub_cat_by_res')}}",
                        method: "post",
                        async: false,
                        data: { 
                            _token: "{{csrf_token()}}",
                            res: $('#restaurant_id').val()
                        },
                        dataType: "json",
                        success: function (result) {
                            $selectSub.find('option').remove();
                            result.map((res, index) => {
                              $selectSub.append($("<option/>", {value: res.sub_category.id, text: res.sub_category.name}));
                            });
                            $selectSub.multipleSelect('refresh');
                        }
                    });
                }
            });
            
            $('#sub_category_id').change(function (e) {
                if(subIndex != $('#sub_category_id').val()) {
                    subIndex = $('#sub_category_id').val();
                    $.ajax({
                        url: "{{route('dashboard_restaurant.get_pro_cat_by_sub_res')}}",
                        method: "post",
                        async: false,
                        data: { 
                            _token: "{{csrf_token()}}",
                            res: $('#restaurant_id').val(),
                            sub: $('#sub_category_id').val()
                        },
                        dataType: "json",
                        success: function (result) {
                            console.log(result);
                            $selectPro.find('option').remove();
                            result.map((res, index) => {
                              $selectPro.append($("<option/>", {value: res.id, text: res.name}));
                            });
                            $selectPro.multipleSelect('refresh');
                        }
                    });
                }
            });
            
            if (isNaN(last_part) == false) {
                if (last_part != null) {
                    $('.title_info').html("تعديل");
                    Render_Data(last_part);
                    cityIndex = -1;
                    $('#city_id').trigger('change');
                    resIndex = -1;
                    $('#restaurant_id').trigger('change');
                    subIndex = -1;
                    $('#sub_ca').trigger('change');
                    Render_Data(last_part);
                }
            } else {
                $('.title_info').html("Create new");
                cityIndex = -1;
            } 
                
        });
        

        var Render_Data = function (id) {
            $.ajax({
                url: "{{route('dashboard_offers.get_data_by_id')}}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function (result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        var res = result.success;
                        console.log(res)
                        $('#city_id').multipleSelect('setSelects', [res.city.id]);
                        // $("#city_id").val(res.city.id).trigger('change');

                        //category_id
                        $('#category_id').multipleSelect('setSelects', [res.category.id]);

                        //sub_category_id
                        $('#sub_category_id').multipleSelect('setSelects', [res.sub_category.id]);

                        //restaurant_id
                        $('#restaurant_id').multipleSelect('setSelects', [res.restaurant.id]);

                        //products_id
                        $('#products_id').multipleSelect('setSelects', [res.products.id]);
                        
                        
                        
                        ImgURL = "{{ URL('/') }}/public/"+[res.image];
                        VideoURL = "{{ URL('/') }}/public/"+[res.video];
                        
                        var vdiehmtl = "";
                        vdiehmtl += '<video width="320" height="240" controls>';
                        vdiehmtl += '<source src="'+VideoURL+'" type="video/mp4">';
                        vdiehmtl += '</video>';
                        
                        $('#off_percentage').val([res.off_percentage]);
                        
                        $('#amount').val([res.amount]);
                        $('#ending_in_date').val([res.ending_in_date]);
                        $('#ending_in_time').val([res.ending_in_time]);
                        $("#CoverImageImg").prop("src", ImgURL);
                        $("#LoadVide").html(vdiehmtl);
                        

                        // $('.avatar_view').removeClass('d-none');
                        // $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);

                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{route('dashboard_offers.index')}}";
                    }
                }
            });
        };

    </script>
@endsection
