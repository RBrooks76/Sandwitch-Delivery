<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\City;
use App\Country;
use App\FCM;
use App\HPContactUS;
use App\Offers;
use App\Order;
use App\MyDriver;
use App\OrderProducts;
use App\OrderProductsFeature;
use App\Products;
use App\ProductsFeature;
use App\Restaurant;
use App\RestaurantReview;
use App\Setting;
use App\SubCategory;
use App\User;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Parser;
use App\Http\Controllers\Dashboard\Common;
use Illuminate\Support\Facades\Input;

use Mail;

class HomePageController extends Controller
{
    public function getHelpLine(){
        $items = Setting::orderby("id", "asc")->first();
        $data = array(
            "description" => $items->CallCenterNumber,
            "order_id" => 1
        );
        
        return parent::successjson($data, 200);
    }

    public function setting()
    {
        $items = Setting::orderby("id", "asc")->first();
        return parent::successjson($items, 200);
    }

    public function category()
    {
        $items = Category::orderby("id", "asc")->get();
        return parent::successjson($items, 200);
    }

    public function restaurant(Request $request)
    {
        $items = Restaurant::with('user')->where("active", 1);
        $city = $request->city_id;
        $cat = $request->category_id;
        $items = $items->whereHas("City", function ($q) use ($city) {
            if ($city) {
                $q->where("restaurant_city", $city);
            }
        });

        $items = $items->whereHas("Category", function ($q) use ($cat) {
            if ($cat) {
                $q->where("restaurant_category", $cat);
            }
        });
        if (!empty($city) && empty($cat)) {
            $items = $items->orderby("all_priority", "asc");
        } else {
            $items = $items->orderby("priority", "asc");
        }
        $items = $items->with("Category")->get();
        foreach ($items as $key => $item) {
            $item->total_rating = RestaurantReview::where('restaurant_id', $item->id)->avg('star');
            $item->user_id = $item->user_id."";
            $item->restaurant_city = $item->restaurant_city."";
            $item->priority = $item->priority."";
            $item->status = $item->status."";
            $item->all_priority = $item->all_priority."";
            $item->restaurant_category = $item->restaurant_category."";
        }
        return parent::successjson($items, 200);
    }

    public function restaurant_view(Request $request)
    {
        $items = Restaurant::orderby("id", "asc")->where("id", $request->id);
        $items = $items->with("Products");
        $items = $items->with("Products");
        $items = $items->first();
        
        $items->views = $items->views + 1;
        $items->save();
        
        return parent::successjson($items, 200);
    }

    public function sub_category()
    {
        $items = SubCategory::orderBy('priority')->orderBy("id", "asc")->first();
        return parent::successjson($items, 200);
    }

public function UpdateOfferCount(Request $request){
        $Offer = Offers::find($request->id);
        $Offer->view = $Offer->view + 1;
        $Offer->save();
        return parent::successjson("", 200);
    }

    public function offers(Request $request)
    {
        if ($request->city_id) {
            $offers = Offers::with(['category', 'city', 'restaurant', 'products'])
                ->where('city_id', $request->city_id)
                ->orderby("priority", "asc")
                ->get();
        } else {
            $offers = Offers::with(['category', 'city', 'restaurant', 'products'])
                ->orderby("priority", "asc")
                ->get();
        }
        foreach ($offers as &$item) {
            
            $item->discount_off = $item->off_percentage."";
            
            $item->product_id = $item->product_id."";
            $item->restaurant_id = $item->restaurant_id."";
            $item->restaurant->name = $item->restaurant->User->name;
            $item->restaurant->map_link = $item->restaurant->User->map_link;
            $item->restaurant->landline_number = $item->restaurant->User->landline_number;
            $item->user_id = $item->user_id."";
            $item->prority = $item->prority."";
            $item->view = $item->view."";
            
            $item->restaurant->user_id = $item->restaurant->user_id."";
            $item->restaurant->restaurant_city = $item->restaurant->restaurant_city."";
            $item->restaurant->priority = $item->restaurant->priority."";
            $item->restaurant->avatar = $item->restaurant->user->avatar."";
            $item->restaurant->status = $item->restaurant->status."";
            $item->restaurant->all_priority = $item->restaurant->all_priority."";
            $item->restaurant->restaurant_category = $item->restaurant->restaurant_category."";
            $item->restaurant->user = $item->restaurant->user;
            $item->products->restaurant_id = $item->products->restaurant_id."";
            $item->products->priority = $item->products->priority."";
            $item->city->priority = $item->city->priority."";
            
            unset($item->restaurant->User);
        }
        return parent::successjson($offers, 200);
    }


