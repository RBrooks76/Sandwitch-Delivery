<?php

namespace App\Http\Controllers\Dashboard;

use App\HPContactUS;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class HPContactUSController extends Controller
{
    public function index(){
        return view('dashboard/hp_contact_us.index');
    }

    public function get_data_by_id(Request $request){
        $items = HPContactUS::first();
        return response()->json(['success'=>$items]);
    }

    public function post_data(Request $request){
        $Setting = HPContactUS::first();
        $validation = Validator::make($request->all(), $this->rules($Setting),$this->languags());
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($Setting == null){
                DB::transaction(function()
                {
                    $Setting = new HPContactUS();
                    $Setting->whatsapp = Input::get('whatsapp');
                    $Setting->phone = Input::get('phone');
                    $Setting->email = Input::get('email');
                    $Setting->iframe = Input::get('iframe');
                    $Setting->address = Input::get('address');
                    $Setting->facebook = Input::get('facebook');
                    $Setting->twitter = Input::get('twitter');
                    $Setting->instagram = Input::get('instagram');
                    $Setting->pinterest = Input::get('pinterest');
                    $Setting->fax = Input::get('fax');
                    $Setting->hours = Input::get('hours');
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
                    $Setting = HPContactUS::first();
                    $Setting->whatsapp = Input::get('whatsapp');
                    $Setting->phone = Input::get('phone');
                    $Setting->email = Input::get('email');
                    $Setting->iframe = Input::get('iframe');
                    $Setting->address = Input::get('address');
                    $Setting->facebook = Input::get('facebook');
                    $Setting->twitter = Input::get('twitter');
                    $Setting->instagram = Input::get('instagram');
                    $Setting->pinterest = Input::get('pinterest');
                    $Setting->fax = Input::get('fax');
                    $Setting->hours = Input::get('hours');
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
            'whatsapp' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'instagram' => 'nullable|string',
            'twitter' => 'nullable|string',
            'facebook' => 'nullable|string',
            'pinterest' => 'nullable|string',
            'hours' => 'nullable|string',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
        }
        return $x;
    }

    private function languags(){
        if(app()->getLocale() == "ar"){
            return [
                'name.min' => 'حقل الاسم مطلوب على الاقل 3 حقول .',
                'address.required' => 'حقل العنوان مطلوب.',
                'address.regex' => 'حقل العنوان غير صحيح .',
                'address.min' => 'حقل العنوان مطلوب على الاقل 3 حقول .',
                'hours.required' => 'حقل عدد ساعات العمل مطلوب.',
                'hours.regex' => 'حقل عدد ساعات العمل غير صحيح .',
                'hours.min' => 'حقل عدد ساعات العمل مطلوب على الاقل 3 حقول .',
                'whatsapp.required' => 'حقل واتس اب مطلوب.',
                'whatsapp.regex' => 'حقل واتس اب غير صحيح .',
                'whatsapp.min' => 'حقل واتس اب مطلوب على الاقل 3 حقول .',
                'phone.required' => 'حقل الهاتف مطلوب.',
                'phone.regex' => 'حقل الهاتف غير صحيح .',
                'phone.min' => 'حقل الهاتف مطلوب على الاقل 3 حقول .',
                'email.required' => 'حقل البريد الالكتروني مطلوب.',
                'email.regex' => 'حقل البريد الالكتروني غير صحيح .',
                'email.min' => 'حقل البريد الالكتروني مطلوب على الاقل 3 حقول .',
                'iframe.required' => 'حقل جوجل ماب مطلوب.',
                'iframe.regex' => 'حقل جوجل ماب غير صحيح .',
                'iframe.min' => 'حقل جوجل ماب مطلوب على الاقل 3 حقول .',
            ];
        }
        else{
            return [];
        }
    }

}
