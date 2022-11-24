<?php

namespace App\Http\Controllers\Dashboard;

use App\Imports\InvoicesExport;
use App\Order;
use App\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Category;
use Maatwebsite\Excel\Facades\Excel;

class ClientsController extends Controller
{
    public function index()
    {
        $restaurant_id = Restaurant::get();
        return view('dashboard/clients.index',compact('restaurant_id'));
    }

    public function export(Request $request){
        $body = Order::
            select("id","name","phone","total","status","created_at","updated_at");

        if($request->from && $request->to){

            $from_d = parent::date_get($request->from,2).'-'.parent::date_get($request->from,0).'-'.parent::date_get($request->from,1);
            $to_d = parent::date_get($request->to,2).'-'.parent::date_get($request->to,0).'-'.parent::date_get($request->to,1);

            $from = date($from_d);
            $to = date($to_d);

            $body = $body->whereBetween('created_at', [$from, $to]);
        }

        $body = $body->get();
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
        return view('dashboard/clients.add_edit');
    }

    public function view_order($id = null)
    {
        $item = Order::where('id', '=', $id)->first();
        if ($item == null) {
            return redirect()->route('dashboard_clients.index');
        }
        return view('dashboard/clients.view_order',compact('item'));
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


        $cat = $request->cat;
        $status = $request->status;
        $totalData = Order::where('client_name', '!=', "")->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $posts = Order::
        Where('client_name', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->orderBy($order, $dir)
            ->get();

        if ($search != null) {
            $totalFiltered = Order::
            Where('client_name', 'LIKE', "%{$search}%")
                ->count();
        }


        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $items_pr = $post->Items;
                $Restaurant_r = "";
                $Products_r = "";
                if ($items_pr->count() != 0) {
                    foreach ($items_pr as $r) {
                        $Products = $r->Products;
                        $Productsname = $r->Products->name;
                        $Restaurant = $Products->Restaurant->name;
                        $Restaurant_r = $Restaurant_r . " <span class='badge badge-primary'>$Restaurant</span>";
                        $Products_r = $Products_r . " <span class='badge badge-dark'>$Productsname</span>";
                    }
                }

                $edit = route('dashboard_clients.view_order', ['id' => $post->id]);

                $nestedData['id'] = $post->order_id;
                $nestedData['phone'] = $post->phone;
                $nestedData['city'] = $post->City->name;
                $nestedData['total'] = $post->total;
                $nestedData['name'] = $post->client_name;
                $nestedData['orders1'] = $items_pr->count();
                $nestedData['count'] = $post->CountPrice();

                $nestedData['options'] = "<a class='btn btn-sm btn-primary' href='{$edit}' title='View' ><i class='fa fa-eye'></i> View</a>
                                          <a class='btn_delete_current btn btn-sm btn-danger' href='#' data-id='{$post->id}' title='Delete' ><i class='fa fa-trash'></i> Delete</a>";
                $data[] = $nestedData;
            }
        }
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
        return response()->json(['error' => __('language.msg.d')]);
    }


    public function post_data(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {

            $Post = Order::where('id', '=', $request->id)->first();
            $Post->name = $request->name;
            $Post->phone = $request->phone;
            $Post->phone_active = $request->phone_active;
            $Post->status = $request->status;
            $Post->update();

            return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_clients.index')]);
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
