<?php

namespace App\Http\Controllers\Dashboard;

use App\Contact_pageTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class Contact_pageTranslateController extends Controller
{
    function get_data_by_id(Request $request){
        $id = $request->id;
        $hp_contents_id = $request->hp_contents_id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $SubScriptions = Contact_pageTranslate::where('hp_contents_id' ,'=',$hp_contents_id)
            ->where('language_id' ,'=',$id)->first();
        if($SubScriptions == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        return response()->json(['success'=>$SubScriptions]);
    }

    public function post_data(Request $request){
        $edit = $request->id;
        $validation = Validator::make($request->all(), $this->rules($edit),$this->languags());
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            $check = Contact_pageTranslate::
            where('id' ,'=',Input::get('id'))
                ->where('hp_contents_id' ,'=',Input::get('hp_contents_id'))
                ->where('language_id' ,'=',Input::get('language_id'))
                ->first();
            if($check != null){
                DB::transaction(function()
                {
                    $Contact_pageTranslate = Contact_pageTranslate::where('id' ,'=',Input::get('id'))->first();
                    $Contact_pageTranslate->name = Input::get('name');
                    $Contact_pageTranslate->summary = Input::get('summary');
                    $Contact_pageTranslate->language_id = Input::get('language_id');
                    $Contact_pageTranslate->hp_contents_id = Input::get('hp_contents_id');
                    $Contact_pageTranslate->update();
                    if( !$Contact_pageTranslate )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=>__('language.msg.m'),'dashboard'=>'1','same_page'=>'1']);
            }
            else{
                DB::transaction(function()
                {
                    $Contact_pageTranslate = new Contact_pageTranslate();
                    $Contact_pageTranslate->name = Input::get('name');
                    $Contact_pageTranslate->summary = Input::get('summary');
                    $Contact_pageTranslate->language_id = Input::get('language_id');
                    $Contact_pageTranslate->hp_contents_id = Input::get('hp_contents_id');
                    $Contact_pageTranslate->update();
                    $Contact_pageTranslate->save();
                    if( !$Contact_pageTranslate )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=> __('language.msg.s'),'dashboard'=>'1','same_page'=>'1']);
            }
        }
    }

    private function rules($edit = null,$pass = null){
        $x= [
            'name' => 'required|min:3|max:191|regex:/^[ا-يa-zA-Z0-9]/',
            'summary' => 'required|string',
            'hp_contents_id' => 'required|numeric|min:1',
            'language_id' => 'required|numeric|min:1',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
        }
        return $x;
    }

    private function languags(){
        if(app()->getLocale() == "ar"){
            return [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.regex' => 'حقل الاسم غير صحيح .',
                'name.min' => 'حقل الاسم مطلوب على الاقل 3 حقول .',
                'name.max' => 'حقل الاسم مطلوب على الاكثر 191 حرف  .',
                'sub_name.required' => 'حقل الاسم الثانوي مطلوب.',
                'sub_name.regex' => 'حقل الاسم الثانوي غير صحيح .',
                'sub_name.min' => 'حقل الاسم الثانوي مطلوب على الاقل 3 حقول .',
                'sub_name.max' => 'حقل الاسم الثانوي مطلوب على الاكثر 191 حرف  .',
                'summary.required' => 'حقل الوصف مطلوب.',
                'language_id.required' => 'حقل الغة مطلوب.',
                'dir.required' => 'حقل كود الغة مطلوب.',
            ];
        }
        else{
            return [];
        }
    }


}