    public function city()
    {
        $items = City::orderBy('priority')->get();
        foreach($items as $it){
            $it->priority = $it->priority."";
        }
        return parent::successjson($items, 200);
    }
    
    public function country(){
        $items = Country::select("countries_id", "name")->orderBy('name')->get();
        return parent::successjson($items, 200);
    }
    

    public function setting_contacts()
    {
        $items = HPContactUS::orderby("id", "asc")->first();
        return parent::successjson($items, 200);
    }

    public function cart_save(Request $request)
    {
        $validation = Validator::make($request->all(), $this->add_cars1());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }

        $restaurant = Restaurant::where("id", $request->restaurant_id)->first();

        if ($restaurant == null) {
            return parent::errorjson("Failer Created. Invalid restaurant", 400);
        }

        $city_id = City::where("id", $request->city_id)->first();

        if ($city_id == null) {
            return parent::errorjson("Failer Created Invalid city", 400);
        }
        
        if ($city_id == null) {
            return parent::errorjson("Failer Created Invalid city", 400);
        }

        $save = Order::where("ip", parent::IP_Address())->where("status", 0)->first();
        if ($request->order_id) {
            $save = Order::where('id', $request->order_id)->first();
        }

        if ($save == null) {
            $save = new Order();
            $save->client_name = "";
            $save->restaurant_id = $request->restaurant_id;
            $save->user_id = $request->user_id;
            $save->order_id = uniqid();
            $save->city_id = $request->city_id;
            $save->ip = parent::IP_Address();
            $save->phone_active = 0;
            $save->phone = "";
            $save->total = 0;
            $save->status = 0;
            $save->payment_type = $request->payment_type;
            $save->log = $request->address["log"];
            $save->lat = $request->address["lat"];
            $save->is_pickup = $request->self_pickup == 1;
            $save->save();
        }
        $order_id = $save->id;
        OrderProducts::where('order_id', $save->id)->delete();
        $price = 0;
        $feature = 0;
        $products = $request->products;
        foreach ($products as $product) {
            $order_products = new OrderProducts();
            $order_products->order_id = $order_id;
            $order_products->restaurant_id = $request->restaurant_id;
            $order_products->products_id = $product['products_id'];
            $order_products->qun = $product['qun'];
            $order_products->price = $product['amount'];
            $order_products->total = $product['amount'] * $product['qun'];
            $order_products->special_request = isset($product["special_request"]) ? $product["special_request"] : "";
            $order_products->save();
            $price += $product['amount'] * $product['qun'];

            //save new price
            if ($product['feature'] != 0) {
                if (count($product['feature']) != 0) {
                    foreach ($product['feature'] as $key => $value) {
                        $order_products_feature = new OrderProductsFeature();
                        $order_products_feature->order_products_id = $order_products->id;
                        $order_products_feature->products_feature_id = $value;
                        $order_products_feature->save();

                        $fr = ProductsFeature::where("id", $value)->first();
                        if ($fr == null) {
                            return parent::errorjson("Failer Created", 400);
                        }
                        $feature = $feature + $fr->amount;
                    }
                }
            }
        }
        $save->total = $price + $feature + ($request->self_pickup == 1 ? 0 : $restaurant->fees);
        $save->save();
        $data = array(
            "description" => "Order Placed Successfully",
            "order_id" => $order_id
        );

