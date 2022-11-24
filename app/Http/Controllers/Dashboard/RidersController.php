<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\City;
use App\UserRide;

class RidersController extends Controller
{
    public function index()
    {
        return view('dashboard/riders.index');
    }

    public function add_edit()
    {
        $city_id = City::get();
        return view('dashboard/riders.add_edit', compact('city_id'));
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'phone',
            3 => 'country',
            4 => 'city',
            5 => 'email',
            6 => 'license'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $totalData = UserRide::
        where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            }
        })->count();
        $totalFiltered = $totalData;

        $posts = UserRide::with('city')->
        where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            }
        })
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->orderBy($order, $dir)
            ->get();

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $edit = route('dashboard_riders.add_edit', ['id' => $post->id]);

                $add = "<label>
                        <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                    </label>";

                $nestedData['id'] = $add;
                $nestedData['name'] = $post->name;
                $nestedData['phone'] = $post->phone;
                $nestedData['country'] = $post->country;
                $nestedData['city'] = $post->city->name;
                $nestedData['email'] = $post->email;
                $nestedData['license'] = $post->license == 1 ? "Car driving license" : "Bike driving license";
                $nestedData['options'] = "&emsp;<a class='btn btn-success btn-sm' href='{$edit}' title='تعديل' ><span class='color_wi fa fa-edit'></span></a>
                                         <a class='btn_delete_current btn btn-danger btn-sm' data-id='{$post->id}' title='حذف' ><span class='color_wi fa fa-trash'></span></a>";
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
            return response()->json(['error' => 'Error Happen']);
        }
        $rider = UserRide::with('city')->where('id', '=', $id)->first();
        if ($rider == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        return response()->json(['success' => $rider]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $rider = UserRide::where('id', $id)->first();
        if ($rider == null) {
            return response()->json(['error' => 'Error Happen']);
        }

        $rider->delete();
        return response()->json(['error' => 'Delete Done']);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $validation = Validator::make($request->all(), $this->rules($edit));
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            $rider = UserRide::where('id', '=', $request->id)->first();
            $rider->name = $request->name;
            $rider->update();
            if (!$rider) {
                return response()->json(['error' => 'Error Happen']);
            }
            return response()->json(['success' => 'Updated Done', 'dashboard' => '1', 'redirect' => route('dashboard_riders.index')]);
        }
    }

    private function rules($edit = null, $pass = null)
    {
        $x = [
            'name' => 'required|min:1|max:191',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
        }

        return $x;
    }

    function deleted_all(Request $request)
    {
        $array = $request->array;
        if ($array == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        if (count($array) == 0) {
            return response()->json(['error' => 'Error Happen']);
        }
        foreach ($array as $key => $value){
            $Post = UserRide::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => 'Error Happen']);
            }
            $Post->delete();
        }
        return response()->json(['success' => 'Delete Done']);
    }

}
