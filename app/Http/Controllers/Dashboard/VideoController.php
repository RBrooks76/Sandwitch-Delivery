<?php

namespace App\Http\Controllers\Dashboard;

use App\Contents;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index(){
        return view('dashboard/video.index');
    }

    public function get_data_by_id(Request $request){
        $items = Contents::where("type","video")->first();
        return response()->json(['success'=>$items]);
    }

    public function post_data(Request $request){
        $Setting = Contents::where("type","video")->first();
        $validation = Validator::make($request->all(), $this->rules($Setting));
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($Setting == null){
                DB::transaction(function()
                {
                    $Setting = new Contents();
                    $Setting->type = "video";
                    $Setting->user_id = user()->id;
                    $Setting->summary = Input::get('summary');
                    $Setting->link = Input::get('link');
                    $Setting->avatar1 = parent::upladImage(Input::file('avatar'),'video_1');
                    $Setting->save();
                    if( !$Setting )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=> __('language.msg.s'),'same_page'=>'1','dashboard'=>'1']);
            }
            else{
                DB::transaction(function()
                {
                    $Setting = Contents::where("type","video")->first();
                    $Setting->summary = Input::get('summary');
                    $Setting->link = Input::get('link');
                    if(Input::hasFile('avatar')){
                        $Setting->avatar1 = parent::upladImage(Input::file('avatar'),'video_1');
                    }
                    $Setting->update();
                    if( !$Setting )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=>__('language.msg.m'),'same_page'=>'1','dashboard'=>'1']);
            }
        }
    }

    private function rules($edit = null,$pass = null){
        $x= [
            'summary' => 'required|min:3',
            'link' => 'required|min:3',
            'avatar' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
            $x['avatar'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG';
        }
        return $x;
    }

}
