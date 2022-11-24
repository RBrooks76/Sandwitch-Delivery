@extends('dashboard.layouts.app')

@section('title')
    Products
@endsection

@section('css')
@endsection

@section('create_btn'){{ route('dashboard_products.add_edit', ['id' => null, 'restaurant_id' => app('request')->input('restaurant_id')]) }}@endsection
    @section('create_btn_btn') Create new @endsection

@section('content')
    <style>
        .active-cookies {
            width: 100%;
            text-align: center;
            color: white;
            background-color: #15be15b3;
            font-weight: bold;
            padding: 0.2rem;
            border-radius: 0.2rem;
        }

    </style>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="post" action="{{ route('dashboard_products.export') }}">
                            <input type="hidden" value="{{ app('request')->input('restaurant_id') }}" name="restaurant_id">
                            {{ csrf_field() }}
                            <div class="filter-custom">
                                <div class="row">
                                    <div class="col-lg-1">
                                        <button type="button" class="btn btn-sm btn-dark ajax_delete_all">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input name="from" class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div>
                                            </div>
                                            <input name="to" class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-info" type="submit"><i class="fe fe-download mr-2"></i>
                                            Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('dashboard_comments.index', ['id' => null, 'restaurant_id' => app('request')->input('restaurant_id')]) }}" class="btn btn-info"
                            style="float: right">
                            <i class="fa fa-commenting-o"></i>
                            Comments
                        </a>

                        <br />

                        <div class="filter-custom">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select class="form-control ajax_cat select2-show-search" data-placeholder="Choose one (with searchbox)">
                                            <optgroup label="Choose sub">
                                                @if ($sub_categories->count() != 0)
                                                    @foreach ($sub_categories as $item)
                                                        <option value="{{ $item->id }}" id="category_id_select_2_{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered text-nowrap w-100 dataTable no-footer data_Table" id="data_Table">
                            <thead>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Product Info</th>
                                <th>Active</th>
                                <th>Price</th>
                                <th>Menu Category</th>
                                <th>Option</th>
                                <th>Priority</th>
                            </thead>
                            <tbody>
                                @foreach ($all_products as $item)
                                    <tr>
                                        <td colspan="3">
                                            <div style="font-weight: bold;">MAIN CATEGORY</div>
                                        </td>
                                        <td colspan="2">
                                            <div class="active-cookies">{{ $item->category->name }}</div>
                                        </td>
                                        <td colspan="2">
                                            <div class="material-switch" style="margin-left: 1rem;">
                                                <input type="checkbox" class="btn_featured category_active" data-id="{{ $item->category->id }}"
                                                    id="category_active_{{ $item->category->id }}" {{ $item->category->active ? 'checked' : '' }} /> <label
                                                    for="category_active_{{ $item->category->id }}" class="label-success"></label>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control input-priority category-priority" data-id="{{ $item->category->id }}"
                                                style="width: 75px; font-weight: bold; font-size: 1.2rem; text-align: center;" value="{{ $item->category->priority }}" />
                                        </td>
                                    </tr>
                                    @foreach ($item->products as $product)
                                        <tr>
                                            <td>
                                                <img style="width: 50px;height: 50px;" src="{{ $product->avatar }}" class="img-circle img_data_tables" />
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td><?php echo $product->description; ?></td>
                                            <td>
                                                <div class="material-switch">
                                                    <input type="checkbox" class="btn_featured product_active" {{ $product->featured ? 'checked' : '' }}
                                                        data-id="{{ $product->id }}" id="product_active_{{ $product->id }}" />
                                                    <label for="product_active_{{ $product->id }}" class="label-success"></label>
                                                </div>
                                            </td>
                                            <td>{{ $product->price }}</td>
                                            <td><?php echo $product->sub_category; ?></td>
                                            <td><?php echo $product->options; ?></td>
                                            <td><?php echo $product->priority; ?></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn_deleted').on("click", function() {
                var id = $('#iddel').val();
                $.ajax({
                    url: "{{ route('dashboard_products.deleted') }}",
                    method: "get",
                    data: {
                        "id": id,
                    },
                    dataType: "json",
                    success: function(result) {
                        toastr.error(result.error);
                        $('.modal').modal('hide');
                        window.location.reload();
                    }
                });
            });
            $(document).on('click', '.btn_delete_current', function() {
                var id = $(this).data("id");
                $('#ModDelete').modal('show');
                $('#iddel').val(id);
                if (id) {
                    $('#data_Table tbody tr').css('background', 'transparent');
                    $('#data_Table tbody #' + id).css('background', 'hsla(64, 100%, 50%, 0.36)');
                }
            });
            $(document).on('change', '.category-priority', function() {
                const priority = $(this).val();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('dashboard_sub_category.priority') }}",
                    method: "get",
                    data: {
                        id,
                        priority,
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
            $(document).on('change', '.input-priority', function(e) {
                const id = $(this).attr("data-id");
                const priority = $(this).val();
                $.ajax({
                    url: "{{ route('dashboard_products.priority') }}",
                    method: "get",
                    data: {
                        id,
                        priority,
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
            $(document).on('change', '.category_active', function() {
                const id = $(this).attr('data-id');
                const active = $(this).prop('checked') ? 1 : 0;
                $.ajax({
                    url: "{{ URL('dashboard/sub_category/active') }}" + `/${id}`,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        active
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
            $(document).on('change', '.product_active', function() {
                const id = $(this).attr('data-id');
                const active = $(this).prop('checked') ? 1 : 0;
                $.ajax({
                    url: "{{ URL('dashboard/products/active') }}" + `/${id}`,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        active
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.error != null) {
                            toastr.error(result.error);
                        } else {
                            toastr.success(result.success);
                        }
                        window.location.reload();
                    }
                });
            });
        });

    </script>


@endsection
