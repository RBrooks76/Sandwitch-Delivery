@extends('dashboard.layouts.app')

@section('title')
    Products
@endsection

@section('create_btn'){{ route('dashboard_products.index', ['id' => null, 'restaurant_id' => app('request')->input('restaurant_id')]) }}@endsection
    @section('create_btn_btn') Close @endsection

@section('content')

    <form class="ajaxForm users" enctype="multipart/form-data" data-name="users" action="{{ route('dashboard_products.post_data') }}" method="post">
        {{ csrf_field() }}

        <input id="id" name="id" class="cls" type="hidden">
        <input id="restaurant_id" name="restaurant_id" class="cls" value="{{ app('request')->input('restaurant_id') }}" type="hidden">

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Sub Category</label>
                        <select multiple="multiple" name="sub_category_id[]" id="sub_category_id" class="filter-multi">
                            @if ($sub_category_id->count() != 0)
                                @foreach ($sub_category_id as $item)
                                    <option value="{{ $item->id }}" id="category_id_select_2_{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('dashboard_sub_category.add_edit') }}" target="_blank" class="btn btn-primary" style="transform: translateY(35px);">
                            <i class="fe fe-plus mr-2"></i>Add Sub Category
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name">
                </div>

                <div class="form-group">
                    <label class="form-label">Product Description</label>
                    <textarea class="form-control sumernote" id="summary" rows="5" name="summary" placeholder="Enter Description here ..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter Product Price">
                </div>

                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="mb-0 card-title">Product Image</h3>
                        </div>
                        <div class="card-body">
                            <input type="file" class="dropify" id="avatar" name="avatar" data-height="300" />
                        </div>
                    </div>
                </div><!-- COL END -->

                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="form-group field_wrapper">
                            <label>Add Feature</label>
                            <div id="add_ons">
                                <div id="add_ons_1">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label for="adddss_name">Add On's Name</label>
                                            <input type="text" class="cls form-control" id="adddss_name_1" placeholder="Enter name feature">
                                        </div>
                                        <div class="col-md-6 col-12"></div>
                                        <div class="form-group col-6">
                                            <a href="javascript:void(0)" class="btn btn-primary add_button" id="btn_add_1" name="1" title="Add Feature"><i class="fa fa-plus"></i>
                                                Add</a>
                                        </div>
                                        <div class="form-group col-6 text-right">
                                            <a href="javascript:void(0)" class="btn btn-danger add_button" id="btn_del_1" title="Delete all"><i class="fa fa-trash"></i> Delete all</a>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="rows" id="d_sizes_1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 mt-4 text-right">
                                <a href="javascript:void(0)" class="btn btn-primary add_button" id="btn_add_add" title="Add Add On's"><i class="fa fa-plus"></i> Add Add On's </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="card">
            <div class="card-footer">
                <button type="submit" class="btn btn-success mt-1">+ Add Products</button>
            </div>
        </div>

    </form>
@endsection


