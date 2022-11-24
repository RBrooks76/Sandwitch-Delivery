<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        return view('dashboard/coupons.index');
    }

    public function add_edit()
    {
        return view('dashboard/coupons.add_edit');
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

        $totalData = Coupon::where(function ($q) use ($search) {
            if ($search) {
                $q->Where('code', 'LIKE', "%{$search}%");
            }
        })->count();
        $totalFiltered = $totalData;

        $posts = Coupon::where(function ($q) use ($search) {
            if ($search) {
                $q->Where('code', 'LIKE', "%{$search}%");
            }
        })->offset($start)->limit($limit)->orderBy('id', "DESC")->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        if ($search != null) {
            $totalFiltered = Coupon::where(function ($q) use ($search) {
                if ($search) {
                    $q->Where('code', 'LIKE', "%{$search}%");
                }
            })->count();
        }

        $data = array();
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {
                $edit = URL('dashboard/coupons/add_edit', ['id' => $post->id, 'type' => $request->type]);
                $check1 = '';
                $active_or_no1 = 'Disable';
                
                if ($post->coupon_status == 1) {
                    $check1 = 'checked';
                    $active_or_no1 = 'Enable';
                }
                
                $add1 = '<div class="material-switch pull-left"><input data-id="' . $post->id . '" id="active_' . $post->id . '" class="btn_confirm_email_current" type="checkbox" ' . $check1 . '/><label for="active_' . $post->id . '" class="label-success"></label></div>';

                
                $nestedData['code'] = $post->code;
                $nestedData['amount'] = $post->amount;
                $nestedData['expiry_date'] = $post->expiry_date;
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
    
    function confirm_email(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $City = Coupon::where('id', '=', $id)->first();
        if ($City == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        if ($City->coupon_status == 1) {
            $City->coupon_status = 0;
            $City->update();
            return response()->json(['error' => 'Not Active']);
        } else {
            $City->coupon_status = 1;
            $City->update();
            return response()->json(['success' => 'Active']);
        }
    }


    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $City = Coupon::where('id', '=', $id)->first();
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
        $City = Coupon::where('id', '=', $id)->first();
        if ($City == null) {
            return response()->json(['error' => 'Error Happen']);
        }

        $City->delete();
        return response()->json(['error' => 'Delete Done']);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        if ($edit == null) {
            $v = \Validator::make($request->all(),[
    			'code' => 'required|unique:coupons',
    		]);
    		
    		if ($v->fails()) {
                return response()->json(['errors' => $v->errors()]);
            }
    		
            $City = new Coupon();
            $City->code = $request->code;
            $City->amount = $request->amount;
            $City->expiry_date = $request->expiry_date;
            $City->save();
            if (!$City) {
                return response()->json(['error' => 'Error Happen']);
            }
            
            return response()->json(['success' => 'Created Done', 'dashboard' => '1', 'redirect' => URL('dashboard/coupons', ['id' => null, 'type' => $request->type])]);
        } else {
            $City = Coupon::where('id', '=', $request->id)->first();
            //$City->code = $request->code;
            $City->amount = $request->amount;
            $City->expiry_date = $request->expiry_date;
            $City->update();
            if (!$City) {
                return response()->json(['error' => 'Error Happen']);
            }
            return response()->json(['success' => 'Updated Done', 'dashboard' => '1', 'redirect' => URL('dashboard/coupons', ['id' => null, 'type' => $request->type])]);
        }
    }
}
