<?php

namespace App\Http\Controllers\Dashboard;

use App\Imports\InvoicesExport;
use App\RestaurantReview;
use App\SubCategory;
use App\User;
use App\UserResaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CommentsController extends Controller
{
    public function index()
    {
        return view('dashboard/comments.index');
    }

    public function add_edit()
    {
        return view('dashboard/comments.add_edit');
    }

    public function view(Request $request, $id)
    {
        $item = RestaurantReview::where("id",$id)->first();
        if($item == null){
            return redirect()->route('dashboard_comments.index',['restaurant_id'=>$request->restaurant_id]);
        }
        return view('dashboard/comments.view',compact('item'));
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'avatar',
            3 => 'featured',
            4 => 'type',
            5 => 'id',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $restaurant_id = $request->restaurant_id;
        $cat = $request->cat;

        $totalData = RestaurantReview::Where('restaurant_id', $restaurant_id)->count();
        $totalFiltered = $totalData;

        $posts = RestaurantReview::
        Where('restaurant_id', $restaurant_id)
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->orderBy($order, $dir)
            ->get();

        if ($search != null) {
            $totalFiltered = RestaurantReview::
            Where('restaurant_id', $restaurant_id)
                ->count();
        }


        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $edit = route('dashboard_comments.add_edit', ['id' => $post->id, 'restaurant_id' => $request->restaurant_id]);
                $view = route('dashboard_comments.view', ['id' => $post->id, 'restaurant_id' => $request->restaurant_id]);

                $Reviewc = 0;
                if($post->Restaurant->TotolComment() != 0){
                    $Reviewc = $post->Restaurant->SumStarComment() / $post->Restaurant->TotolComment();
                }

                $nestedData['id'] = $post->id;
                $nestedData['desc'] = mb_substr($post->comment, 1, 25,'utf-8');
                $nestedData['name'] = $post->client_name;
                $nestedData['phone'] = $post->client_phone;
                $nestedData['date'] = $post->date();
                $nestedData['review'] = $Reviewc;
                $nestedData['options'] = "&emsp;<a class='btn btn-sm btn-primary' href='{$edit}' title='Edit' ><span class='color_wi fa fa-edit'></span> Edit</a>
                                          <a class='btn_delete_current btn btn-danger btn-sm' data-id='{$post->id}' title='Delete' ><span class='color_wi fa fa-trash'></span> Delete</a>";

                $nestedData['view'] = "<a class='btn btn-sm btn-secondary' href='{$view}' title='View' ><span class=' fa fa-info-circle'></span> View</a>";

                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
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
            $Post = RestaurantReview::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => __('language.msg.e')]);
            }
            $Post->delete();
        }
        return response()->json(['success' => __('language.msg.d')]);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = RestaurantReview::where('id', '=', $id)->first();
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
        $Post = RestaurantReview::where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post->delete();
        return response()->json(['error' => __('language.msg.d')]);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $validation = Validator::make($request->all(), $this->rules($edit));
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {

            $Post = RestaurantReview::where('id', '=', $request->id)->first();
            $Post->comment = $request->comment;
            $Post->update();

            return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_comments.index', ['restaurant_id' => $request->restaurant_id])]);
        }
    }

    private function rules($edit = null)
    {
        $x = [
            'comment' => 'required',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
        }
        return $x;
    }

    function featured(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $user = RestaurantReview::where('id', '=', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($user->active == 1) {
            $user->active = 0;
            $user->update();
            return response()->json(['error' => __('table.r-choice')]);
        } else {
            $user->active = 1;
            $user->update();
            return response()->json(['success' => __('table.choice')]);
        }
    }

}
