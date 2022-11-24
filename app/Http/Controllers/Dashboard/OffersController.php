<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\City;
// use App\OffersCat;
// use App\OffersCity;
// use App\OffersProducts;
// use App\OffersRestaurant;
// use App\OffersSubCat;
use App\Products;
use App\Restaurant;
use App\SubCategory;
use App\Notification;
use App\FCM;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Offers;

class OffersController extends Controller
{
    public function index()
    {
        $city = City::where("active", 1)->get();
        $category_id = Category::where("active", 1)->get();
        return view('dashboard/offers.index', compact('city', 'category_id'));
    }

    public function add_edit()
    {
        $category_id = Category::where("active", 1)->get();
        $sub_category_id = SubCategory::where("active", 1)->get();
        $restaurant_id = Restaurant::with('user')->where("active", 1)->get();
        $city_id = City::where("active", 1)->get();
        $products_id = Products::where("active", 1)->get();
        return view('dashboard/offers.add_edit', compact('category_id', 'city_id', 'restaurant_id', 'sub_category_id', 'products_id'));
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => "name",
            2 => "restaurant",
            3 => "cat",
            4 => "city",
            5 => "priority",
            6 => "price",
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');
        $product_array = Products::where('name', 'like', "%$search%")->pluck('id');
        $totalData = Offers::whereIn('product_id', $product_array)->count();
        $totalFiltered = $totalData;
        $cat = $request->cat;
        $city = $request->city;
        $posts =  Offers::with('products')->with('category')->with('restaurant')->with('city')->with('subcategory')->whereIn('product_id', $product_array)
            ->where(function ($q) use ($cat) {
                if ($cat) {
                    $q->where("category_id", $cat);
                }
            })
            ->where(function ($q) use ($city) {
                if ($city) {
                    $q->where('city_id', $city);
                }
            })
            ->offset($start)
            ->limit($limit)
            // ->orderBy('id','desc')
            ->orderBy($order, $dir)
            ->get();

        $totalFiltered = Offers::whereIn('product_id', $product_array)
            ->where(function ($q) use ($cat) {
                if ($cat) {
                    $q->where("category_id", $cat);
                }
            })
            ->where(function ($q) use ($city) {
                if ($city) {
                    $q->where('city_id', $city);
                }
            })
            ->count();


        $data = array();
        $active_count = 1;
        if (!empty($posts)) {
            $priority = 1;
            foreach ($posts as $post) {
                $edit = route('dashboard_offers.add_edit', ['id' => $post->id]);

                $featured = '';
                if ($post->active == 1) {
                    $featured = 'checked';
                }

                $nestedData['featured'] = '<div class="material-switch pull-left">
                                                <input data-id="' . $post->id . '" id="active_' . $active_count . '" class="btn_featured" type="checkbox" ' . $featured . '/>
                                                <label for="active_' . $active_count . '" class="label-success"></label>
                                            </div>';

                $Products = $post->Products;
                $result_avatar = path() . "upload/products/no.png";
                $name = $Products->name;
                $img = URL('/') ."/". $Products->img();
                $result_products =  "<span class='badge badge-dark'>$name</span>";
                $result_products_price = $Products->amount;
                $result_avatar = "<img style='width: 50px;height: 50px;' src='" . $img . "' class='img-circle img_data_tables'>";

                $City = $post->City;
                $name = $City->name;
                $result_city =  "<span class='badge badge-success'>$name</span>";

                $Category = $post->Category;
                $name = $Category->name;
                $result_cat =  "<span class='badge badge-primary'>$name</span>";

                $Restaurant = $post->Restaurant;
                $name = $Restaurant->user->name ?? "";
                $result_restaurant =  "<span class='badge badge-warning'>$name</span>";

                $add = "<label>
                        <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                    </label>";

                $pr = $result_products_price . setting()->currency;
                $nestedData['priority'] = '<input type="number" data-id="' . $post->id . '" id="priority_' . $priority . '" class="form-control input-priority" style="width: 75px;" value="' . ($priority++) . '"/>';
                $nestedData['id'] = $add;
                $nestedData['name'] = $result_products;
                $nestedData['city'] = $result_city;
                $nestedData['cat'] = $result_cat;
                $nestedData['avatar'] = $result_avatar;
                $nestedData['price'] = "<span class='badge badge-dark'>$pr</span>";
                $nestedData['restaurant'] = $result_restaurant;

                $nestedData['options'] = "<a class='btn btn-sm btn-primary' href='{$edit}' title='Edit' ><i class='fa fa-edit'></i> Edit</a>
                                          <a class='btn_delete_current btn btn-sm btn-danger' href='#' data-id='{$post->id}' title='Delete' ><i class='fa fa-trash'></i> Delete</a>";
                $data[] = $nestedData;
                $active_count = $active_count + 1;
            }
        }
        $json_data = array(
            "draw"            => intval($request->draw),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
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
            $Post = Offers::where('id', '=', $value)->first();
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
        $Post = Offers::with("City")->with("Category")->with("SubCategory")->with("Restaurant")->with("Products")->where('id', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        
        $Post->ending_in_date = date("Y-m-d", strtotime($Post->ending_in));
        $Post->ending_in_time = date("H:i:s", strtotime($Post->ending_in));
        return response()->json(['success' => $Post]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $Post = Offers::where('id', '=', $id)->first();
        if ($Post == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $priority = $Post->priority;
        Offers::where('city_id', $Post->city_id)->where('priority', '>', $priority)->decrement('priority');
        $restaurant = Restaurant::with('user')->where('id', $Post['restaurant_id'])->first();
        $Post->delete();
        $to = $restaurant['user']['email'];
        $subject = "Sandwich Map";
        $message = 'Your Product has been Removed from the Offers page
For adding more products please contact your Relationship Manager   

Thank you for using SandwichMap for more support Please call 0501212770';

        $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
            "CC: somebodyelse@example.com";

        $message1 = 'Your Product has been Removed from the Offers page
For adding more product please contact your Relationship Manager   

www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770

Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
        Common::SendTextSMS($restaurant['user']['phone'], $message);
        Common::SendEmail($to, $subject, $message1, $headers);

        return response()->json(['error' => __('language.msg.d')]);
    }

    public function post_data(Request $request)
    {
        $edit = $request->id;
        $type_post = $request->type_post;
        $type = $request->type;
        
        $ConnectedID = "";
        
        $validation = Validator::make($request->all(), $this->rules($edit, $type_post, $type));
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            if ($edit != null) {
                $Post = Offers::where('id', '=', $request->id)->first();

                $city_id = $request->city_id;
                if ($city_id) {
                    $Post->city_id = $city_id;
                }

                $category_id = $request->category_id;
                if ($category_id) {
                    $Post->category_id = $category_id;
                }

                $restaurant_id = $request->restaurant_id;
                if ($restaurant_id) {
                    $Post->restaurant_id = $restaurant_id;
                }

                $sub_category_id = $request->sub_category_id;
                if ($sub_category_id) {
                    $Post->sub_category_id = $sub_category_id;
                }

                $product_id = $request->products_id;
                if ($product_id) {
                    $Post->product_id = $product_id;
                }

                if (!$Post) {
                    return response()->json(['error' => __('language.msg.e')]);
                }
                
                $Post->amount = $request->amount;
                $Post->off_percentage = $request->off_percentage;
                $Post->ending_in = $request->ending_in_date." ".$request->ending_in_time;
                
                if($request->coverImage != null){
                    $Post->image = parent::upladImage($request->coverImage, 'coverImage');
                }
                
                if($request->Video != null){
                    $Post->video = parent::upladImage($request->Video, 'Video');
                }
                
                $Post->update();

                $restaurant = Restaurant::with('user')->where('id', $request->restaurant_id)->first();
                $to = $restaurant['user']['email'];
                $subject = "Sandwich Map";
                $message = 'Your Product has been successfully updated to Offers page
Sandwich Map Customers Loves Offers 
Our advice to is to add Your None moving Products to the Offers Page 

Thank you for using SandwichMap for more support Please call 0501212770';

                $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                    "CC: somebodyelse@example.com";

                $message1 = 'Your Product has been successfully updated to Offers page
Sandwich Map Customers Loves Offers 
Our advice to is to add Your None moving Products to the Offers Page 

www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770

Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
                //Common::SendTextSMS($restaurant['user']['phone'], $message);
                //Common::SendEmail($to, $subject, $message1, $headers);
                
                $GetOfferData = Offers::find($Post->id);
                
                $NewExtData["city_id"] = $city_id;
                $NewExtData["restaurant_id"] = $restaurant_id;

                return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_offers.index')]);
            } else {
                
                    $Post = new Offers();
                    $Post->name = "";
                    
                    $Post->amount = $request->amount;
                    $Post->ending_in = $request->ending_in_date." ".$request->ending_in_time;
                    
                    if($request->coverImage != null){
                        $Post->image = parent::upladImage($request->coverImage, 'coverImage');
                    }
                    
                    if($request->Video != null){
                        $Post->video = parent::upladImage($request->Video, 'Video');
                    }
                    $Post->user_id = parent::CurrentID();
                    $Post->status = 1;
                    $Post->active = 1;

                    $city_id = $request->city_id;
                    if ($city_id) {
                        $Post->city_id = $city_id;
                    }

                    $category_id = $request->category_id;
                    if ($category_id) {
                        $Post->category_id = $category_id;
                    }

                    $restaurant_id = $request->restaurant_id;
                    if ($restaurant_id) {
                        $Post->restaurant_id = $restaurant_id;
                    }

                    $sub_category_id = $request->sub_category_id;
                    if ($sub_category_id) {
                        $Post->sub_category_id = $sub_category_id;
                    }

                    $product_id = $request->products_id;
                    if ($product_id) {
                        $Post->product_id = $product_id;
                    }
                    $Post->off_percentage = $request->off_percentage;
                    $Post->priority = Offers::where('city_id', $city_id)->count() + 1;
                    $Post->save();
                    
                    $ConnectedID = $Post->id;
                    
                $restaurant = Restaurant::with('user')->where('id', $request->restaurant_id)->first();
                $to = $restaurant['user']['email'];
                $subject = "Sandwich Map";
                $message = 'Your Product has been successfully added to Offers page
Sandwich Map Customers Loves Offers 
Our advice to is to add Your None moving Products to the Offers Page 

Thank you for using SandwichMap for more support Please call 0501212770';

                $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                    "CC: somebodyelse@example.com";

                $message1 = 'Your Product has been successfully added to Offers page
Sandwich Map Customers Loves Offers 
Our advice to is to add Your None moving Products to the Offers Page 

www.sandwichmap.com

Thank you for using SandwichMap for more support Please call 0501212770

Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
                //Common::SendTextSMS($restaurant['user']['phone'], $message);
                //Common::SendEmail($to, $subject, $message1, $headers);
                
                $NewExtData["city_id"] = $request->city_id;
                $NewExtData["restaurant_id"] = $request->restaurant_id;
                
                if($request->PushNoti == 1){
                    $getAllToken = FCM::get();
                    
                    
                    foreach($getAllToken as $GAT){
                        $NewNoti = new Notification();
                        $NewNoti->users = $GAT->device_id;
                        $NewNoti->type = "Offers";
                        $NewNoti->connected_id = $ConnectedID;
                        $NewNoti->title = $request->PushTitle;
                        $NewNoti->description = $request->PushMessage;
                        $NewNoti->image = "";
                        $NewNoti->save();
                        
                        $this->SendNotificationSendPushNotification($GAT->token, $request->PushTitle, $request->PushMessage, 0, "Offer", $NewExtData);
                    }
                }
                
                return response()->json(['success' => __('language.msg.s'), 'dashboard' => '1', 'redirect' => route('dashboard_offers.index')]);
            }
        }
    }

    private function rules($edit = null)
    {
        $x = [
            'restaurant_id' => 'required',
            'category_id' => 'required',
            'city_id' => 'required',
            'sub_category_id' => 'required',
            'products_id' => 'required'
        ];
        
        if($edit == null){
            $x['coverImage'] = 'required|mimes:jpeg,png,jpg';
            $x['Video'] = 'required|mimes:mp4';
        }
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
        $user = Offers::where('id', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($user->active == 1) {
            $user->active = 0;
            $user->update();
            $restaurant = Restaurant::with('user')->where('id', $user['restaurant_id'])->first();
            $to = $restaurant['user']['email'];
            $subject = "Sandwich Map";
            $message = 'Your Product has been Freezed from the Offers page
To reactivated your Product please contact your Relationship Manager   


Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                "CC: somebodyelse@example.com";

            $message1 = 'Your Product has been Freezed from the Offers page
To reactivated your Product please contact your Relationship Manager   


www.sandwichmap.net

Thank you for using SandwichMap for more support Please call 0501212770

Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            Common::SendTextSMS($restaurant['user']['phone'], $message);
            Common::SendEmail($to, $subject, $message1, $headers);

            return response()->json(['error' => 'In Active']);
        } else {
            $user->active = 1;
            $user->update();

            $to = $user['user']['email'];
            $subject = "Sandwich Map";
            $message = 'Your Product has been successfully Activated in the Offers page
For adding more product please contact your Relationship Manager   

Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                "CC: somebodyelse@example.com";

            $message1 = 'Your Product has been successfully Activated in the Offers page
For adding more product please contact your Relationship Manager   

www.sandwichmap.net

Thank you for using SandwichMap for more support Please call 0501212770

Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    ' . $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            Common::SendTextSMS($user['user']['phone'], $message);
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
        $offer = Offers::where('id', $id)->first();
        if ($offer == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $size = Offers::where('city_id', $offer->city_id)->count();
        if ($priority > $size) {
            Offers::where('city_id', $offer->city_id)->where('priority', '>', $offer->priority)->decrement('priority');
            $offer->priority = $size;
        } else if ($priority <= 0) {
            Offers::where('city_id', $offer->city_id)->where('priority', '<', $offer->priority)->increment('priority');
            $offer->priority = 1;
        } else if ($priority < $offer->priority) {
            Offers::where('city_id', $offer->city_id)->where('priority', '<', $offer->priority)->where('priority', '>=', $priority)->increment('priority');
            $offer->priority = $priority;
        } else if ($priority > $offer->priority) {
            Offers::where('city_id', $offer->city_id)->where('priority', '>', $offer->priority)->where('priority', '<=', $priority)->decrement('priority');
            $offer->priority = $priority;
        }
        $offer->update();
        return response()->json(['success' => 'Update Priority']);
    }
}
