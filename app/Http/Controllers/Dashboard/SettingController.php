<?php

namespace App\Http\Controllers\Dashboard;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(){
        return view('dashboard/setting.index');
    }

    public function get_data_by_id(Request $request){
        $items = Setting::first();
        return response()->json(['success'=>$items]);
    }

    public function post_data(Request $request){
        $Setting = Setting::first();
        $validation = Validator::make($request->all(), $this->rules($Setting),$this->languags());
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($Setting == null){
                $Setting = new Setting();
                $Setting->name = $request->name;
                $Setting->google = $request->google;
                $Setting->apple = $request->apple;
                $Setting->currency = $request->currency;
                $Setting->summary = $request->summary;
                $Setting->email = $request->email;
                $Setting->address = $request->address;
                $Setting->phone = $request->phone;
                $Setting->CallCenterNumber = $request->CallCenterNumber;
                $Setting->avatar = parent::upladImage($request->avatar,'setting');
                $Setting->avatar1 = parent::upladImage($request->avatar1,'setting');
                $Setting->bunner = parent::upladImage($request->bunner,'setting');
                $Setting->fav = parent::upladImage($request->fav,'setting');
                $Setting->save();
                if( !$Setting )
                {
                    return response()->json(['error'=> __('language.msg.e')]);
                }
                return response()->json(['success'=> __('language.msg.s'),'same_page'=>'1','dashboard'=>'1']);
            }
            else{
                    $Setting = Setting::first();
                    $Setting->name = $request->name;
                    $Setting->summary = $request->summary;
                    $Setting->currency = $request->currency;
                    $Setting->email = $request->email;
                    $Setting->address = $request->address;
                    $Setting->phone = $request->phone;
                    if(isset($request->avatar)){
                        if($Setting->avatar != 'setting/no.png'){
                            if(file_exists(public_path($Setting->avatar))){
                                unlink(public_path($Setting->avatar));
                            }
                        }
                        $Setting->avatar = parent::upladImage($request->avatar,'setting');
                    }
                    if(isset($request->avatar1)){
                        if($Setting->avatar1 != 'setting/no.png'){
                            if(file_exists(public_path($Setting->avatar1))){
                                unlink(public_path($Setting->avatar1));
                            }
                        }
                        $Setting->avatar1 = parent::upladImage($request->avatar1,'setting');
                    }
                    if(isset($request->bunner)){
                        if($Setting->bunner != 'setting/no.png'){
                            if(file_exists(public_path($Setting->bunner))){
                                unlink(public_path($Setting->bunner));
                            }
                        }
                        $Setting->bunner = parent::upladImage($request->bunner,'setting');
                    }
                    if(isset($request->fav)){
                        if($Setting->fav != 'setting/no.png'){
                            if(file_exists(public_path($Setting->fav))){
                                unlink(public_path($Setting->fav));
                            }
                        }
                        $Setting->fav = parent::upladImage($request->fav,'setting');
                    }
                    $Setting->google = $request->google;
                    $Setting->apple = $request->apple;
                    $Setting->CallCenterNumber = $request->CallCenterNumber;
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
            'name' => 'required|min:3|regex:/^[ا-يa-zA-Z0-9]/',
            'avatar' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG',
            'summary' => 'required|string',
            'avatar1' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG',
            'bunner' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
            $x['avatar'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG';
            $x['avatar1'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG,svg,SVG';
            $x['bunner'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG';
            $x['fav'] ='nullable|mimes:png,jpg,jpeg,ico,PNG,JPG,JPEG,ICO';
        }
        return $x;
    }

    private function languags(){
        if(app()->getLocale() == "ar"){
            return [
                'keywords' => 'The keywords field is required.',
                'description ' => 'The description  field is required.',
                'name.required' => 'حقل الاسم مطلوب.',
                'name.regex' => 'حقل الاسم غير صحيح .',
                'fav.required' => 'حقل العلامة في تاب الموقع مطلوب.',
                'avatar.required' => 'حقل الصورة في الهيدر مطلوب.',
                'avatar1.required' => 'حقل الصورة في الفوتير مطلوب.',
                'dir.required' => 'حقل كود الغة مطلوب.',

            ];
        }
        else{
            return [];
        }
    }

}
