<?php

namespace App\Http\Controllers\Dashboard;

use App\City;
use App\Imports\InvoicesExport;
use App\Order;
use App\Restaurant;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Category;
use Maatwebsite\Excel\Facades\Excel;

class OrdersController extends Controller
{
    public function index()
    {
        $category_id = Category::where("active", 1)->get();
        $city = City::where("active", 1)->get();
        return view('dashboard/orders.index', compact('category_id', 'city'));
    }

    public function export(Request $request)
    {
        $body = Order::select("id", "client_name", "phone", "total", "status", "created_at", "updated_at");

        if ($request->from && $request->to) {

            $from_d = parent::date_get($request->from, 2) . '-' . parent::date_get($request->from, 0) . '-' . parent::date_get($request->from, 1);
            $to_d = parent::date_get($request->to, 2) . '-' . parent::date_get($request->to, 0) . '-' . parent::date_get($request->to, 1);

            $from = date($from_d);
            $to = date($to_d);

            $body = $body->whereBetween('created_at', [$from, $to]);
        }
        $city_ids = City::where("active", 1)->pluck('id');
        $restaurants = Restaurant::whereIn('restaurant_city', $city_ids)->pluck('id');
        $body = $body->whereIn('restaurant_id', $restaurants)->get();
        $headers_collc = [
            'order_id',
            'Name',
            'Phone',
            'Total',
            'Status',
            'Created Date',
            'Updated Date',
        ];
        $export = new InvoicesExport([
            $headers_collc,
            $body
        ]);
        return Excel::download($export, 'export' . time() . '.xlsx');
    }


    public function add_edit()
    {
        return view('dashboard/orders.add_edit');
    }

    public function view_order($id = null)
    {
        $item = Order::where('id', '=', $id)->first();
        if ($item == null) {
            return redirect()->route('dashboard_orders.index');
        }
        return view('dashboard/orders.view_order', compact('item'));
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
        $city_ids = City::where("active", 1)->pluck('id');
        $restaurants = Restaurant::whereIn('restaurant_city', $city_ids)->pluck('id');

        // $totalData = Order::whereIn('restaurant_id', $restaurants)->count();
        // $totalFiltered = $totalData;

        $cat = $request->cat;
        $status = $request->status;
        $city = $request->city;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $posts = Order::whereIn('restaurant_id', $restaurants)->Where('client_name', 'LIKE', "%{$search}%")->where(function ($q) use ($cat, $status, $city) {
            if ($cat) {
                //Products
                $q->whereHas("Items", function ($q2) use ($cat) {
                    $q2->whereHas("Products", function ($q2) use ($cat) {
                        $q2->Where('restaurant_id', $cat);
                    });
                });
            }
            if ($status) {
                $q->Where('status', $status);
            }
            if ($city) {
                $q->Where('city_id', $city);
            }
        });
        if ($limit) $posts = $posts->offset($start)->limit($limit);
        $posts = $posts->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        // if ($search != null) {
        $totalData = Order::whereIn('restaurant_id', $restaurants)->Where('client_name', 'LIKE', "%{$search}%")
            ->where(function ($q) use ($cat, $status, $city) {
                if ($cat) {
                    //Products
                    $q->whereHas("Items", function ($q2) use ($cat) {
                        $q2->whereHas("Products", function ($q2) use ($cat) {
                            $q2->Where('restaurant_id', $cat);
                        });
                    });
                }
                if ($status) {
                    $q->Where('status', $status);
                }
                if ($city) {
                    $q->Where('city_id', $city);
                }
            })
            ->count();
        // }


        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $items_pr = $post->Items;
                $Restaurant_r = array();
                $Products_r = "";
                $logo_a = "";
                $cat2 = "";
                if ($items_pr->count() != 0) {
                    foreach ($items_pr as $r) {
                        $Products = $r->Products;
                        $Productsname = $r->Products->name;

                        $Restaurant = $Products->Restaurant->User->name;
                        if (!in_array($Restaurant, $Restaurant_r)) {
                            array_push($Restaurant_r, $Restaurant);
                        }
                        //$Restaurant_r = $Restaurant_r . " $Restaurant";
                        $Products_r = $Products_r . " <span class='btn-sm btn btn-dark'>$Productsname</span>";
                        $logo_a = path() . $Products->Restaurant->user->avatar;
                        $cat = $Products->ProductsCategory;
                        foreach ($cat as $c) {
                            $e = $c->SubCategory->name;
                            $cat2 =  $e;
                        }
                    }
                }

                $logo = "<img src='$logo_a' style='width: 50px;height: 50px;'>";

                $edit = route('dashboard_orders.add_edit', ['id' => $post->id]);
                $view_order = route('dashboard_orders.view_order', ['id' => $post->id]);

                $nestedData['id'] = $post->id;
                $nestedData['logo'] = $logo;
                $nestedData['cat'] = $cat2;
                $nestedData['city'] = $post->City->name;
                $nestedData['phone'] = $post->phone;
                $nestedData['total'] = $post->total;
                $nestedData['date'] = $post->date();
                $nestedData['status'] = $post->status();
                $nestedData['name'] = $post->client_name;
                $nestedData['restaurant_r'] = implode(", ", $Restaurant_r);
                $nestedData['products_r'] = $Products_r;
                $nestedData['view_order'] = "<a class='btn btn-sm btn-secondary' href='{$view_order}' title='View' ><i class='fa fa-info-circle'></i> Edit Order</a>";
                $nestedData['options'] = "<a class='btn btn-sm btn-primary' href='{$edit}' title='Edit' ><i class='fa fa-edit'></i> Edit</a>
                                          <a class='btn_delete_current btn btn-sm btn-danger' href='#' data-id='{$post->id}' title='Delete' ><i class='fa fa-trash'></i> Delete</a>";
                $data[] = $nestedData;
            }
        }
        $totalFiltered = $totalData;
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = Order::where('id', '=', $id)->first();
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
        $Post = Order::where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post->delete();
        
        Notification::where("type", "Order")->where("connected_id", $id)->delete();
        return response()->json(['error' => __('language.msg.d')]);
    }


    public function post_data(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {

            $Post = Order::where('id', '=', Input::get('id'))->first();
            $Post->name = Input::get('name');
            $Post->phone = Input::get('phone');
            $Post->phone_active = Input::get('phone_active');
            $Post->status = Input::get('status');
            $Post->update();

            return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_orders.index')]);
        }
    }

    private function rules()
    {
        $x = [
            'name' => 'required|min:1|max:191',
            'phone' => 'required|min:1|max:191',
        ];
        return $x;
    }
}
