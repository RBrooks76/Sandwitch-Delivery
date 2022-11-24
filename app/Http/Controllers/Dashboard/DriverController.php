<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\MyDriver;
use App\DriverComment;
use App\Complaint;
use App\Order;
use App\City;

class DriverController extends Controller
{
    public function index()
    {
        return view('dashboard/Drivers.index');
    }
    
    public function rating(){
        return view('dashboard/Drivers.rating');
    }
    
    public function complaint(){
        return view('dashboard/Drivers.complaint');
    }

    public function add_edit()
    {
        $CityList = City::where("active", 1)->get();
        return view('dashboard/Drivers.add_edit', compact("CityList"));
    }
    
    public function get_rating_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'confirm_email',
            4 => 'id',
        );

        $type = $request->type;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $totalData = DriverComment::count();
        $totalFiltered = $totalData;

        $posts = DriverComment::offset($start)->limit($limit)->orderBy('id', "DESC")->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        $data = array();
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {
                $nestedData['Driver'] = $post->Drivers->name;
                $nestedData['Star'] = $post->star;
                $nestedData['Commants'] = '<div style="word-wrap: break-word; width: 200px">'.nl2br($post->comment).'</div>';
                $nestedData['Order'] = $post->order_id;
                $nestedData['By'] = $post->AppType;
                $data[] = $nestedData;
            }
        }
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ]);
    }
    
    public function get_complaint_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'confirm_email',
            4 => 'id',
        );

        $type = $request->type;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $totalData = Complaint::count();
        $totalFiltered = $totalData;

        $posts = Complaint::offset($start)->limit($limit)->orderBy('id', "DESC")->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        $data = array();
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {
                $Name = "";
                $OrderData = Order::find($post->order_id);
                if($post->complaint_for == 1){
                    $Name = $OrderData->restaurant->user->name." (".$OrderData->restaurant->user->phone.")";
                }else{
                    $Name = $OrderData->client_name." (".$OrderData->phone.")";
                }
                
                $nestedData['Driver'] = $post->Drivers->name;
                $nestedData['Order'] = $post->order_id;
                $nestedData['For'] = $Name;
                $data[] = $nestedData;
            }
        }
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ]);
    }

    public function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'confirm_email',
            4 => 'id',
        );

        $type = $request->type;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $totalData = MyDriver::where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            }
        })->count();
        $totalFiltered = $totalData;

        $posts = MyDriver::where(function ($q) use ($search) {
            if ($search) {
                $q->Where('email', 'LIKE', "%{$search}%");
            }
        })->offset($start)->limit($limit)->orderBy('id', "DESC")->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        if ($search != null) {
            $totalFiltered = MyDriver::where(function ($q) use ($search) {
                if ($search) {
                    $q->Where('mobile', 'LIKE', "%{$search}%");
                }
            })->count();
        }

        $data = array();
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {
                $edit = URL('dashboard/driver/add_edit', ['id' => $post->id, 'type' => $request->type]);
                $check1 = '';
                $active_or_no1 = 'Disable';
                
                if ($post->status == 1) {
                    $check1 = 'checked';
                    $active_or_no1 = 'Enable';
                }
                
                $add1 = '<div class="material-switch pull-left"><input data-id="' . $post->id . '" id="active_' . $post->id . '" class="btn_confirm_email_current" type="checkbox" ' . $check1 . '/><label for="active_' . $post->id . '" class="label-success"></label></div>';

                $CityName = City::find($post->city);
                $nestedData['name'] = $post->name;
                $nestedData['email'] = $post->email;
                $nestedData['mobile'] = $post->mobile;
                $nestedData['password'] = $post->password;
                $nestedData['username'] = $post->username;
                $nestedData['city'] = $CityName->name;
                $nestedData['confirm_email'] = $add1;
                $nestedData['options'] = "&emsp;<a class='btn btn-success btn-sm' href='{$edit}?' title='تعديل' ><span class='color_wi fa fa-edit'></span></a>
                                         <a class='btn_delete_current btn btn-danger btn-sm' data-id='{$post->id}' title='حذف' ><span class='color_wi fa fa-trash'></span></a>";
                $data[] = $nestedData;
            }
        }
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ]);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $City = MyDriver::where('id', '=', $id)->first();
        if ($City == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        return response()->json(['success' => $City]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $City = MyDriver::where('id', '=', $id)->first();
        if ($City == null) {
            return response()->json(['error' => 'Error Happen']);
        }

        $City->delete();
        return response()->json(['error' => 'Delete Done']);
    }

    function confirm_email(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $City = MyDriver::where('id', '=', $id)->first();
        if ($City == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        if ($City->status == 1) {
            $City->status = 0;
            $City->update();
            return response()->json(['error' => 'Not Active']);
        } else {
            $City->status = 1;
            $City->update();
            return response()->json(['success' => 'Active']);
        }
    }
    public function post_data(Request $request)
    {
        $edit = $request->id;
        if ($edit == null) {
            $v = \Validator::make($request->all(),[
    			'username' => 'required|unique:my_driver',
    		]);
    		
    		if ($v->fails()) {
                return response()->json(['errors' => $v->errors()]);
            }
    		
            $City = new MyDriver();
            $City->name = $request->name;
            $City->email = $request->email;
            $City->mobile = $request->mobile;
            $City->username = $request->username;
            $City->password = $request->password;
            $City->city = $request->city;
            $City->save();
            if (!$City) {
                return response()->json(['error' => 'Error Happen']);
            }
            
            return response()->json(['success' => 'Created Done', 'dashboard' => '1', 'redirect' => URL('dashboard/driver', ['id' => null, 'type' => $request->type])]);
        } else {
            $City = MyDriver::where('id', '=', $request->id)->first();
            $City->name = $request->name;
            $City->email = $request->email;
            $City->mobile = $request->mobile;
            $City->username = $request->username;
            $City->password = $request->password;
            $City->city = $request->city;
            $City->update();
            if (!$City) {
                return response()->json(['error' => 'Error Happen']);
            }
            return response()->json(['success' => 'Updated Done', 'dashboard' => '1', 'redirect' => URL('dashboard/driver', ['id' => null, 'type' => $request->type])]);
        }
    }
}
