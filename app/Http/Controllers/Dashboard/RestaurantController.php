<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\City;
use App\RestaurantCategory;
use App\RestaurantCity;
use App\SubCategory;
use App\UserResaurant;
use App\ProductsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\InvoicesExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Restaurant;
use App\User;
use App\Products;

use App\Http\Controllers\Dashboard\Common;
use App\ProductsFeature;

class RestaurantController extends Controller
{
    public function index()
    {
        $category_id = Category::where("active", 1)->get();
        $city = City::where("active", 1)->get();
        return view('dashboard/restaurant.index', compact('category_id', 'city'));
    }

    public function export(Request $request)
    {
        $body = Restaurant::select("*");

        if ($request->from && $request->to) {

            $from_d = parent::date_get($request->from, 2) . '-' . parent::date_get($request->from, 0) . '-' . parent::date_get($request->from, 1);
            $to_d = parent::date_get($request->to, 2) . '-' . parent::date_get($request->to, 0) . '-' . parent::date_get($request->to, 1);

            $from = date($from_d);
            $to = date($to_d);

            $body = $body->whereBetween('created_at', [$from, $to]);
        }

        $body = $body->get();
        $excelData = [];
        if (!empty($body)) {
            foreach ($body as $data) {
                $name = $data->user->name ?? '';
                $nestedData['name'] = $name;
                $nestedData['orders'] = $data->Orders->count();
                $nestedData['sales'] = $data->OrdersTotal();
                $nestedData['pending'] = $data->OrdersPending();
                $nestedData['accepted'] = $data->OrdersCompleted();
                $nestedData['rejected'] = $data->OrdersRejected();
                $excelData[] = $nestedData;
            }
        }
        $headers_collc = [
            'name',
            'orders',
            'sales',
            'pending',
            'accepted',
            'rejected'
        ];
        $export = new InvoicesExport([
            $headers_collc,
            $excelData
        ]);
        return Excel::download($export, 'export' . time() . '.xlsx');
    }

    public function add_edit($id = null)
    {
        $category_id = Category::where("active", 1)->get();
        $city_id = City::where("active", 1)->get();
        return view('dashboard/restaurant.add_edit', compact('category_id', 'city_id'));
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'avatar',
            2 => 'name',
            3 => 'Category',
            4 => 'City',
            5 => $request->cat ? 'priority' : 'all_priority',
            6 => 'orders',
            7 => "c_t",
            8 => "comments",
            9 => "reviewc",
            10 => "sales",
            11 => "pending",
            12 => "accepted",
            13 => "rejected"
        );

        // if($totalData) {
        //     $totalFiltered = $totalData;
        // }
        // else {
        //     $totalData = 0;
        //     $totalFiltered = $totalData;
        // }

        $cat = $request->cat;
        $city = $request->city;
        $city_ids = City::where("active", 1)->pluck('id');
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $user_array = User::where('name', 'like', "%$search%")->pluck('id');

        $totalFiltered = Restaurant::with('user')->whereIn('user_id', $user_array)->whereIn('restaurant_city', $city_ids)->count();
        $totalData = Restaurant::with('user')->whereIn('user_id', $user_array)->whereIn('restaurant_city', $city_ids);
        if ($city) {
            if ($cat) {
                $posts = $totalData->where('restaurant_city', $city)
                    ->where('restaurant_category', $cat)
                    ->offset($start)
                    ->limit($limit)
                    // ->orderBy('id', 'desc')
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $posts = $totalData->where('restaurant_city', $city)
                    ->offset($start)
                    ->limit($limit)
                    // ->orderBy('id', 'desc')
                    ->orderBy($order, $dir)
                    ->get();
            }
        } else {
            if ($cat) {
                $posts = $totalData->where('restaurant_category', $cat)
                    ->offset($start)
                    ->limit($limit)
                    // ->orderBy('id', 'desc')
                    ->orderBy($order, $dir)
                    ->get();
            } else {
                $posts = $totalData->offset($start)
                    ->limit($limit)
                    // ->orderBy('id', 'desc')
                    ->orderBy($order, $dir)
                    ->get();
            }
        }

