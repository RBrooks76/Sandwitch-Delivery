<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\City;
use App\UserRestaurant;

class UserrestaurantController extends Controller
{
    public function index()
    {
        return view('dashboard/userrestaurant.index');
    }

    public function add_edit()
    {
        $city_id = City::get();
        return view('dashboard/userrestaurant.add_edit', compact('city_id'));
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            4 => 'city_id',
            2 => 'phone',
            5 => 'email',
            3 => 'username',
            6 => 'website'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $totalData = UserRestaurant::
        where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            }
        })->count();
        $totalFiltered = $totalData;

        $posts = UserRestaurant::with('city')->
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

                $edit = route('dashboard_ownrestaurant.add_edit', ['id' => $post->id]);

                $add = "<label>
                        <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                    </label>";

                $nestedData['id'] = $add;
                $nestedData['name'] = $post->name;
                $nestedData['phone'] = $post->phone;
                $nestedData['username'] = $post->username;
                $nestedData['city_id'] = $post->city->name;
                $nestedData['email'] = $post->email;
                $nestedData['website'] = $post->website;
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
        $userrestaurant = UserRestaurant::with('city')->where('id', '=', $id)->first();
        if ($userrestaurant == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        return response()->json(['success' => $userrestaurant]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $userrestaurant = UserRestaurant::where('id', $id)->first();
        if ($userrestaurant == null) {
            return response()->json(['error' => 'Error Happen']);
        }

        $userrestaurant->delete();
        return response()->json(['error' => 'Delete Done']);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $validation = Validator::make($request->all(), $this->rules($edit));
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            $userrestaurant = UserRestaurant::where('id', '=', $request->id)->first();
            $userrestaurant->name = $request->name;
            $userrestaurant->update();
            if (!$userrestaurant) {
                return response()->json(['error' => 'Error Happen']);
            }
            return response()->json(['success' => 'Updated Done', 'dashboard' => '1', 'redirect' => route('dashboard_ownrestaurant.index')]);
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
            $Post = UserRestaurant::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => 'Error Happen']);
            }
            $Post->delete();
        }
        return response()->json(['success' => 'Delete Done']);
    }

}
