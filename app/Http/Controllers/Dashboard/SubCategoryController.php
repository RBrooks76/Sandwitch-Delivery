<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\SubCategory;

class SubCategoryController extends Controller
{
    public function index()
    {
        return view('dashboard/sub_category.index');
    }

    public function add_edit()
    {
        return view('dashboard/sub_category.add_edit');
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'confirm_email',
            4 => 'id',
        );

        $type = $request->type;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $totalData = SubCategory::where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            }
        })->count();
        $totalFiltered = $totalData;

        $posts = SubCategory::where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
            }
        })->offset($start)->limit($limit)->orderBy('priority')->orderBy('id', 'desc')->orderBy($order, $dir)->get();

        if ($search != null) {
            $totalFiltered = SubCategory::where(function ($q) use ($search) {
                if ($search) {
                    $q->Where('name', 'LIKE', "%{$search}%");
                }
            })
                ->count();
        }
        $data = array();
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {

                $edit = route('dashboard_sub_category.add_edit', ['id' => $post->id, 'type' => $request->type]);

                $check1 = '';
                $active_or_no1 = 'Disable';
                if ($post->active == 1) {
                    $check1 = 'checked';
                    $active_or_no1 = 'Enable';
                }
                $add1 = '<div class="material-switch pull-left">
                                                            <input data-id="' . $post->id . '" id="active_' . $post->id . '" class="btn_confirm_email_current" type="checkbox" ' . $check1 . '/>
                                                            <label for="active_' . $post->id . '" class="label-success"></label>
                                                        </div>';

                $add = "<label>
                        <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                    </label>";

                $nestedData['id'] = $add;
                $nestedData['name'] = $post->name;
                $nestedData['confirm_email'] = $add1;
                $nestedData['options'] = "&emsp;<a class='btn btn-success btn-sm' href='{$edit}' title='تعديل' ><span class='color_wi fa fa-edit'></span></a>
                                         <a class='btn_delete_current btn btn-danger btn-sm' data-id='{$post->id}' title='حذف' ><span class='color_wi fa fa-trash'></span></a>";
                $nestedData['priority'] = '<input type="number" data-id="' . $post->id . '" id="priority_' . $priority . '" class="form-control input-priority" style="width: 75px;" value="' . ($priority++) . '"/>';
                $data[] = $nestedData;
            }
        }
        $result = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return response()->json($result);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $SubCategory = SubCategory::where('id', '=', $id)->first();
        if ($SubCategory == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        return response()->json(['success' => $SubCategory]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $SubCategory = SubCategory::where('id', '=', $id)->first();
        if ($SubCategory == null) {
            return response()->json(['error' => 'Error Happen']);
        }

        $SubCategory->delete();
        return response()->json(['error' => 'Delete Done']);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $password = $request->password;
        $validation = Validator::make($request->all(), $this->rules($edit, $password));
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            if ($edit == null) {
                
                    $SubCategory = new SubCategory();
                    $SubCategory->name = $request->name;
                    $SubCategory->user_id = user()->id;
                    $SubCategory->save();
                    if (!$SubCategory) {
                        return response()->json(['error' => 'Error Happen']);
                    }
                
                return response()->json(['success' => 'Created Done', 'dashboard' => '1', 'redirect' => route('dashboard_sub_category.index', ['id' => null, 'type' => $request->type])]);
            } else {
                
                    $SubCategory = SubCategory::where('id', '=', $request->id)->first();
                    $SubCategory->name = $request->name;
                    $SubCategory->update();
                    if (!$SubCategory) {
                        return response()->json(['error' => 'Error Happen']);
                    }
                
                return response()->json(['success' => 'Updated Done', 'dashboard' => '1', 'redirect' => route('dashboard_sub_category.index', ['id' => null, 'type' => $request->type])]);
            }
        }
    }

    private function rules($edit = null, $pass = null)
    {
        $x = [
            'name' => 'required|min:1|max:191',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
        }

        return $x;
    }

    function confirm_email(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        $SubCategory = SubCategory::where('id', '=', $id)->first();
        if ($SubCategory == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        if ($SubCategory->active == 1) {
            $SubCategory->active = 0;
            $SubCategory->update();
            return response()->json(['error' => 'Not Active']);
        } else {
            $SubCategory->active = 1;
            $SubCategory->update();
            return response()->json(['success' => 'Active']);
        }
    }

    function deleted_all(Request $request)
    {
        $array = $request->array;
        if ($array == null) {
            return response()->json(['error' => 'Error Happen']);
        }
        if (count($array) == 0) {
            return response()->json(['error' => 'Error Happen']);
        }
        foreach ($array as $key => $value) {
            $Post = SubCategory::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => 'Error Happen']);
            }
            $Post->delete();
        }
        return response()->json(['success' => 'Delete Done']);
    }

    public function priority(Request $request)
    {
        $id = $request->id;
        $priority = $request->priority;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $subCategory = SubCategory::where('id', $id)->first();
        if ($subCategory == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }

        $items = SubCategory::where('id', '<>', $id)->orderBy('priority')->get();
        $current = -1;
        $total = 1;
        foreach ($items as $item) {
            if ($item->priority >= $priority && $current == -1) {
                $current = $total;
                $total++;
            }
            $item->update(['priority' => $total]);
            $total++;
        }
        if ($current == -1) $current = $total;
        $subCategory->update(['priority' => $current]);
        return response()->json(['success' => 'Update Priority']);
    }

    public function updateActive(Request $request, $id)
    {
        $subCategory = SubCategory::where('id', $id)->first();
        if (!$subCategory) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $subCategory->update(['active' => $request->active]);

        return response()->json(['success' => true]);
    }
}
