<?php

namespace App\Http\Controllers\Dashboard;

use App\PostGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Post;

class PostsController extends Controller
{
    public function index()
    {
        $items = Post::get();
        return view('dashboard/post.index',compact('items'));
    }

    public function add_edit()
    {
        return view('dashboard/post.add_edit');
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
        foreach ($array as $key => $value) {
            $Post = Post::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => __('language.msg.e')]);
            }
            $Post->delete();
        }
        return response()->json(['success' => __('language.msg.d'),'redirect' => route('dashboard_posts.index')]);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = Post::where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        return response()->json(['success' => $Post]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = Post::where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post->delete();
        return response()->json(['error' => __('language.msg.d'),'redirect' => route('dashboard_posts.index')]);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $type_post = $request->type_post;
        $type = $request->type;
        $validation = Validator::make($request->all(), $this->rules($edit, $type_post, $type), $this->languags());
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            if ($edit != null) {
                DB::transaction(function () {
                    $Post = Post::where('id', '=', Input::get('id'))->first();
                    $Post->tags = parent::create_slug(Input::get('tags'));
                    $Post->name = Input::get('name');
                    $Post->type = Input::get('type');
                    $Post->summary = Input::get('summary');
                    if (Input::hasFile('avatar')) {
                        //Remove Old
                        if ($Post->avatar != 'posts/no.png') {
                            if (file_exists(public_path($Post->avatar))) {
                                unlink(public_path($Post->avatar));
                            }
                        }
                        //Save avatar
                        $Post->avatar = parent::upladImage(Input::file('avatar'), 'posts');
                    }
                    $Post->update();
                    if (!$Post) {
                        return response()->json(['error' => __('language.msg.e')]);
                    }
                });
                return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_posts.index')]);
            } else {
                DB::transaction(function () {
                    $Post = new Post();
                    $Post->name = Input::get('name');
                    $Post->type = Input::get('type');
                    $Post->tags = parent::create_slug(Input::get('tags'));
                    $Post->summary = Input::get('summary');
                    $Post->avatar = parent::upladImage(Input::file('avatar'), 'posts');
                    if (Input::get("video") != null) {
                        $Post->video = Input::get('video');
                    }
                    $Post->user_id = parent::CurrentID();
                    $Post->save();
                    if (!$Post) {
                        return response()->json(['error' => __('language.msg.e')]);
                    }
                });
                return response()->json(['success' => __('language.msg.s'), 'dashboard' => '1', 'redirect' => route('dashboard_posts.index')]);
            }
        }
    }

    private function rules($edit = null, $type_post = null, $type = null)
    {
        $x = [
            'tags' => 'required|min:3|max:191|regex:/^[ا-يa-zA-Z0-9]/',
            'name' => 'required|min:3|max:191|regex:/^[ا-يa-zA-Z0-9]/',
            'avatar' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
            'type' => 'required|numeric|in:1,2,3',
            'summary' => 'required|string',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
            $x['avatar'] = 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG';
        }
        return $x;
    }

    private function languags()
    {
        if (app()->getLocale() == "ar") {
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

                'avatar.required' => 'حقل الصورة مطلوب.',
                'summary.required' => 'حقل الوصف مطلوب.',
                'dir.required' => 'حقل كود الغة مطلوب.',
                'keywords' => 'The keywords field is required.',
                'description ' => 'The description  field is required.',

            ];
        } else {
            return [];
        }
    }

    function featured(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $user = Post::where('id', '=', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($user->featured == 1) {
            $user->featured = 0;
            $user->update();
            return response()->json(['error' => __('table.r-choice')]);
        } else {
            $user->featured = 1;
            $user->update();
            return response()->json(['success' => __('table.choice')]);
        }
    }

}
