<?php

namespace App\Http\Controllers\Dashboard;

use App\Contents;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class JoinUSController extends Controller
{
    public function index(){
        return view('dashboard/join_us.index');
    }

    public function get_data_by_id(Request $request){
        $items = Contents::where("type","join_us")->first();
        return response()->json(['success'=>$items]);
    }

    public function post_data(Request $request){
        $Setting = Contents::where("type","join_us")->first();
        $validation = Validator::make($request->all(), $this->rules($Setting));
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($Setting == null){
                $Setting = new Contents();
                $Setting->type = "join_us";
                $Setting->user_id = user()->id;
                $Setting->summary = $request->summary;
                $Setting->summary1 = $request->summary1;
                $Setting->avatar1 = parent::upladImage($request->avatar,'join_us_1');
                $Setting->avatar2 = parent::upladImage($request->avatar1,'join_us_1');
                $Setting->save();
                if( !$Setting )
                {
                    return response()->json(['error'=> __('language.msg.e')]);
                }
                return response()->json(['success'=> __('language.msg.s'),'same_page'=>'1','dashboard'=>'1']);
            }
            else{
                $Setting = Contents::where("type","join_us")->first();
                $Setting->summary = $request->summary;
                $Setting->summary1 = $request->summary1;
                if($request->avatar){
                    $Setting->avatar1 = parent::upladImage($request->avatar,'join_us_1');
                }
                if($request->avatar1){
                    $Setting->avatar2 = parent::upladImage($request->avatar1,'join_us_1');
                }
                $Setting->update();
                if( !$Setting )
                {
                    return response()->json(['error'=> __('language.msg.e')]);
                }
                return response()->json(['success'=>__('language.msg.m'),'same_page'=>'1','dashboard'=>'1']);
            }
        }
    }

    private function rules($edit = null,$pass = null){
        $x= [
            'summary' => 'required|min:3',
            'summary1' => 'required|min:3',
            'avatar' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG',
            'avatar1' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
            $x['avatar1'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG';
            $x['avatar'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG';
        }
        return $x;
    }

}