        $data = array();
        $active_count = 1;
        if (!empty($posts)) {
            $priorityNum = 1;
            foreach ($posts as $post) {
                $ava = URl("/")."/".$post->img();

                $edit = route('dashboard_restaurant.add_edit', ['id' => $post->id]);

                $featured = '';
                if ($post->active == 1) {
                    $featured = 'checked';
                }
                $nestedData['featured'] = '<div class="material-switch pull-left">
                                                <input data-id="' . $post->id . '" id="active_' . $active_count . '" class="btn_featured" type="checkbox" ' . $featured . '/>
                                                <label for="active_' . $active_count . '" class="label-success"></label>
                                            </div>';

                $city = City::where('id', $post->restaurant_city)->first();
                $result_city =  "<span class='badge badge-success'>$city->name</span>";

                $cat = Category::where('id', $post->restaurant_category)->first();
                $result_cat = "<span class='badge badge-primary'>$cat->name</span>";

                $Reviewc = 0;
                if ($post->TotolComment() != 0) {
                    $Reviewc = $post->SumStarComment() / $post->TotolComment();
                }

                $add = "<label>
                            <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                        </label>";

                $ad = route('dashboard_products.index', ['restaurant_id' => $post->id]);
                $adds = "<a class='btn btn-primary btn-sm' href='{$ad}' title='Add more' ><span class='color_wi fa fa-plus'></span></a>";
                if (isset($request->cat) && !empty($request->cat)) {
                    $nestedData['priority'] = '<input type="number" data-id="' . $post->id . '" id="priority_' . $active_count . '" class="form-control input-priority" style="width: 75px;" value="' . $priorityNum++ . '"/>';
                } else {
                    $nestedData['priority'] = '<input type="number" data-id="' . $post->id . '" id="priority_' . $active_count . '" class="form-control input-priority" style="width: 75px;" value="' . $priorityNum++ . '"/>';
                }

                $name = $post->user->name ?? '';
                $nestedData['id'] = $add;
                $nestedData['City'] = $result_city;
                $nestedData['Category'] = $result_cat;
                $nestedData['orders'] = $post->Orders->count();
                $nestedData['sales'] = $post->OrdersTotal();
                $nestedData['pending'] = $post->OrdersPending();
                $nestedData['rejected'] = $post->OrdersRejected();
                $nestedData['accepted'] = $post->OrdersCompleted();
                $nestedData['name'] = $name;
                $nestedData['add'] = $adds;
                $nestedData['comments'] = $post->Comments->count();
                $nestedData['c_t'] = 1;
                $nestedData['reviewc'] = $Reviewc;
                $nestedData['avatar'] = "<a href='{$ad}'><img style='width: 50px;height: 50px;' src='{$ava}' class='img-circle img_data_tables'></a>";
                $nestedData['options'] = "<a class='btn btn-sm btn-primary' href='{$edit}' title='Edit' ><i class='fa fa-edit'></i> Edit</a>";
                if (Auth::user()->role == '1'){
                    $nestedData['options'] .= "<a class='btn_delete_current btn btn-sm btn-danger' href='#' data-id='{$post->id}' title='Delete' ><i class='fa fa-trash'></i> Delete</a>";
            }
                $data[] = $nestedData;
                $active_count = $active_count + 1;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    function get_restaurant_by_cat_city(Request $request)
    {
        $cat = $request->cat;
        $city = $request->city;
        $restaurant = Restaurant::with('user')->where('restaurant_category', $cat)->where('restaurant_city', $city)->get();
        echo json_encode($restaurant);
    }

    function get_sub_cat_by_res(Request $request)
    {
        $res = $request->res;
        $product_cat = Products::with('ProductsCategory')->where('restaurant_id', $res)->pluck('id');
        $sub_cat = ProductsCategory::with('SubCategory')->whereIn('products_id', $product_cat)->groupBy('sub_category_id')->get();
        echo json_encode($sub_cat);
    }

    function get_pro_cat_by_sub_res(Request $request)
    {
        $res = $request->res;
        $sub = $request->sub;
        $ids = ProductsCategory::where('sub_category_id', $sub)->pluck('products_id');
        $product_cat = Products::whereIn('id', $ids)->where('restaurant_id', $res)->get();
        echo json_encode($product_cat);
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
            $Post = Restaurant::where('id', '=', $value)->first();
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
        $Post = Restaurant::with('user')->where('id', '=', $id)->first();
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
        $Post = Restaurant::with('user')->where('id', '=', $id)->first();

        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $products = Products::where('restaurant_id', $Post->id)->get();
        if (!empty($products)) {
            foreach ($products as $product) {
                $features = ProductsFeature::where('products_id', $product->id)->get();
                if (!empty($features)) {
                    foreach ($features as $feature) {
                        $feature->delete();
                    }
                }
                $product->delete();
            }
        }
        $user = User::where('id', $Post->user_id)->first();
        $user->delete();
        $priority = $Post->priority;
        $to = $Post['user']['email'];
        $subject = "Sandwich Map";
        $message = 'Unfortunately Your Restaurant  has been Removed From Sandwich Map Platform
it was a pleasure to have you with us

www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770';

        $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
            "CC: somebodyelse@example.com";

        $message1 = 'Unfortunately Your Restaurant  has been Removed From Sandwich Map Platform
it was a pleasure to have you with us.

www.sandwichmap.com
Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $Post['user']['email'] . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
        //Common::SendTextSMS($Post['user']['phone'], $message);
        Common::SendEmail($to, $subject, $message1, $headers);
        Restaurant::where('restaurant_city', $Post->restaurant_city)->where('restaurant_category', $Post->restaurant_category)->where('priority', '>', $priority)->decrement('priority');
        Restaurant::where('restaurant_city', $Post->restaurant_city)->where('all_priority', '>', $priority)->decrement('all_priority');
        $Post->delete();
        return response()->json(['error' => __('language.msg.d')]);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $password = $request->password;
        $user_name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $validation = Validator::make($request->all(), $this->rules_test($edit, $password, $email));

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
            // return redirect()->back()->withErrors($validation)->withInput();
        } else {
            if ($edit != null) {
                
                    $Post = Restaurant::where('id', $request->id)->first();
                    $user = User::where('id', $Post->user_id)->first();

                    $user->name = $request->name;
                    $user->phone = $request->phone;
                    $user->email = $request->email;
                    
                    $user->landline_number = $request->landline_number;
                    $user->map_link = $request->map_link;
                    $user->opening_hours = $request->opening_hours;
                    $user->latitude = $request->latitude;
                    $user->longtitude = $request->longtitude;
                    $user->instagram = $request->instagram;
                    $user->address = $request->address;
                    $user->whatsapp = $request->whatsapp;
                    
                    $user->sub_emails = $request->sub_emails;
                    if ($request->password != null) {
                        $user->password = bcrypt($request->password);
                    }
                    $user->show_password = $request->password;
                    if ($request->avatar) {
                        $user->avatar = parent::upladImage($request->avatar, 'avatar');
                    }
                    $user->update();
                    $Post->fees = $request->fees;
                    $Post->delivery = $request->delivery;
                    $Post->cash = 0;
                    $Post->restaurant_category = $request->category_id[0];
                    $Post->restaurant_city = $request->city_id[0];
                    if ($request->cash == "on") {
                        $Post->cash = 1;
                    }
                    $Post->visa = 0;
                    if ($request->visa == "on") {
                        $Post->visa = 1;
                    }
                    $Post->online = 0;
                    if ($request->online == "on") {
                        $Post->online = 1;
                    }
                    $Post->update();

                    if (!$Post) {
                        return response()->json(['error' => __('language.msg.e')]);
                    }
                

                $to = $email;
                $subject = "Sandwich Restaurant";
                $message = 'Welcome to Sandwich Map.
Your Email and PhoneNumber has been successfully updated To SandwichMap

Restaurant name  : ' . $user_name . '
mail: ' . $email . '
password: ' . $password . '
phone: ' . $phone . '

www.sandwichmap.com
Thank you for using SandwichMap for more support Please call 0501212770';

                $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                    "CC: somebodyelse@example.com";

                // $message1 = 'Welcome to Sandwich Map.
                //             Your Email and PhoneNumber has been successfully updated To SandwichMap.

                //             New Email: '. $email . '
                //             New phone number: ' . $phone.'

                //             www.sandwichmap.com
                //             Thank you for using SandwichMap for more support Please call 0501212770
                //             Please do not reply to this email.
                //           ';
                //Common::SendTextSMS($phone, $message);
                //Common::SendEmail($to, $subject, $message, $headers);
                return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_restaurant.index')]);
            } else {
                
                    $av = parent::upladImage($request->avatar, 'avatar');
                    $user = new User();
                    $user->avatar = $av;
                    $user->name = $request->name;
                    $user->phone = $request->phone;
                    $user->email = $request->email;
                    
                    $user->landline_number = $request->landline_number;
                    $user->map_link = $request->map_link;
                    $user->opening_hours = $request->opening_hours;
                    $user->latitude = $request->latitude;
                    $user->longtitude = $request->longtitude;
                    $user->address = $request->address;
                    $user->instagram = $request->instagram;
                    $user->whatsapp = $request->whatsapp;
                    
                    $user->sub_emails = $request->sub_emails;
                    $user->active = 1;
                    $user->role = 4;
                    if ($request->password != null) {
                        $user->password = bcrypt($request->password);
                    }
                    $user->show_password = $request->password;
                    $user->save();
                    $Post = new Restaurant();
                    $Post->user_id = $user->id;
                    $Post->fees = $request->fees;
                    $Post->delivery = $request->delivery;
                    $Post->status = 1;
                    $Post->active = 1;
                    $Post->cash = 0;
                    $Post->priority = Restaurant::where('restaurant_city', $request->city_id[0])->where('restaurant_category', $request->category_id[0])->count() + 1;
                    $Post->priority = Restaurant::where('restaurant_city', $request->city_id[0])->count() + 1;
                    $Post->restaurant_category = $request->category_id[0];
                    $Post->restaurant_city = $request->city_id[0];
                    if ($request->cash == "on") {
                        $Post->cash = 1;
                    }
                    $Post->visa = 0;
                    if ($request->cash == "on") {
                        $Post->cash = 1;
                    }
                    $Post->visa = 0;
                    if ($request->visa == "on") {
                        $Post->visa = 1;
                    }
                    $Post->online = 0;
                    if ($request->online == "on") {
                        $Post->online = 1;
                    }
                    $Post->save();

                    if (!$Post) {
                        return response()->json(['error' => __('language.msg.e')]);
                    }
                

                $to = $email;
                $subject = "Sandwich Restaurant";
                $message = 'Welcome to Sandwich Map.
Your Restaurant has been successfully registered To SandwichMap
Restaurant name  : ' . $user_name . '
mail: ' . $email . '
password: ' . $password . '
phone: ' . $phone . '

www.sandwichmap.com
Thank you for using SandwichMap for more support Please call 0501212770';

                $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                    "CC: somebodyelse@example.com";

                $message1 = 'Welcome to Sandwich Map.
Your Restaurant has been successfully registered To SandwichMap.
Restaurant name  : ' . $user_name . '
mail: ' . $email . '
password: ' . $password . '
phone: ' . $phone . '
www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $email . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
                //Common::SendTextSMS($phone, $message);
                Common::SendEmail($to, $subject, $message1, $headers);
                return response()->json(['success' => __('language.msg.s'), 'dashboard' => '1', 'redirect' => route('dashboard_restaurant.index')]);
            }
        }
    }

    private function rules_test($edit = null, $pass = null, $email = null)
    {
        $x = [
            'name' => 'required|min:3|max:191',
            'phone' => 'required|min:1|numeric',
            'email' => 'required|string|email' . $edit == null ? '|unique:users,email,' . $email : '',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
            $x['password'] = 'nullable|string|min:6|confirmed';
        } else {
            $x['password'] = 'required|string|min:6|confirmed';
        }

        if ($pass != null) {
            $x['password'] = 'required|string|min:6|confirmed';
        }
        return $x;
    }

    function featured(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $restaurant = Restaurant::with('user')->where('id', '=', $id)->first();
        if ($restaurant == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($restaurant->active == 1) {
            $restaurant->active = 0;
            $restaurant->update();

            $to = $restaurant['user']['email'];
            $subject = "Sandwich Map";
            $message = 'Your Restaurant  has been freezed.
            
To reactivate your restaurant  please contact your relationship manager.

www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                "CC: somebodyelse@example.com";

            $message1 = 'Your Restaurant  has been freezed.
            
To reactivate your restaurant  please contact your relationship manager.

www.sandwichmap.com
Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $restaurant['user']['email'] . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            //Common::SendTextSMS($restaurant['user']['phone'], $message);
            Common::SendEmail($to, $subject, $message1, $headers);

            return response()->json(['error' => 'In Active']);
        } else {
            $restaurant->active = 1;
            $restaurant->update();

            $to = $restaurant['user']['email'];
            $subject = "Sandwich Map";
            $message = 'Your Restaurant  has been successfully activated

www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                "CC: somebodyelse@example.com";

            $message1 = 'Your Restaurant  has been successfully activated.

www.sandwichmap.com
Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $restaurant['user']['email'] . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            //Common::SendTextSMS($restaurant['user']['phone'], $message);
            Common::SendEmail($to, $subject, $message1, $headers);

            return response()->json(['success' => 'Active']);
        }
    }

    function priority(Request $request)
    {
        $id = $request->id;
        $priority = $request->priority;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $restaurant = Restaurant::where('id', $id)->first();
        if ($restaurant == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }

        $items = Restaurant::where('id', '<>', $id)->where('restaurant_city', $restaurant->restaurant_city)->orderBy('priority')->get();
        $current = -1;
        $total = 1;
        foreach ($items as $item) {
            if ($item->priority >= $priority && $current == -1) {
                $current = $total;
                $total++;
            }
            $item->update(['priority' => $total, 'all_priority' => $total]);
            $total++;
        }
        if ($current == -1) $current = $total;
        $restaurant->update(['priority' => $current, 'all_priority' => $current]);
        return response()->json(['success' => 'Update Priority']);
    }
}
