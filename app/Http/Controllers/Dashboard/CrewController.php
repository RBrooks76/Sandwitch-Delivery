<?php

namespace App\Http\Controllers\Dashboard;

use App\Crew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CrewController extends Controller
{
    public function index(){
        return view('dashboard/crew.index');
    }

    function get_data(Request $request)
    {
        $totalData = Crew::count();
        
        $columns = array(
            0 =>'id',
            1 =>'name',
            2 =>'email',
            3 =>'phone',
            4 =>'id',
        );

        
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $posts =  Crew::
        Where('Name', 'LIKE',"%{$search}%")
            ->orWhere('City', 'like',"%{$search}%")
            ->orWhere('Nationality', 'like',"%{$search}%")
            ->orWhere('Phone', 'like',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy('id','desc')
            ->orderBy($order,$dir)
            ->get();

        if($search != null){
            $totalFiltered = Crew::
            Where('Name', 'LIKE',"%{$search}%")
                ->orWhere('City', 'like',"%{$search}%")
                ->orWhere('Nationality', 'like',"%{$search}%")
                ->orWhere('Phone', 'like',"%{$search}%")
                ->count();
        }


        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['Name'] = $post->Name;
                $nestedData['DOB'] = $post->DOB;
                $nestedData['Exprience'] = $post->Exprience;
                $nestedData['City'] = $post->City;
                $nestedData['Position'] = $post->Position;
                $nestedData['Nationality'] = $post->Nationality;
                $nestedData['about'] = $post->about;
                $nestedData['Phone'] = $post->Phone;
                $nestedData['CV'] = "<a href='".$post->CV."' target='_blank'>View</a>";
                $nestedData['Image'] = "<img src='".$post->Image."' width='100'>";
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

    function details(Request $request){
        $id = $request->id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $ContactUS = Crew::where('id' ,'=',$id)->first();
        if($ContactUS == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        return response()->json(['success'=>$ContactUS]);
    }

    function deleted(Request $request){
        $id = $request->id;
        if($id == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $ContactUS = Crew::where('id' ,'=',$id)->first();
        if($ContactUS == null){
            return response()->json(['error'=> __('language.msg.e')]);
        }
        $ContactUS->delete();
        return response()->json(['error'=> __('language.msg.d')]);
    }

}
