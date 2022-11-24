<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Contents;

class SliderController extends Controller
{
    public function index(){
        $items =  Contents::where("type","slider")->get();
        return view('dashboard/slider.index',compact('items'));
    }

    public function add_edit(){
        return view('dashboard/slider.add_edit');
    }

    function deleted_all(Request $request)
    {
        $array = $request->array;
        if ($array == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if (count($array) == 0) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        foreach ($array as $key => $value){
            $Post = Contents::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => __('language.msg.e')]);
            }
            $Post->delete();
        }
        return response()->json(['success' => __('language.msg.d'),'redirect' => route('dashboard_slider.index')]);
    }

    function get_data_by_id(Request $request){
        $id = $request->id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $Post = Contents::where('id' ,'=',$id)->first();
        if($Post == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        return response()->json(['success'=>$Post]);
    }

    function deleted(Request $request){
        $id = $request->id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $Post = Contents::where('id' ,'=',$id)->first();
        if($Post == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $Post->delete();
        return response()->json(['error' => __('language.msg.d'),'redirect' => route('dashboard_slider.index')]);
    }

    public function post_data(Request $request){
        $edit = $request->id;
        $validation = Validator::make($request->all(), $this->rules($edit),$this->languags());
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($edit != null){
                DB::transaction(function()
                {
                    $Post = Contents::where('id' ,'=',Input::get('id'))->first();
                    $Post->link = Input::get('link');
                    $Post->video = Input::get('video');
                    $Post->name = Input::get('name');
                    $Post->summary = Input::get('summary');
                    if(Input::hasFile('avatar1')){
                        //Remove Old
                        if($Post->avatar1 != 'slider/no.png'){
                            if(file_exists(public_path($Post->avatar1))){
                                unlink(public_path($Post->avatar1));
                            }
                        }
                        //Save avatar
                        $Post->avatar1 = parent::upladImage(Input::file('avatar1'),'slider');
                    }
                    $Post->update();
                    if( !$Post )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=>__('language.msg.m'),'dashboard'=>'1','redirect' =>route('dashboard_slider.index')]);
            }
            else{
                DB::transaction(function()
                {
                    $Post = new Contents();
                    $Post->link = Input::get('link');
                    $Post->video = Input::get('video');
                    $Post->name = Input::get('name');
                    $Post->summary = Input::get('summary');
                    $Post->type = 'slider';
                    $Post->avatar1 = parent::upladImage(Input::file('avatar1'),'slider');
                    // $Post->user_id = parent::CurrentID();
                    $Post->save();
                    if( !$Post )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=> __('language.msg.s'),'dashboard'=>'1','redirect' =>route('dashboard_slider.index')]);
            }
        }
    }

    private function rules($edit = null){
        $x= [
            'avatar1' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'name' => 'required|min:2|max:191|string',
            'link' => 'required|min:1|max:191|string',
            'video' => 'required|min:1|max:191|string',
            'summary' => 'required|min:2',
        ];
        if($edit != null){
            $x['id'] ='required|integer|min:1';
            $x['avatar1'] ='nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG';
        }
        return $x;
    }

    private function languags(){
        if(app()->getLocale() == "ar"){
            return [
                'video.required' => 'حقل الفيديو مطلوب.',
                'video.regex' => 'حقل الفيديو غير صحيح .',
                'video.min' => 'حقل الفيديو مطلوب على الاقل 3 حقول .',
                'video.max' => 'حقل الفيديو مطلوب على الاكثر 191 حرف  .',
                'name.required' => 'حقل الاسم مطلوب.',
                'name.regex' => 'حقل الاسم غير صحيح .',
                'name.min' => 'حقل الاسم مطلوب على الاقل 3 حقول .',
                'name.max' => 'حقل الاسم مطلوب على الاكثر 191 حرف  .',
                'type.required' => 'حقل نوع التنصيف مطلوب.',
                'type.numeric' => 'حقل نوع التنصيف غير صحيح .',
                'type.in' => 'حقل نوع التنصيف غير صحيح .',
                'type_post.required' => 'حقل نوع المقالة مطلوب.',
                'type_post.numeric' => 'حقل نوع المقالة غير صحيح .',
                'type_post.in' => 'حقل نوع المقالة غير صحيح .',

                'avatar1.required' => 'حقل الصورة مطلوب.',
                'summary.required' => 'حقل الوصف مطلوب.',
                'dir.required' => 'حقل كود الغة مطلوب.',

            ];
        }
        else{
            return [];
        }
    }

    function featured(Request $request){
        $id = $request->id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $user = Contents::where('id' ,'=',$id)->first();
        if($user == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        if($user->active == 1){
            $user->active = 0;
            $user->update();
            return response()->json(['error'=> 'In Active']);
        }
        else{
            $user->active = 1;
            $user->update();
            return response()->json(['success'=> 'Active']);
        }
    }

}
