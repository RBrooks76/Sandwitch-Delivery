<?php

namespace App\Http\Controllers\Dashboard;

use App\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function index(){
        return view('dashboard/newsletter.index');
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'email',
            2 =>'id',
        );

        $totalData = Newsletter::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $posts =  Newsletter::
            Where('email', 'like',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->orderBy('id','desc')
            ->get();

        if($search != null){
            $totalFiltered = Newsletter::
                Where('email', 'like',"%{$search}%")
                ->count();
        }


        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['email'] = $post->email;
                $nestedData['options'] = "<a class='btn_delete_current btn btn-danger btn-sm' data-id='{$post->id}' title='حذف' ><span class='color_wi fa fa-trash'></span></a>";
                $data[] = $nestedData;

            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    function deleted(Request $request){
        $id = $request->id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $Newsletter = Newsletter::where('id' ,'=',$id)->first();
        if($Newsletter == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $Newsletter->delete();
        return response()->json(['error'=> __('language.msg.d')]);
    }

}
