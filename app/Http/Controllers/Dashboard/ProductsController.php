<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\City;
use App\Imports\InvoicesExport;
use App\Products;
use App\ProductsCategory;
use App\ProductsFeature;
use App\RestaurantCategory;
use App\SubCategory;
use App\User;
use App\UserResaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $sub_categories = SubCategory::orderBy('priority')->get();
        $restaurant_id = $request->restaurant_id;
        $all_products = [];
        $sub_priority = 1;
        foreach ($sub_categories as $category) {
            $masked_categories = ProductsCategory::where('sub_category_id', $category->id)->get()->pluck('products_id');
            $filtered = Products::where('restaurant_id', $restaurant_id)->whereIn('id', $masked_categories)->orderBy('priority')->get();
            if ($filtered->count() <= 0) continue;
            $category->priority = $sub_priority++;
            $row = (object)([
                'category' => $category,
                'products' => []
            ]);
            $priority = 1;
            foreach ($filtered as $item) {
                if (Auth::user()->role == '1'){
                $row->products[] = (object)([
                    'id' => $item->id,
                    'featured' => $item->active,
                    'sub_category' => $item->ProductsCategory->map(function ($item) {
                        return '<span class="badge badge-success">' . $item->SubCategory->name . '</span><br><br>';
                    })->reduce(function ($a, $b) {
                        return $a . $b;
                    }),
                    'description' => $item->summary,
                    'name' => $item->name,
                    'price' => $item->amount,
                    'avatar' => (URL('/') . $item->img()),
                    'options' => "&emsp;<a class='btn btn-sm btn-primary' href='" . route('dashboard_products.add_edit', ['id' => $item->id, 'restaurant_id' => $restaurant_id]) . "' title='Edit' ><span class='color_wi fa fa-edit'></span> Edit</a>
                    <a class='btn_delete_current btn btn-danger btn-sm' data-id='" . $item->id . "' title='Delete' ><span class='color_wi fa fa-trash'></span> Delete</a>",
                    'priority' => '<input type="number" data-id="' . $item->id . '" id="priority_' . $priority . '" class="form-control input-priority product-priority" style="width: 75px;" value="' . ($priority++) . '"/>'
                ]);
                }else{
                    $row->products[] = (object)([
                    'id' => $item->id,
                    'featured' => $item->active,
                    'sub_category' => $item->ProductsCategory->map(function ($item) {
                        return '<span class="badge badge-success">' . $item->SubCategory->name . '</span><br><br>';
                    })->reduce(function ($a, $b) {
                        return $a . $b;
                    }),
                    'description' => $item->summary,
                    'name' => $item->name,
                    'price' => $item->amount,
                    'avatar' => (URL('/') . $item->img()),
                    'options' => "&emsp;<a class='btn btn-sm btn-primary' href='" . route('dashboard_products.add_edit', ['id' => $item->id, 'restaurant_id' => $restaurant_id]) . "' title='Edit' ><span class='color_wi fa fa-edit'></span> Edit</a>",
                    'priority' => '<input type="number" data-id="' . $item->id . '" id="priority_' . $priority . '" class="form-control input-priority product-priority" style="width: 75px;" value="' . ($priority++) . '"/>'
                ]);
                }
            }
            $all_products[] = $row;
        }
        return view('dashboard/products.index', compact('sub_categories', 'all_products'));
    }

    public function export(Request $request)
    {
        $body = Products::with('ProductsCategory')->where("restaurant_id", $request->restaurant_id);

        if ($request->from && $request->to) {

            $from_d = parent::date_get($request->from, 2) . '-' . parent::date_get($request->from, 0) . '-' . parent::date_get($request->from, 1);
            $to_d = parent::date_get($request->to, 2) . '-' . parent::date_get($request->to, 0) . '-' . parent::date_get($request->to, 1);

            $from = date($from_d);
            $to = date($to_d);

            $body = $body->whereBetween('created_at', [$from, $to]);
        }

        $body = $body->get();
        $xls_data = [];
        foreach ($body as $key => $data) {
            $record = [];
            $record['name'] = $data['name'];
            $record['image'] = $data['avatar'];
            $record['info'] = $data['summary'];
            $record['status'] = $data['status'];
            $record['amount'] = $data['amount'];
            $productscategory = $data['ProductsCategory'];
            $record['category'] = "";
            foreach ($productscategory as $k => $sub) {
                $record['category'] .= $sub->subcategory->name;
            }
            $record['created'] = $data['created_at'];
            $record['updated'] = $data['updated_at'];
            $xls_data[] = $record;
        }
        $headers_collc = [
            'Product Name',
            'Product Image',
            'Product Info',
            'Status',
            'Price',
            'Menu category',
            'Created Date',
            'Updated Date',
        ];
        $export = new InvoicesExport([
            $headers_collc,
            $xls_data
        ]);
        return Excel::download($export, 'export' . time() . '.xlsx');
    }

    public function add_edit()
    {
        $sub_category_id = SubCategory::where("active", 1)->get();
        return view('dashboard/products.add_edit', compact('sub_category_id'));
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'avatar',
            3 => 'featured',
            4 => 'type',
            5 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $restaurant_id = $request->restaurant_id;
        $cat = $request->cat;

        $totalData = Products::Where('restaurant_id', $restaurant_id)->count();
        $totalFiltered = $totalData;

        $posts = Products::Where('restaurant_id', $restaurant_id)->where(function ($q) use ($cat) {
            if ($cat) {
                $q->whereHas("ProductsCategory", function ($q2) use ($cat) {
                    $q2->Where('sub_category_id', $cat);
                });
            }
        })->offset($start)->limit($limit)->orderBy('priority')->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        if ($search != null) {
            $totalFiltered = Products::Where('restaurant_id', $restaurant_id)->where(function ($q) use ($cat) {
                if ($cat) {
                    $q->whereHas("ProductsCategory", function ($q2) use ($cat) {
                        $q2->Where('category_id', $cat);
                    });
                }
            })->count();
        }

        $data = array();
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {
                $ava = path() . $post->img();

                $edit = route('dashboard_products.add_edit', ['id' => $post->id, 'restaurant_id' => Input::get('restaurant_id')]);

                $featured = '';
                if ($post->active == 1) {
                    $featured = 'checked';
                }

                $nestedData['featured'] = '<div class="material-switch pull-left">
                                                            <input data-id="' . $post->id . '" id="active_' . $post->id . '" class="btn_featured" type="checkbox" ' . $featured . '/>
                                                            <label for="active_' . $post->id . '" class="label-success"></label>
                                                        </div>';


                $add = "<label>
                        <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                    </label>";

                $SubCategory = "";
                if ($post->ProductsCategory->count() != 0) {
                    foreach ($post->ProductsCategory as $R) {
                        $re = $R->SubCategory->name;
                        $SubCategory = $SubCategory . " <span class='badge badge-success'>$re</span><br><br>";
                    }
                }

                $nestedData['id'] = $add;
                $nestedData['SubCategory'] = $SubCategory;
                $nestedData['desc'] = $post->summary;
                $nestedData['name'] = $post->name;
                $nestedData['price'] = $post->amount;
                $nestedData['avatar'] = "<img style='width: 50px;height: 50px;' src='{$ava}' class='img-circle img_data_tables'>";
                $nestedData['options'] = "&emsp;<a class='btn btn-sm btn-primary' href='{$edit}' title='Edit' ><span class='color_wi fa fa-edit'></span> Edit</a>
                                          <a class='btn_delete_current btn btn-danger btn-sm' data-id='{$post->id}' title='Delete' ><span class='color_wi fa fa-trash'></span> Delete</a>";
                $nestedData['priority'] = '<input type="number" data-id="' . $post->id . '" id="priority_' . $priority . '" class="form-control input-priority" style="width: 75px;" value="' . ($priority++) . '"/>';
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return response()->json($json_data);
    }

    function deleted_all(Request $request)
    {
        $array = $request->array;
        if ($array == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if (count($array) == 0) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        foreach ($array as $key => $value) {
            $Post = Products::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => __('language.msg.e')]);
            }
            $Post->delete();
        }
        return response()->json(['success' => __('language.msg.d')]);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = Products::with("ProductsFeature")->with("ProductsCategory")->where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        return response()->json(['success' => $Post]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = Products::where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post->delete();
        return response()->json(['error' => __('language.msg.d')]);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $validation = Validator::make($request->all(), $this->rules($edit));
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            if ($edit != null) {
                $Post = Products::where('id', '=', $request->id)->first();
                $Post->name = $request->name;
                $Post->summary = $request->summary;
                $Post->amount = $request->amount;
                if ($request->avatar != null) {
                    //Remove Old
                    if ($Post->avatar != 'posts/no.png') {
                        if (file_exists(public_path($Post->avatar))) {
                            unlink(public_path($Post->avatar));
                        }
                    }
                    //Save avatar
                    $Post->avatar = parent::upladImage($request->avatar, 'avatar');
                }
                $Post->update();

                $sub_category_id = $request->sub_category_id;
                if ($sub_category_id) {
                    if (count($sub_category_id) != 0) {
                        //Remove
                        $remove_all = ProductsCategory::where("products_id", $Post->id)->get();
                        foreach ($remove_all as $r) {
                            ProductsCategory::where("id", $r->id)->delete();
                        }

                        foreach ($sub_category_id as $key => $value) {
                            $save = new ProductsCategory();
                            $save->products_id = $Post->id;
                            $save->sub_category_id = $value;
                            $save->save();
                        }
                    }
                }

                $adddss_name = $request->adddss_name;
                $adddss_price = $request->adddss_price;
                $adddss_level = $request->adddss_level;
                if ($adddss_name && $adddss_price) {
                    $result = array_combine($adddss_name, $adddss_price);
                    $level_data = array_combine($adddss_name, $adddss_level);
                    if ($result) {
                        if (count($result) != 0) {
                            //remove all
                            $remove_all = ProductsFeature::where("products_id", $Post->id)->get();
                            if ($remove_all->count() != 0) {
                                foreach ($remove_all as $r) {
                                    ProductsFeature::where("id", $r->id)->delete();
                                }
                            }
                            //save new
                            $i = 1;
                            $level = $adddss_level[0];
                            foreach ($result as $key => $value) {
                                if ($level != $level_data[$key]) {
                                    $i++;
                                }
                                $save = new ProductsFeature();
                                $save->products_id = $Post->id;
                                $save->name = $key;
                                $save->amount = $value;
                                $save->level = $i;
                                $save->save();
                                $level = $level_data[$key];
                            }
                        }
                    }
                }


                if (!$Post) {
                    return response()->json(['error' => __('language.msg.e')]);
                }
            
                return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_products.index', ['restaurant_id' => $request->restaurant_id])]);
            } else {
                $av = parent::upladImage($request->avatar, 'avatar');

                $Post = new Products();
                $Post->name = $request->name;
                $Post->summary = $request->summary;
                $Post->amount = $request->amount;
                $Post->avatar = $av;
                $Post->status = 1;
                $Post->active = 1;
                $Post->restaurant_id = $request->restaurant_id;
                $Post->save();

                $sub_category_id = $request->sub_category_id;
                if ($sub_category_id) {
                    if (count($sub_category_id) != 0) {
                        foreach ($sub_category_id as $key => $value) {
                            $save = new ProductsCategory();
                            $save->products_id = $Post->id;
                            $save->sub_category_id = $value;
                            $save->save();
                        }
                    }
                }


                $adddss_name = $request->adddss_name;
                $adddss_price = $request->adddss_price;
                if ($adddss_name && $adddss_price) {
                    $result = array_combine($adddss_name, $adddss_price);
                    if ($result) {
                        if (count($result) != 0) {
                            foreach ($result as $key => $value) {
                                $save = new ProductsFeature();
                                $save->products_id = $Post->id;
                                $save->name = $key;
                                $save->amount = $value;
                                $save->save();
                            }
                        }
                    }
                }
                if (!$Post) {
                    return response()->json(['error' => __('language.msg.e')]);
                }
                return response()->json(['success' => __('language.msg.s'), 'dashboard' => '1', 'redirect' => route('dashboard_products.index', ['restaurant_id' => $request->restaurant_id])]);
            }
        }
    }

    private function rules($edit = null)
    {
        $x = [
            'name' => 'required|min:3|max:191',
            'sub_category_id' => 'required',
            'summary' => 'required|min:1',
            'avatar' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'amount' => 'required|numeric',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
            $x['avatar'] = 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG';
        }
        return $x;
    }

    function featured(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $user = Products::where('id', '=', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($user->active == 1) {
            $user->active = 0;
            $user->update();
            return response()->json(['error' => __('table.r-choice')]);
        } else {
            $user->active = 1;
            $user->update();
            return response()->json(['success' => __('table.choice')]);
        }
    }

    public function priority(Request $request)
    {
        $id = $request->id;
        $priority = max($request->priority, 1);
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $product = Products::where('id', $id)->first();
        if ($product == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }

        $items = Products::where('id', '<>', $id)->where('restaurant_id', $product->restaurant_id)->orderBy('priority')->get();
        foreach ($items as $i => $item) $item->update(['priority' => $i + 1]);

        $current = -1;
        $total = 1;
        foreach ($items as $item) {
            if ($item->priority >= $priority && $current == -1) {
                $current = $total;
                $total++;
            }
            $item->update(['priority' => $total]);
            $total++;
        }
        if ($current == -1) $current = $total;
        $product->update(['priority' => $current]);
        return response()->json(['success' => 'Update Priority']);
    }

    public function updateActive(Request $request, $id)
    {
        $product = Products::where('id', $id)->first();
        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $product->update(['active' => $request->active]);

        return response()->json(['success' => true]);
    }
}
