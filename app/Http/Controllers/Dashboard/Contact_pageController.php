<?php

namespace App\Http\Controllers\Dashboard;

use App\Contact_page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class Contact_pageController extends Controller
{
    public function index(){
        return view('dashboard/contact_page.index');
    }

    public function get_data_by_id(Request $request){
        $items = Contact_page::where('type','=','contact_page')->first();
        return response()->json(['success'=>$items]);
    }

    public function post_data(Request $request){
        $Contact_page = Contact_page::where('type','=','contact_page')->first();
        $validation = Validator::make($request->all(), $this->rules($Contact_page),$this->languags());
        if ($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()]);
        }
        else{
            if($Contact_page == null){
                DB::transaction(function()
                {
                    $Contact_page = new Contact_page();
                    $Contact_page->name = Input::get('name');
                    $Contact_page->summary = Input::get('summary');
                    $Contact_page->type = 'contact_page';
                    $Contact_page->avatar1 = parent::upladImage(Input::file('avatar1'),'contact_page');
                    $Contact_page->language_id = parent::GetIdLangEn()->id;
                    $Contact_page->user_id = parent::CurrentID();
                    $Contact_page->save();
                    if( !$Contact_page )
                    {
                        return response()->json(['error'=> __('language.msg.e')]);
                    }
                });
                return response()->json(['success'=> __('language.msg.s'),'same_page'=>'1','dashboard'=>'1']);
            }
            else{
                DB::transaction(function()
                {
                    $Contact_page = Contact_page::where('type','=','contact_page')->first();
                    $Contact_page->name = Input::get('name');
                    $Contact_page->summary = Input::get('summary');
                    if(Input::hasFile('avatar1')){
                        //Remove Old
                        if($Contact_page->avatar1 != 'upload/contact_page/no.png'){
                            if(file_exists(public_path($Contact_page->avatar1))){
                                unlink(public_path($Contact_page->avatar1));
                            }
                        }
                        //Save avatar1
                        $Contact_page->avatar1 = parent::upladImage(Input::file('avatar1'),'contact_page');
                    }
                    $Contact_page->update();
                    if( !$Contact_page )
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
            'name' => 'required|min:3|max:191|regex:/^[ا-يa-zA-Z0-9]/',
            'summary' => 'required|string',
            'avatar1' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
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
                'link.required' => 'حقل الرابط مطلوب.',
                'video.required' => 'حقل الفيديو مطلوب.',
                'summary.required' => 'حقل الوصف مطلوب.',
                'name.required' => 'حقل الاسم مطلوب.',
                'name.regex' => 'حقل الاسم غير صحيح .',
                'name.min' => 'حقل الاسم مطلوب على الاقل 3 حقول .',
                'name.max' => 'حقل الاسم مطلوب على الاكثر 191 حرف  .',
                'sub_name.required' => 'حقل الاسم الثانوي مطلوب.',
                'sub_name.regex' => 'حقل الاسم الثانوي غير صحيح .',
                'sub_name.min' => 'حقل الاسم الثانوي مطلوب على الاقل 3 حقول .',
                'sub_name.max' => 'حقل الاسم الثانوي مطلوب على الاكثر 191 حرف  .',
                'avatar1.required' => 'حقل الصورة في الهيدر مطلوب.',
                'avatar2.required' => 'حقل الصورة في الهيدر مطلوب.',
                'dir.required' => 'حقل كود الغة مطلوب.',
            ];
        }
        else{
            return [];
        }
    }

}