@section('js')
    <script type="text/javascript">
        var isizes = 1;
        var dsizes = 1;
        $(document).ready(function() {

            "use strict";
            //Code here.

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var last = last_part.split("?")[0];

            var name_form = $('.ajaxForm').data('name');

            if (isNaN(last) == false) {
                if (last != null) {
                    $('.title_info').html("Edit");
                    Render_Data(last);
                }
            } else {
                $('.title_info').html("Create new");
            }

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id).remove();
            });


            $('#btn_add_1').click(function(e) {
                isizes++;
                var adddss_name = $('#adddss_name_1').val();
                // var adddss_price = $('#adddss_price').val();
                var level = e.target.name;
                var r = '<div id="row' + isizes + '" class="row">\
                                <input type="hidden" name="adddss_level[]" value="' + level + '"/>\
                                <div class="form-group col-sm-6 col-12">\
                                    <label for="adddss_name">Name</label>\
                                    <input type="text" class="cls form-control" name="adddss_name[]" value="' + adddss_name + '">\
                                </div>\
                                <div class="form-group col-sm-5 col-8">\
                                    <label for="adddss_price">Price</label>\
                                    <input type="number" class="cls form-control" name="adddss_price[]" value="' + 10 + '">\
                                </div>\
                                <div class="form-group col-sm-1 col-4 text-right mt-auto mb-4"><button type="button" name="remove" id="' + isizes +
                    '" class="btn-sm btn btn-danger btn_remove"><i class="fa fa-trash"></i></button></div></div>';
                $("#d_sizes_1").append(r);
                $('#adddss_name_1').val('');
                // $('#adddss_price').val('');
                //$('#d_sizes').append('<tr id="row' + isizes + '" class="dynamic-added"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+isizes+'" class="btn btn-danger btn_remove">X</button></td></tr>');
            });

            $('#btn_del_1').click(function() {
                $("#d_sizes_1").html("");
            });

            $('#btn_add_add').click(function() {
                dsizes++;
                var d = `<div id="add_ons_${dsizes}" class="mt-5">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label for="adddss_name">Add On's Name</label>
                                        <input type="text" class="cls form-control" id="adddss_name_${dsizes}" placeholder="Enter name feature">
                                    </div>
                                    <div class="col-md-6 col-12"></div>
                                    <div class="form-group col-6">
                                        <a href="javascript:void(0)" class="btn btn-primary add_button" id="btn_add_${dsizes}" name="${dsizes}" title="Add Feature"><i class="fa fa-plus"></i> Add</a>
                                    </div>
                                    <div class="form-group col-6 text-right">
                                        <a href="javascript:void(0)" class="btn btn-danger add_button" id="btn_del_${dsizes}" title="Delete all"><i class="fa fa-trash"></i> Delete all</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="rows" id="d_sizes_${dsizes}">
                                </div>
                            </div>`;
                $('#add_ons').append(d);
                AddEvent(dsizes);
            });
        });

        var Render_Data = function(id) {
            $.ajax({
                url: "{{ route('dashboard_products.get_data_by_id') }}",
                method: "get",
                data: {
                    "id": id,
                },
                dataType: "json",
                success: function(result) {
                    if (result.success != null) {
                        $('#id').val(result.success.id);
                        $('#amount').val(result.success.amount);
                        $('#summary').summernote("code", result.success.summary);
                        $('#name').val(result.success.name);
                        $('.avatar_view').removeClass('d-none');
                        $('.avatar_view').attr('src', geturlphoto() + result.success.avatar);

                        //category_id
                        var arry1 = [];
                        var count_cat = result.success.products_category;
                        if (count_cat.length != 0) {
                            for (var i = 0; i < count_cat.length; i++) {
                                if (count_cat[i]) {
                                    var id = count_cat[i].sub_category_id;
                                    arry1.push(id);
                                }
                            }
                        }
                        $('#sub_category_id').multipleSelect('setSelects', arry1);
                        $("#sub_category_id").val(arry1);

                        //ProductsFeature
                        var fea = result.success.products_feature;
                        if (fea.length != 0) {
                            var level = fea[0].level;
                            for (var j = 0; j < fea.length; j++) {
                                isizes++;
                                var r = "";
                                r = '<div id="row' + isizes + '" class="row">\
                                                <input type="hidden" name="adddss_level[]" value=". + fea[j].level + ."/>\
                                                <div class="form-group col-sm-6 col-12">\
                                                    <label for="adddss_name">Name</label>\
                                                    <input type="text" class="cls form-control" name="adddss_name[]" value="' + fea[j].name + '">\
                                                </div>\
                                                <div class="form-group col-sm-5 col-8">\
                                                    <label for="adddss_price">Price</label>\
                                                    <input type="number" class="cls form-control" name="adddss_price[]" value="' + fea[j].amount + '">\
                                                </div>\
                                                <div class="form-group col-sm-1 col-4 text-right mt-auto mb-4"><button type="button" name="remove" id="' + isizes +
                                    '" class="btn-sm btn btn-danger btn_remove"><i class="fa fa-trash"></i></button></div></div>';
                                if (level != fea[j].level) {
                                    dsizes++;
                                    r = `<div id="add_ons_${dsizes}" class="mt-5">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12">
                                                <label for="adddss_name">Add On's Name</label>
                                                <input type="text" class="cls form-control" id="adddss_name_${dsizes}" placeholder="Enter name feature">
                                            </div>
                                            <div class="col-md-6 col-12"></div>
                                            <div class="form-group col-6">
                                                <a href="javascript:void(0)" class="btn btn-primary add_button" id="btn_add_${dsizes}" name="${dsizes}" title="Add Feature"><i class="fa fa-plus"></i> Add</a>
                                            </div>
                                            <div class="form-group col-6 text-right">
                                                <a href="javascript:void(0)" class="btn btn-danger add_button" id="btn_del_${dsizes}" title="Delete all"><i class="fa fa-trash"></i> Delete all</a>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="rows" id="d_sizes_${dsizes}">${r}</div></div>`;
                                    $(`#add_ons`).append(r);
                                    AddEvent(dsizes);
                                } else {
                                    $(`#d_sizes_${dsizes}`).append(r);
                                }
                                level = fea[j].level;
                                //$('#d_sizes').append('<tr id="row'+isizes+'" class="dynamic-added"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+isizes+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                            }
                        }

                    } else {
                        toastr.error('لا يوحد بيانات', 'العمليات');
                        window.location.href = "{{ route('dashboard_products.index') }}";
                    }
                }
            });
        };

        var AddEvent = function(id) {
            $(`#btn_add_${id}`).click(function(e) {
                isizes++;
                var adddss_name = $(`#adddss_name_${id}`).val();
                var level = e.target.name;
                // var adddss_price = $('#adddss_price').val();
                var r = '<div id="row' + isizes + '" class="row">\
                                <input type="hidden" name="adddss_level[]" value="' + level + '"/>\
                                <div class="form-group col-sm-6 col-12">\
                                    <label for="adddss_name">Name</label>\
                                    <input type="text" class="cls form-control" name="adddss_name[]" value="' + adddss_name + '">\
                                </div>\
                                <div class="form-group col-sm-5 col-8">\
                                    <label for="adddss_price">Price</label>\
                                    <input type="number" class="cls form-control" name="adddss_price[]" value="' + 10 + '">\
                                </div>\
                                <div class="form-group col-sm-1 col-4 text-right mt-auto mb-4"><button type="button" name="remove" id="' + isizes +
                    '" class="btn-sm btn btn-danger btn_remove"><i class="fa fa-trash"></i></button></div></div>';
                $(`#d_sizes_${id}`).append(r);
                $(`#adddss_name_${id}`).val('');
                // $('#adddss_price').val('');
                //$('#d_sizes').append('<tr id="row'+isizes+'" class="dynamic-added"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+isizes+'" class="btn btn-danger btn_remove">X</button></td></tr>');
            });
            $(`#btn_del_${id}`).click(function() {
                $(`#add_ons_${id}`).remove();
            });
        }

    </script>
@endsection
