<?php

namespace App\Http\Controllers\Dashboard;

use App\EmailSetting;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class EmailSettingController extends Controller
{
    public function index(){
        return view('dashboard/email_setting.index');
    }

    public function get_data_by_id(Request $request){
        $items = EmailSetting::first();
        return response()->json(['success'=>$items]);
    }

    public function post_data(Request $request){
        $Setting = EmailSetting::first();
        $validation = Validator::make($request->all(), $this->rules($Setting),$this->languags());
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($Setting == null){
                DB::transaction(function()
                {
                    $Setting = new EmailSetting();
                    $Setting->password = Input::get('password');
                    $Setting->email = Input::get('email');
                    $Setting->driver = Input::get('driver');
                    $Setting->host = Input::get('host');
                    $Setting->port = Input::get('port');
                    $Setting->encrption = Input::get('encrption');
                    if (Input::get('active') == "on") {
                        $Setting->active = true;
                    } else {
                        $Setting->active = false;
                    }
                    $Setting->save();
                    $env_update = [
                        'MAIL_USERNAME'   =>$Setting->email,
                        'MAIL_PASSWORD'   =>$Setting->password,
                        'MAIL_DRIVER'   =>$Setting->driver,
                        'MAIL_HOST'   =>$Setting->host,
                        'MAIL_PORT'   =>$Setting->port,
                        'MAIL_ENCRYPTION'   =>$Setting->encrption,
                    ];
                    parent::changeEnv($env_update);
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
                    $Setting = EmailSetting::first();
                    $Setting->password = Input::get('password');
                    $Setting->email = Input::get('email');
                    $Setting->driver = Input::get('driver');
                    $Setting->host = Input::get('host');
                    $Setting->port = Input::get('port');
                    $Setting->encrption = Input::get('encrption');
                    if (Input::get('active') == "on") {
                        $Setting->active = true;
                    } else {
                        $Setting->active = false;
                    }
                    $Setting->update();
                    $env_update = [
                        'MAIL_USERNAME'   =>$Setting->email,
                        'MAIL_PASSWORD'   =>$Setting->password,
                        'MAIL_DRIVER'   =>$Setting->driver,
                        'MAIL_HOST'   =>$Setting->host,
                        'MAIL_PORT'   =>$Setting->port,
                        'MAIL_ENCRYPTION'   =>$Setting->encrption,
                    ];
                    parent::changeEnv($env_update);
                    if( !$Setting )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=>__('language.msg.m'),'same_page'=>'1','dashboard'=>'1']);
            }
        }
    }

    private function rules($edit = null){
        $x= [
            'email' => 'nullable|string|email',
            'password' => 'nullable|string',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
        }
        return $x;
    }

    private function languags(){
        if(app()->getLocale() == "ar"){
            return [

            ];
        }
        else{
            return [];
        }
    }

}