        return parent::successjson($data, 200);
    }


    private function add_cars1()
    {
        return [
            'city_id' => 'required|numeric|min:1',
            'restaurant_id' => 'required|numeric|min:1',
            'payment_type' => 'required|numeric|in:1,2,3',
            'address' => 'required',
        ];
    }

    public function cart_save_address(Request $request)
    {
        $validation = Validator::make($request->all(), $this->add_cars12());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        $save = Order::where("ip", parent::IP_Address())->where("status", 0)->first();

        if ($save == null) {
            return parent::errorjson("Failer Created", 400);
        }
        $save->log = $request->log;
        $save->lat = $request->lat;
        $save->save();

        return parent::successjson("Done Created", 200);
    }


    private function add_cars12()
    {
        return [
            'log' => 'required|min:1',
            'lat' => 'required|min:1',
        ];
    }

    public function payment_type(Request $request)
    {
        $validation = Validator::make($request->all(), $this->add_cars122());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        $save = Order::where("ip", parent::IP_Address())->where("status", 0)->first();

        if ($save == null) {
            return parent::errorjson("Failer Created", 400);
        }
        $save->payment_type = $request->payment_type;
        $save->status = 1;
        $save->save();

        return parent::successjson("Done Created", 200);
    }


    private function add_cars122()
    {
        return [
            'payment_type' => 'required|numeric|in:1,2,3',
        ];
    }

    public function complete_order(Request $request)
    {
        $validation = Validator::make($request->all(), $this->complete_order2());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        // $save = Order::where("ip", parent::IP_Address())->where("status", 0)->first();
        $save = Order::where("id", $request->order_id)->where("status", "<", "2")->first();

        if ($save == null) {
            return parent::errorjson("Failer Created", 400);
        }
        $save->client_name = $request->name;
        $save->phone = $request->phone;
        $save->status = 1;
        $verifyCode = mt_rand(100000, 999999);
        $save->code = $verifyCode;

        $message = "Mr. " . $request->name . " Your one time Order verification code is " . $verifyCode . "\nThanks from using Sandwich Map.";

        Common::SendTextSMS($request->phone, $message);
        $save->phone_active = 1;
        $save->save();

        $this->sendEmail($save);

        return parent::successjson("Done Created", 200);
    }


    private function complete_order2()
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string',
            'order_id' => 'required',
        ];
    }

    public function complete_order_verfiy(Request $request)
    {
        $validation = Validator::make($request->all(), $this->complete_order_verfiy2());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        // $save = Order::where("ip", parent::IP_Address())->where("status", 1)->first();
        $save = Order::where("id", $request->order_id)->where("status", 1)->first();
        if ($save == null) {
            return parent::errorjson("Failer Created", 400);
        }

        if ($save->code != $request->code) {
            return parent::errorjson("Failer Code", 400);
        }

        $save->phone_active = 1;
        $save->status = 2;
        $save->code = null;
        $save->save();
        $message = "Dear " . $save->client_name . " Your order is proceeded successfully.\nExpect a Call from the restaurant shortly.\nOrder Number: " . $save->id . "\nTotal bill is: " . $save->total . "\nThank You for using Sandwich Map.";

        Common::SendTextSMS($save->phone, $message);

        $user = User::where('id', $save->user_id)->first();

        $message = "Dear Partner You Have One New Order 

                    Thank you for using SandwichMap 
                    
                    www.sandwichmap.com
                    
                    Thank you for using SandwichMap for more support Please call 0501212770
                    
                    Please do not reply to this email. 
                    
                    We would also be happy to receive your feedback - suggestions and complaints on 
                    
                    Management Email :
                        sandwichmap@yahoo.com
                    
                    Owner Email :
                        i.osmann@yahoo.com
                        fs.aljabri@yahoo.com
                    Sincerely,
                    
                    Sandwich Map LLC
                    Restaurants Partners Support Team
                    ";

        $msg = "Dear Partner You Have One New Order\nCustomer name: " . $save->client_name . "\nMobile Number: " . $save->phone . "\nTotal Bill: " . $save->total . "\nThank you for using Sandwich Map";

        Common::SendTextSMS($user->phone, $msg);

        return parent::successjson("Active Created", 200);
    }

    private function sendEmail($order)
    {
        $user = User::where('id', $order->user_id)->first();
        $to = $user->email;
        $subject = "SandwichMap - new Order# " . $order->id;
        /*$headers = "From:   noreply@sandwhichmail.net";
        $msg = "Dear Partner You Have One New Order\nCustomer name: " . $order->client_name . "\nMobile Number: " . $order->phone . "\nTotal Bill: " . $order->total . "\nThank you for using Sandwich Map";*/
        
        $order = Order::where("id", $order->id)->first();
        $products = OrderProducts::with('Products')->with("OrderProductsFeature.ProductsFeature")->where("order_id", $order->id)->get();

        $sub_total = floatval($order->total);
        $delivery_fee = floatval($order->restaurant->fees);
        if (!$order->is_pickup) $sub_total -= $delivery_fee;

        $sub_total = sprintf("%.2f", $sub_total);
        $delivery_fee = sprintf("%.2f", $delivery_fee);

        foreach ($products as &$item) {
            $addon_ids = OrderProductsFeature::with("ProductsFeature")->where('order_products_id', $item->id)->get();
            $item->product_addons = $addon_ids;
        }
        
        $data = array("order" => $order, "products" => $products, "sub_total" => $sub_total, "delivery_fee" => $delivery_fee, "Restaurant" => $order->restaurant);
        
        Mail::send("EmailTemplates.print", $data, function ($m) use ($order, $to) {
            $m->from("noreply@sandwichmap.net", $order->restaurant->user->name);
            $m->to($to)->subject("SandwichMap - new Order# " . $order->id);
        });
        
        if ($user->sub_emails) {
            $emails = explode(',', $user->sub_emails);
            foreach ($emails as $to){
                Mail::send("EmailTemplates.print", $data, function ($m) use ($order, $to) {
                    $m->from("noreply@sandwichmap.net", $order->restaurant->user->name);
                    $m->to($to)->subject("SandwichMap - new Order# " . $order->id);
                });
            }
        }
    }

    private function complete_order_verfiy2()
    {
        return [
            'code' => 'required|string',
            'order_id' => 'required'
        ];
    }

    public function restaurant_comment(Request $request)
    {
        $validation = Validator::make($request->all(), $this->restaurant_comment2());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        $save = Restaurant::where("id", $request->restaurant_id)->first();

        if ($save == null) {
            return parent::errorjson("Failer Created", 400);
        }

        $save2 = new RestaurantReview();
        $save2->restaurant_id = $request->restaurant_id;
        $save2->comment = $request->comment;
        $save2->client_name = $request->client_name;
        $save2->client_phone = $request->client_phone;
        $save2->star = $request->star;
        $save2->save();

        return parent::successjson("Commented Created", 200);
    }


    private function restaurant_comment2()
    {
        return [
            'comment' => 'required|string',
            'client_name' => 'required|string',
            'client_phone' => 'required|string',
            'restaurant_id' => 'required',
            'star' => 'required|in:1,2,3,4,5',
        ];
    }

    public function client_comment(Request $request)
    {
        $items = RestaurantReview::orderby("id", "asc")->where("client_name", $request->client_name)->get();
        return parent::successjson($items, 200);
    }

    public function createPosOrder(Request $request)
    {
        $this->validate($request, [
            'restaurant_id' => 'required|exists:restaurant,id',
            'payment_type' => 'required',
            'products' => 'required'
        ]);
        $restaurant = Restaurant::where('id', $request->restaurant_id)->first();

        if (!$restaurant) {
            return response()->json(['error' => 'Invalid restaurant'], 400);
        }
        $order = Order::where('id', $request->order_id)->first();
        if (!$order) {
            $order = Order::create([
                'order_id' => uniqid(),
                'client_name' => $request->name ?? '-----',
                'phone' => $request->phone ?? '-----',
                'ip' => parent::IP_Address(),
                'phone_active' => 1,
                'total' => 0,
                'status' => 2,
                'payment_type' => $request->payment_type,
                'city_id' => $restaurant->restaurant_city,
                'restaurant_id' => $restaurant->id,
                'user_id' => $restaurant->user_id,
                'code' => 0,
                'b_view' => 1,
                'is_pickup' => 1
            ]);
        } else {
            $order->update([
                'client_name' => $request->name ?? '-----',
                'phone' => $request->phone ?? '-----',
                'ip' => parent::IP_Address(),
                'phone_active' => 1,
                'total' => 0,
                'status' => 2,
                'payment_type' => $request->payment_type,
                'code' => 0,
                'b_view' => 1
            ]);
        }
        OrderProducts::where('order_id', $order->id)->delete();

        $price = 0;
        $feature = 0;
        foreach ($request->products as $product) {
            $order_product = OrderProducts::create([
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'products_id' => $product['products_id'],
                'price' => $product['amount'],
                'qun' => $product['qun'],
                'amount' => $product['amount'],
                'total' => $product['amount'] * $product['qun'],
                'special_request' => $product['special_request'] ?? ''
            ]);
            $price += $product['amount'] * $product['qun'];

            if ($product['feature'] != 0 && count($product['feature']) != 0) {
                foreach ($product['feature'] as $key => $value) {
                    if (ProductsFeature::where('id', $value)->count() <= 0) continue;
                    OrderProductsFeature::create([
                        'order_products_id' => $order_product->id,
                        'products_feature_id' => $value
                    ]);
                    $fr = ProductsFeature::where('id', $value)->first();
                    $feature += $fr->amount;
                }
            }
        }
        $order->update(['total' => $price + $feature]);

        return response()->json(['description' => 'Order Placed Successfully', 'order_id' => $order->id]);
    }
    
    
    
    
    
    public function createPosOrderNew(Request $request)
    {
        $this->validate($request, [
            'restaurant_id' => 'required|exists:restaurant,id',
            'products' => 'required'
        ]);
        $restaurant = Restaurant::where('id', $request->restaurant_id)->first();

        if (!$restaurant) {
            return response()->json(['error' => 'Invalid restaurant'], 400);
        }
        $order = Order::where('id', $request->order_id)->first();
        if (!$order) {
            $order = Order::create([
                'order_id' => uniqid(),
                'client_name' => $request->name ?? '-----',
                'phone' => $request->phone ?? '-----',
                'car_number' => $request->car_or_table_number ?? '',
                'ip' => parent::IP_Address(),
                'phone_active' => 1,
                'total' => 0,
                'status' => 1,
                'payment_type' => $request->payment_type ?? '1',
                'city_id' => $restaurant->restaurant_city,
                'restaurant_id' => $restaurant->id,
                'user_id' => $restaurant->user_id,
                'code' => 0,
                'b_view' => 1,
                'is_pickup' => 1
            ]);
        } else {
            $order->update([
                'client_name' => $request->name ?? '-----',
                'phone' => $request->phone ?? '-----',
                'ip' => parent::IP_Address(),
                'phone_active' => 1,
                'total' => 0,
                'status' => 2,
                'payment_type' => $request->payment_type,
                'code' => 0,
                'b_view' => 1
            ]);
        }
        OrderProducts::where('order_id', $order->id)->delete();

        $price = 0;
        $feature = 0;
        foreach ($request->products as $product) {
            $order_product = OrderProducts::create([
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'products_id' => $product['products_id'],
                'price' => $product['amount'],
                'qun' => $product['qun'],
                'amount' => $product['amount'],
                'total' => $product['amount'] * $product['qun'],
                'special_request' => $product['special_request'] ?? ''
            ]);
            $price += $product['amount'] * $product['qun'];

            if ($product['feature'] != 0) {
                if (count($product['feature']) != 0) {
                    foreach ($product['feature'] as $key => $value) {
                        $order_products_feature = new OrderProductsFeature();
                        $order_products_feature->order_products_id = $order_product->id;
                        $order_products_feature->products_feature_id = $value["id"];
                        $order_products_feature->quantity = $value["quantity"];
                        $order_products_feature->save();

                        $fr = ProductsFeature::where("id", $value["id"])->first();
                        if ($fr == null) {
                            return parent::errorjson("Failer Created", 400);
                        }
                         $feature = $feature + ($fr->amount * $value["quantity"]);
                    }
                }
            }
        }
        $order->update(['total' => $price + $feature]);

        return response()->json(['description' => 'Order Placed Successfully', 'order_id' => $order->id]);
    }
    
    
    
    public function SaveOrderNew(Request $request){
        $validation = Validator::make($request->all(), $this->add_cars1());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        
        $restaurant = Restaurant::where("id", $request->restaurant_id)->first();

        if ($restaurant == null) {
            return parent::errorjson("Failer Created. Invalid restaurant", 400);
        }

        $city_id = City::where("id", $request->city_id)->first();

        if ($city_id == null) {
            return parent::errorjson("Failer Created Invalid city", 400);
        }
        
        if ($city_id == null) {
            return parent::errorjson("Failer Created Invalid city", 400);
        }

        /*$save = Order::where("ip", parent::IP_Address())->where("status", 0)->first();
        if ($request->order_id) {
            $save = Order::where('id', $request->order_id)->first();
        }*/
        
        //if ($save == null)
        {
            $save = new Order();
            $save->restaurant_id = $request->restaurant_id;
            $save->user_id = $request->user_id;
            $save->house_number = $request->houseNo;
            $save->order_id = uniqid();
            $save->city_id = $request->city_id;
            $save->ip = parent::IP_Address();
            $save->phone_active = 0;
            $save->delivery_fee = $restaurant->fees;
            $save->original_delivery_fee = $restaurant->fees;
            $save->total = 0;
            $save->status = 0;
            $save->payment_type = $request->payment_type;
            $save->log = $request->address["log"];
            $save->lat = $request->address["lat"];
            $save->is_pickup = $request->self_pickup == 1;
            
            if($request->DiscountAmount > 0){
                $save->discount_code = $request->DiscountCode;
                $save->discounted_amount = $request->DiscountAmount;
            }else{
                $save->discount_code = "";
                $save->discounted_amount = 0;
            }
            
            $save->client_name = $request->name;
            $save->phone = $request->phone;
            if(isset($request->device_id)){
            $save->device_id = $request->device_id;
            }
            
            $save->status = 1;
            $verifyCode = mt_rand(100000, 999999);
            $save->code = $verifyCode;
            $save->phone_active = 1;
            
            $save->save();
        }
        $order_id = $save->id;
        OrderProducts::where('order_id', $save->id)->delete();
        $price = 0;
        $feature = 0;
        $products = $request->products;
        foreach ($products as $product) {
            $order_products = new OrderProducts();
            $order_products->order_id = $order_id;
            $order_products->restaurant_id = $request->restaurant_id;
            $order_products->products_id = $product['products_id'];
            $order_products->qun = $product['qun'];
            $order_products->price = $product['amount'];
            $order_products->total = $product['amount'] * $product['qun'];
            $order_products->special_request = isset($product["special_request"]) ? $product["special_request"] : "";
            $order_products->save();
            $price += $product['amount'] * $product['qun'];

            //save new price
            if ($product['feature'] != 0) {
                if (count($product['feature']) != 0) {
                    foreach ($product['feature'] as $key => $value) {
                        $order_products_feature = new OrderProductsFeature();
                        $order_products_feature->order_products_id = $order_products->id;
                        $order_products_feature->products_feature_id = $value["id"];
                        $order_products_feature->quantity = $value["quantity"];
                        $order_products_feature->save();

                        $fr = ProductsFeature::where("id", $value["id"])->first();
                        if ($fr == null) {
                            return parent::errorjson("Failer Created", 400);
                        }
                         $feature = $feature + ($fr->amount * $value["quantity"]);
                    }
                }
            }
        }
        $save->total = $price + $feature + ($request->self_pickup == 1 ? 0 : $restaurant->fees);
        $save->save();
        
        $data = array(
            "description" => "Order Placed Successfully",
            "order_id" => $order_id
        );
        
        
        $message = "Mr. " . $save->client_name . " Your order is proceeded successfully.\nExpect a Call from the restaurant shortly.\nOrder Number: " . $save->id . "\nTotal bill is: " . $save->total . "\nThank You for using Sandwich Map.";
        Common::SendTextSMS($save->phone, $message);
        
        $msg = "Dear Partner You Have One New Order\nCustomer name: " . $save->client_name . "\nMobile Number: " . $save->phone . "\nTotal Bill: " . $save->total . "\nThank you for using Sandwich Map";
        $user = User::where('id', $save->user_id)->first();
        Common::SendTextSMS($user->phone, $msg);
        
        $NewNoti = new Notification();
        $NewNoti->users = $request->user_id;
        $NewNoti->type = "Order";
        $NewNoti->notification_app = "SandwichMenu";
        $NewNoti->connected_id = $save->id;
        $NewNoti->image = $save->restaurant->user->avatar;
        $NewNoti->title = "You Have One New Order";
        $NewNoti->description = "Order #".$save->id."";
        $NewNoti->save();
        
        $curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL, URL("/")."/api/send-order-email/".$order_id);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);
        
        return parent::successjson($data, 200);
    }
    
    public function SendOrderEmail($ID){
        $order = Order::find($ID);
        
        $CountOrder = Order::where("status", 1)->where("restaurant_id", $order->restaurant->id)->count();
        $GetFCM = FCM::where("device_id", $order->restaurant->id)->where("app_type", "SandwichMenu")->get();
        
        foreach($GetFCM as $GAT){
            $NewExtData["OrderID"] = $ID;
            $NewExtData["UnreadBadge"] = $CountOrder;
            
            $this->SendNotificationSendPushNotification($GAT->token, "Dear Partner You Have One New Order", "Order #".$ID." Please Open the Orders page", 1, "Orders", $NewExtData);
        }
        
        if($order->is_pickup != 1){
            $GetAllDrivers = MyDriver::select("id")->where("city", $order->city_id)->pluck("id")->toArray();
            $GetFCM = FCM::whereIn("device_id", $GetAllDrivers)->where("app_type", "SandwichMenuDriver")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $ID;
                $NewExtData["UnreadBadge"] = 0;
                
                $NewNoti = new Notification();
                $NewNoti->users = $GAT->device_id;
                $NewNoti->notification_app = "SandwichMenuDriver";
                $NewNoti->type = "Order";
                $NewNoti->connected_id = $ID;
                $NewNoti->image = $order->restaurant->user->avatar;
                $NewNoti->title = "New Order";
                $NewNoti->description = "Order #".$ID."";
                $NewNoti->save();
                
                $this->SendNotificationSendPushNotification($GAT->token, "New Order", "Dear All there is One New Order", 1, "Orders", $NewExtData);
            }
        }
        
        $this->sendEmail($order);
    }
}
