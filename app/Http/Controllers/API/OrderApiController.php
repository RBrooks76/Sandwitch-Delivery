<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Driver;
use App\Order;
use App\OrderLog;
use App\Coupon;
use App\FCM;
use App\OrderProducts;
use App\OrderProductsFeature;
use App\ProductsFeature;
use App\Http\Controllers\Dashboard\Common;
use App\User;
use App\Events\StatusLiked;
use App\Notification;
use App\MyDriver;
use App\DriverComment;

use Carbon\Carbon;

use Mail;
use PDF;

class OrderApiController extends Controller
{
    public function EmailTest(){
        Common::EmailTest("milkycookies2020@gmail.com", "New Order Email", "New Order Received", "");
    }
    
    public function getUnreadCounter(Request $request){
        $Notifications = Notification::where('users', $request->id)->where("unread", 0)->count();
        $Orders = Order::where("user_id", $request->id)->where("status", 1)->count();
        
        $data = array(
            "description" => "Order Placed Successfully",
            "order_id" => $Notifications
        );
        
        return parent::successjson($data, 200);
    }
    
    public function getUnreadOrders(Request $request){
        $Orders = Order::where("user_id", $request->id)->where("status", 1)->count();
        
        $data = array(
            "description" => "Order Placed Successfully",
            "order_id" => $Orders
        );
        
        return parent::successjson($data, 200);
    }
    
    
    public function StoreOrder(Request $request)
    {
        $orderFoods = $request->orderFoods;
        $product_id = '';
        if($orderFoods) {
            $order = new Order();
            $order->phone = $request->orderMobile;
            $order->order_id = $request->orderId;
            $order->lat = $request->orderLat;
            $order->log = $request->orderLon;
            $order->total = $request->totalBill;
            $order->payment_type = $request->orderPayment;
            $order->created_at = $request->orderDate;
            $order->updated_at = $request->orderDate;
            $order->city_id = $request->orderCity;
            $order->client_name = $request->orderClientName;
            $order->restaurant_id = $request->foodRestaurantId;
            $order->user_id = $request->orderUserId;
            $order->save();
            for($i = 0; $i < count($orderFoods); $i++)
            {
                $food = $orderFoods[$i];
                $product = new OrderProducts();
                $product->price = $food['foodPrice'];
                $product->qun = $food['foodCount'];
                $product->restaurant_id = $request->foodRestaurantId;
                $product->products_id = $food['foodId'];
                $product->total = $food['foodTotal'];
                $product->order_id = $order->id;
                $product->created_at = $request->orderDate;
                $product->updated_at = $request->orderDate;
                $product->save();
                if(key_exists('foodFeatures', $food)) {
                    $features = $food['foodFeatures'];
                    $featureArray = explode('-', $features);
                    if(!empty($featureArray)) {
                        foreach($featureArray as $id) {
                            $productFeature = new OrderProductsFeature();
                            $productFeature->order_products_id = $product->id;
                            $productFeature->products_feature_id = $id;
                            $productFeature->created_at = $request->orderDate;
                            $productFeature->updated_at = $request->orderDate;
                            $productFeature->save();
                        }

                    }
                }

            }
                $user = User::where('id', $order->user_id)->first();
                
                $to = $user->email;
                $subject = "SandwichMap - new Order# ".$order->id;
                $message = "You have new order
                            Order# ".$order->id.
                            '
                            Please login to your restaurant dashboard and precess the order.
                            It’s your chance to show your customer the best you can do 
                            Good Luck 
                            Sincerely,
                            Your partner 
                            Sandwich Map Support Team';
                $headers = "From:   noreply@sandwhichmap.net" . "\r\n" ;
                Common::SendTextSMS($user ->phone, $message);
                Common::SendEmail($to,$subject,$message,$headers);
                event(new StatusLiked('hello world'));
                
            return response()->json(["order_id"=>$order->id]);
            
        }
        else {
            return response()->json(["fail"=>"Invalid request"]);
        }

    }

    public function SendVerifySMS(Request $request){
        $orderMobile = $request->orderMobile;
        $orderID = $request->orderID;
        $orderUserName = $request->orderUserName;

        $verifyCode =mt_rand(100000, 999999);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://api.smscountry.com/SMSCwebservice_bulk.aspx', [
            'form_params'  => [
                "User"         => "Sandwichmap",
                "passwd"       => "10495477",
                "mobilenumber" => $orderMobile,
                "message"      => "Hello ".$orderUserName." Your verification code to order is ".$verifyCode,
                "sid"          => "AD-Telebu",
                "mtype"        => "N",
                "DR"           => "Y",
            ]
        ]);

        if ($response->getStatusCode() === 200) {

            /*DB::table('phone_verify')
                            ->insert([
                                    'phone' => $orderMobile,
                                    'order_id' => $orderID,
                                    'order_username' => $orderUserName,
                                    'verify_code' => $verifyCode
                                ]);*/
            return response()->json(["code"=>$verifyCode]);
        } else {
            return response()->json("no");
        }
    }

    public function CheckVerifyCode(Request $request){
        $orderMobile = $request->orderMobile;
        $orderID = $request->orderID;
        $orderverifycode =  $request->orderverifycode;

        $exist_vefiry_code = DB::table('phone_verify')->where('order_id', $orderID)->get();

        if($verifyCode == $exist_vefiry_code[0]->verify_code) {
            return response()->json("ok");
        }else{
            return response()->json("no");
        }

    }
    
    public function OrderHistory(Request $request){
        $AllData = $request->all();
        
        $GetOrders = Order::with("Items", "OrderLog", "Items.Products", "Items.OrderProductsFeature", "Restaurant", "Restaurant.User")->whereIn("id", $AllData["orders"])->orderBy('id', 'desc')->get();
        
        foreach($GetOrders as $ord){
            $ord->phone_active = $ord->phone_active."";
            $ord->status = $ord->status."";
            $ord->payment_type = $ord->payment_type."";
            $ord->city_id = $ord->city_id."";
            $ord->restaurant_id = $ord->restaurant_id."";
            $ord->user_id = $ord->user_id."";
            $ord->code = $ord->code."";
            $ord->b_view = $ord->b_view."";
            $ord->is_pickup = $ord->is_pckup."";
            
            $ord->send_driver = $ord->send_driver."";
            $ord->UpdateMyOrder = $ord->UpdateMyOrder."";
            $ord->driver_status = $ord->driver_status;
            $ord->panelty_level = $ord->panelty_level."";
            $ord->delivery_time = $ord->delivery_time."";
            
            $ord->restaurant->user_id = $ord->restaurant->user_id."";
            $ord->restaurant->restaurant_city = $ord->restaurant->restaurant_city."";
            $ord->restaurant->priority = $ord->restaurant->priority."";
            $ord->restaurant->status = $ord->restaurant->status."";
            $ord->restaurant->all_priority = $ord->restaurant->all_priority."";
            $ord->restaurant->restaurant_category = $ord->restaurant->restaurant_category."";
            $ord->restaurant->user = $ord->restaurant->user;
            
            
            $TotalAddOnPrice = 0;
            foreach($ord->items as $key => $items){
                $items->qun = $items->qun."";
                $items->restaurant_id = $items->restaurant_id."";
                $items->products_id = $items->products_id."";
                $items->order_id = $items->order_id."";
                $items->products = $items->products;
                
                $items->order_products_feature = $items->OrderProductsFeature;
                foreach($items->order_products_feature as $Fetch){
                    $TotalAddOnPrice += $Fetch->quantity * $Fetch->ProductsFeature->amount;
                    $Fetch->products_feature = $Fetch->ProductsFeature;
                    $Fetch->quantity = $Fetch->quantity."";
                }
                
                
                $items->products->restaurant_id = $items->products->restaurant_id."";
                $items->products->priority = $items->products->priority."";
            }
            
            $ord->TotalAdOns = $TotalAddOnPrice."";
            $ord->DeliveryFee = $ord->restaurant->fees."";
            
            foreach($ord->orderLog as $log){
                $log->log_order_id = $log->log_order_id."";
                $log->type = $log->type."";
            }
        }
        
        return parent::successjson($GetOrders, 200);
    }
    
    public function CancelOrder(Request $request){
        $Order = Order::find($request->ID);
        $Order->status = 6;
        $Order->save();
        
        return parent::successjson(array(), 200);
    }


    public function ResturantOrderHistory(Request $request){
        $AllData = $request->all();
        $Offset = $AllData["page"] * 10;
        
        $GetTotalToday = Order::where("restaurant_id", $AllData["id"])->where("created_at", ">=", date("Y-m-d 00:00:00"))->where("status", 5)->where("created_at", "<=", date("Y-m-d 23:59:59"))->sum("total");
        $GetOrders = Order::with("Items", "OrderLog", "Items.Products", "Items.OrderProductsFeature", "Restaurant", "Restaurant.User")->where("restaurant_id", $AllData["id"])->orderBy('id', 'desc')->take(10)->offset($Offset)->get();
        
        foreach($GetOrders as $ord){
            $ord->phone_active = $ord->phone_active."";
            $ord->status = $ord->status."";
            $ord->TotalAmount = $GetTotalToday."";
            $ord->payment_type = $ord->payment_type."";
            $ord->city_id = $ord->city_id."";
            $ord->restaurant_id = $ord->restaurant_id."";
            $ord->user_id = $ord->user_id."";
            $ord->code = $ord->code."";
            $ord->pickup_sent = $ord->pickup_sent."";
            $ord->b_view = $ord->b_view."";
            $ord->lat = $ord->lat."";
            $ord->log = $ord->log."";
            $ord->is_pickup = $ord->is_pickup."";
            $ord->send_driver = $ord->send_driver."";
            $ord->UpdateMyOrder = $ord->UpdateMyOrder."";
            $ord->driver_status = $ord->driver_status;
            $ord->panelty_level = $ord->panelty_level."";
            $ord->delivery_time = $ord->delivery_time."";
            
            $Time = $ord->created_at;
            $NewDT = date("Y-m-d", strtotime($Time));
            
            $DTString = $NewDT;
            if($NewDT == date("Y-m-d")){
                $DTString = "Today";
            }
            
            if($NewDT == date("Y-m-d", strtotime("-1 day"))){
                $DTString = "Yesterday";
            }
            
            $ord->Time = $DTString . " " . date("h:i A", strtotime($Time));
            
            $ord->restaurant->user_id = $ord->restaurant->user_id."";
            $ord->restaurant->restaurant_city = $ord->restaurant->restaurant_city."";
            $ord->restaurant->priority = $ord->restaurant->priority."";
            $ord->restaurant->status = $ord->restaurant->status."";
            $ord->restaurant->all_priority = $ord->restaurant->all_priority."";
            $ord->restaurant->restaurant_category = $ord->restaurant->restaurant_category."";
            $ord->restaurant->user = $ord->restaurant->user;
            
            $TotalAddOnPrice = 0;
            
            foreach($ord->items as $key => $items){
                $items->qun = $items->qun."";
                $items->restaurant_id = $items->restaurant_id."";
                $items->products_id = $items->products_id."";
                $items->order_id = $items->order_id."";
                $items->products = $items->products;
                $items->order_products_feature = $items->OrderProductsFeature;
                foreach($items->order_products_feature as $Fetch){
                    $TotalAddOnPrice += $Fetch->quantity * $Fetch->ProductsFeature->amount;
                    $Fetch->quantity = $Fetch->quantity."";
                    $Fetch->products_feature = $Fetch->ProductsFeature;
                }
                $items->products->restaurant_id = $items->products->restaurant_id."";
                $items->products->priority = $items->products->priority."";
            }
            
            $ord->TotalAdOns = $TotalAddOnPrice."";
            $ord->DeliveryFee = $ord->restaurant->fees."";
            
            foreach($ord->orderLog as $log){
                $log->log_order_id = $log->log_order_id."";
                $log->type = $log->type."";
            }
        }
        
        return parent::successjson($GetOrders, 200);
    }
    
    function codexworldGetDistanceOpt($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
        $rad = M_PI / 180;
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad) * sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad)* cos($latitudeTo * $rad) * cos($theta * $rad);
        return acos($dist) / $rad * 60 *  1.853;
    }
    
    public function OrderDetail(Request $request){
        $AllData = $request->all();
        
        $GetOrders = Order::with("Items", "OrderLog", "Items.Products", "Items.OrderProductsFeature", "Restaurant", "Restaurant.User")->where("id", $AllData["id"])->get();
        
        foreach($GetOrders as $ord){
            $Distance = 0;
            
            if($ord->lat != null && $ord->lat > 0 && $ord->lat != ""){
                $Distance = $this->codexworldGetDistanceOpt($ord->lat, $ord->log, $ord->restaurant->user->latitude, $ord->restaurant->user->longtitude);
                $Distance = round($Distance, 0);
            }
            
            $CheckRating = DriverComment::where("order_id", $ord->id)->where("AppType", "SandwichMap")->count();
            
            $ord->RateDriver = $CheckRating;
            $ord->distance = (double)$Distance;
            $ord->TotalAmount = "0";
            $ord->Commission = "0";
            $ord->phone_active = $ord->phone_active."";
            $ord->restaurnt_phone = $ord->restaurant->user->landline_number."";
            $ord->status = $ord->status."";
            $ord->pickup_sent = $ord->pickup_sent."";
            $ord->payment_type = $ord->payment_type."";
            $ord->city_id = $ord->city_id."";
            $ord->house = $ord->house_number."";
            $ord->restaurant_id = $ord->restaurant_id."";
            $ord->user_id = $ord->user_id."";
            $ord->code = $ord->code."";
            $ord->b_view = $ord->b_view."";
            $ord->panelty_level = $ord->panelty_level."";
            $ord->is_pickup = $ord->is_pickup."";
            $ord->UpdateMyOrder = $ord->UpdateMyOrder."";
            $ord->delivery_time = $ord->delivery_time."";
            
            
            $ord->send_driver = $ord->send_driver."";
            $ord->driver_status = $ord->driver_status;
            
            $ord->maplink = $ord->restaurant->user->map_link."";
            
            $ord->restaurant->user_id = $ord->restaurant->user_id."";
            $ord->restaurant->restaurant_city = $ord->restaurant->restaurant_city."";
            $ord->restaurant->priority = $ord->restaurant->priority."";
            $ord->restaurant->status = $ord->restaurant->status."";
            $ord->restaurant->all_priority = $ord->restaurant->all_priority."";
            $ord->restaurant->restaurant_category = $ord->restaurant->restaurant_category."";
            $ord->restaurant->user = $ord->restaurant->user;
            
            $TotalAddOnPrice = 0;
            
            foreach($ord->items as $key => $items){
                $items->qun = $items->qun."";
                $items->restaurant_id = $items->restaurant_id."";
                $items->products_id = $items->products_id."";
                $items->order_id = $items->order_id."";
                $items->products = $items->products;
                $items->order_products_feature = $items->OrderProductsFeature;
                foreach($items->order_products_feature as $Fetch){
                    $TotalAddOnPrice += $Fetch->quantity * $Fetch->ProductsFeature->amount;
                    $Fetch->quantity = $Fetch->quantity."";
                    $Fetch->products_feature = $Fetch->ProductsFeature;
                }
                $items->products->restaurant_id = $items->products->restaurant_id."";
                $items->products->priority = $items->products->priority."";
            }
            
            $ord->TotalAdOns = $TotalAddOnPrice."";
            
            if($ord->is_pickup == 0){
                $ord->DeliveryFee = $ord->delivery_fee."";
            }else{
                $ord->DeliveryFee = "0";
            }
            
            foreach($ord->orderLog as $log){
                $log->log_order_id = $log->log_order_id."";
                $log->type = $log->type."";
            }
        }
        return parent::successjson($GetOrders, 200);
    }
    
    public function AcceptOrder(Request $request){
        $item = Order::where("id", $request->order_id)->first();
        if ($item == null) {
            return response()->json(['success' => false]);
        }
        
        $item->status = 5;
        $item->UpdateMyOrder = 0;
        $item->save();

        $message = "Restaurant has Accepted Your Order. Thank you for using SandwichMap";
        //Common::SendTextSMS($item->phone, $message);
        
        $SaveLog = new OrderLog();
        $SaveLog->log_order_id = $request->order_id;
        $SaveLog->message = "Restaurant Has Accepted Your Order";
        $SaveLog->type = 2;
        $SaveLog->save();
        
        $getAllToken = FCM::where("device_id", $item->device_id)->get();
        $Message = "Restaurant Has Accepted Your Order";
        
        $Title = $item->restaurant->user->name." Order #".$request->order_id;
        
        if($item->device_id != ""){
            $NewNoti = new Notification();
            $NewNoti->users = $item->device_id;
            $NewNoti->type = "Order";
            $NewNoti->connected_id = $request->order_id;
            $NewNoti->image = $item->restaurant->user->avatar;
            $NewNoti->title = "Accepted Your Order #".$request->order_id;
            $NewNoti->description = $item->restaurant->user->name . " has accepted your order #".$request->order_id;
            $NewNoti->save();
            
            $CountNoti = Notification::where("users", $item->device_id)->where("unread", 0)->count();
            $NewExtData["UnreadBadge"] = $CountNoti;
            
            foreach($getAllToken as $GAT){
                $this->SendNotificationSendPushNotification($GAT->token, $item->restaurant->user->name." Order #".$request->order_id, $Message, 1, "Order", $NewExtData);
            }
        }
        
        return parent::successjson("Order Accepted Successfully", 200);
    }
    
    public function UpdateMyOrder(Request $request){
        $item = Order::where("id", $request->id)->first();
        $item->UpdateMyOrder = 1;
        $item->save();
        
        $GetFCM = FCM::where("device_id", $item->restaurant->id)->where("app_type", "SandwichMenu")->get();
        
        foreach($GetFCM as $GAT){
            $NewExtData["OrderID"] = $request->id;
            $this->SendNotificationSendPushNotification($GAT->token, "Order Reminder", "Reminder from Customer - Order #".$request->id, 0, "Orders", $NewExtData);
        }
        
        
        return parent::successjson("Order Accepted Successfully", 200);
    }
    
    public function ViewOrder(Request $request){
        $item = Order::where("id", $request->order_id)->first();
        if ($item == null) {
            return response()->json(['success' => false]);
        }

        if (!$item->b_view) {
            $message = "Restaurant has Viewed Your Order. Thank you for using SandwichMap";
            Common::SendTextSMS($item->phone, $message);
            $item->b_view = 1;
            $item->save();
            
            $SaveLog = new OrderLog();
            $SaveLog->log_order_id = $request->order_id;
            $SaveLog->message = "Restaurant Has Viewed Your Order";
            $SaveLog->type = 1;
            $SaveLog->save();
            
            $getAllToken = FCM::where("device_id", $item->device_id)->get();
            $Message = "Restaurant Has Viewed Your Order order";
            
            $Title = $item->restaurant->user->name." Order #".$request->order_id;
            
            foreach($getAllToken as $GAT){
                $this->SendNotificationSendPushNotification($GAT->token, $item->restaurant->user->name." Order #".$request->order_id, $Message, 1, "Order");
            }
            
            $NewNoti = new Notification();
            $NewNoti->users = $item->device_id;
            $NewNoti->type = "Order";
            $NewNoti->connected_id = $request->order_id;
            $NewNoti->image = $item->restaurant->user->avatar;
            $NewNoti->title = "Viewed Your Order #".$request->order_id;
            $NewNoti->description = $item->restaurant->user->name . " has viewed your order #".$request->order_id;
            $NewNoti->save();
        }
        
        return parent::successjson("Order View Successfully", 200);
    }
    
    public function PickupReady(Request $request){
        $item = Order::where("id", $request->order_id)->first();
        if ($item == null) {
            return response()->json(['success' => false]);
        }

        if (!$item->pickup_sent) {
            
            $message = "Dear ".$item->client_name." Your Order No #".$request->order_id." is now ready for Pickup. Please walk inside the shop & collect it. Thank you for using SandwichMap";
            Common::SendTextSMS($item->phone, $message);
            $item->pickup_sent = 1;
            $item->save();
            
            $getAllToken = FCM::where("device_id", $item->device_id)->get();
            $Message = "Your Order is Ready For Pickup";
            
            $Title = $item->restaurant->user->name." Order #".$request->order_id;
            
            foreach($getAllToken as $GAT){
                $this->SendNotificationSendPushNotification($GAT->token, $item->restaurant->user->name." Order #".$request->order_id, $Message, 1, "Order");
            }
            
            $NewNoti = new Notification();
            $NewNoti->users = $item->device_id;
            $NewNoti->type = "Order";
            $NewNoti->connected_id = $request->order_id;
            $NewNoti->image = $item->restaurant->user->avatar;
            $NewNoti->title = "Your Order #".$request->order_id." is ready for Pickup";
            $NewNoti->description = $item->restaurant->user->name . " order #".$request->order_id." is ready for pickup";
            $NewNoti->save();
        }
        
        return parent::successjson("Order View Successfully", 200);
    }
    
    public function RejectedOrder(Request $request){
        $item = Order::where("id", $request->order_id)->first();
        if ($item == null) {
            return response()->json(['success' => false]);
        }
        $item->status = 3;
        $item->UpdateMyOrder = 0;
        $item->save();

        $message = "Restaurant has Rejected Your Order 
Due to none available stock 
or Fully Booked deliveries 
Please try another restaurants or Check the offers Page. 
Thank you for using SandwichMap ";

        Common::SendTextSMS($item->phone, $message);
        
        
        $SaveLog = new OrderLog();
        $SaveLog->log_order_id = $request->order_id;
        $SaveLog->message = "Restaurant Has Rejected Your Order";
        $SaveLog->type = 2;
        $SaveLog->save();
        
        $getAllToken = FCM::where("device_id", $item->device_id)->get();
        $Message = "Restaurant Has Rejected Your Order";
        
        $Title = $item->restaurant->user->name." Order #".$request->order_id;
        
        if($item->device_id != ""){
        $NewNoti = new Notification();
        $NewNoti->users = $item->device_id;
        $NewNoti->type = "Order";
        $NewNoti->connected_id = $request->order_id;
        $NewNoti->image = $item->restaurant->user->avatar;
        $NewNoti->title = "Rejected Your Order #".$request->order_id;
        $NewNoti->description = $item->restaurant->user->name . " has rejected your order #".$request->order_id;
        $NewNoti->save();
        
        $CountNoti = Notification::where("users", $item->device_id)->where("unread", 0)->count();
        $NewExtData["UnreadBadge"] = $CountNoti;
        
        foreach($getAllToken as $GAT){
            $this->SendNotificationSendPushNotification($GAT->token, $item->restaurant->user->name." Order #".$request->order_id, $Message, 1, "Order", $NewExtData);
        }
            
        }

        return parent::successjson("Order Rejected Successfully", 200);
    }
    
    public function DriverList(Request $request){
        $Bike = Driver::where('restaurant_id', $request->id)->where("vehicle_type", "Bike driving license")->get();
        $Car = Driver::where('restaurant_id', $request->id)->where("vehicle_type", "Car driving license")->get();
        
        foreach($Bike as $BK){
            $BK->phone_number = $BK->phone_number."";
            $BK->restaurant_id = $BK->restaurant_id."";
        }
        
        foreach($Car as $CK){
            $CK->phone_number = $CK->phone_number."";
            $CK->restaurant_id = $CK->restaurant_id."";
        }
        
        return parent::successjson(["Bike" => $Bike, "Car" => $Car], 200);
    }
    
    public function GetNotificationList(Request $request){
        $NewArray = array();
        $Sections = array();
        
        Notification::where('users', $request->id)->update(["unread" => 1]);
        
        $Notifications = Notification::where('users', $request->id)->orderBy("id", "DESC");
        
        if(isset($request->App)){
            $Notifications = $Notifications->where("notification_app", $request->App);
        }
        
        $Notifications = $Notifications->get();
        
        foreach($Notifications as $NT){
            $DT = date("M, Y", strtotime($NT->created_at));
            
            if(date("Y-m-d", strtotime($NT->created_at)) == date("Y-m-d")){
               $DT = "Today";
            }else if(date("Y-m-d", strtotime($NT->created_at)) == date("Y-m-d", strtotime("-1 day"))){
               $DT = "Yesterday";
            }else if(date("Y-m-w", strtotime($NT->created_at)) > date("Y-m-w")){
               $DT = "This Week";
            }else if(date("m-Y", strtotime($NT->created_at)) == date("m-Y")){
               $DT = "This Month";
            }
            
            $Time = $NT->created_at;
            $NewDT = date("Y-m-d", strtotime($Time));
            
            $DTString = $NewDT;
            if($NewDT == date("Y-m-d")){
                $DTString = "Today";
            }
            
            if($NewDT == date("Y-m-d", strtotime("-1 day"))){
                $DTString = "Yesterday";
            }
            
            $NT->Time = $DTString . " " . date("h:i A", strtotime($Time));
            
            $NT->Dated = $DT;
            $Sections[] = $DT;
        }
        
        
        
        $Sections = array_unique($Sections);
        $Sections = array_values($Sections);
        
        return json_encode(["Data" => $Notifications, "Section" => $Sections]);
    }
    
    public function SendOrderToDriver(Request $request){
        $order = Order::where('id', $request->OrderID)->first();
        $order->send_driver = 1;
        $order->save();
        
        $driver = Driver::find($request->DriverID);
        
        if (!$driver || !$order) return redirect()->back()->with('error', 'Driver does not exist');

        $message = "Dear " . $driver->name . " You Have One New Delivery\nOrder Number: " . sprintf("%05d", $order->id) . "\n";
        $message .= "Shop name: " . $order->restaurant->user->name . "\n";
        $message .= "Shop Location: " . $order->restaurant->user->map_link . "\n";
        $message .= "Customer name: " . $order->client_name . "\n";
        $message .= "Customer Mobile Number: " . $order->phone . "\n";
        $message .= "Total Bill: " . $order->total . "\n";
        $message .= "Customer Location: https://maps.google.com/maps/search/?api=1&query=" . $order->log . "," . $order->lat . "\n";
        $message .= "Thank you for using Sandwich Map";
        
        $EmailMessage = $message;

        $result_1 = Common::SendTextSMS($driver->phone_number, $message);

        $message = "Mr. " . $order->client_name . " Your Order Has Been Collected By the Restaurant Driver\n";
        $message .= "Shop name: " . $order->restaurant->user->name . "\n";
        $message .= "Driver name: " . $driver->name . "\n";
        $message .= "Driver ID: " . sprintf("%05d", $driver->id) . "\n";
        $message .= "Driver Mobile Number: " . $driver->phone_number . "\n";
        $message .= "Thank You for using Sandwich Map";

        $result_2 = Common::SendTextSMS($order->phone, $message);
        
        
        $products = OrderProducts::with('Products')->with("OrderProductsFeature.ProductsFeature")->where("order_id", $request->OrderID)->get();

        $sub_total = floatval($order->total);
        $delivery_fee = floatval($order->restaurant->fees);
        if (!$order->is_pickup) $sub_total -= $delivery_fee;

        $sub_total = sprintf("%.2f", $sub_total);
        $delivery_fee = sprintf("%.2f", $delivery_fee);

        foreach ($products as &$item) {
            $addon_ids = OrderProductsFeature::with("ProductsFeature")->where('order_products_id', $item->id)->get();
            $item->product_addons = $addon_ids;
        }
        
        if($order->device_id != ""){
        $NewNoti = new Notification();
        $NewNoti->users = $order->device_id;
        $NewNoti->type = "Order";
        $NewNoti->connected_id = $request->OrderID;
        $NewNoti->image = $order->restaurant->user->avatar;
        $NewNoti->title = "Driver Picked Your Order #".$request->OrderID;
        $NewNoti->description = $order->restaurant->user->name . " send order to driver. Order #".$request->OrderID;
        $NewNoti->save();
        
        $getAllToken = FCM::where("device_id", $order->device_id)->get();
        $Message = "Order Has Been Taken by Driver";
        
        $Title = $order->restaurant->user->name." Order #".$request->OrderID;
        
        foreach($getAllToken as $GAT){
            $this->SendNotificationSendPushNotification($GAT->token, $order->restaurant->user->name." Order #".$request->OrderID, $Message, 1, "Order");
        }
        }
        
        $data = array("Message" => $EmailMessage);
        
        Mail::send("EmailTemplates.General", $data, function ($m) use ($order, $driver) {
            $m->from($order->restaurant->user->email, $order->restaurant->user->name);
            $m->to($driver->email)->subject("You Have One New Delivery. Order Number: " . sprintf("%05d", $order->id));
        });

        return parent::successjson("Order Send To Driver Successfully", 200);
    }
    
    public function VerifyCoupon(Request $request){
        logger($request->code);
        $CheckCode = Coupon::where("code", $request->code)->count();
        
        if($CheckCode == 0){
            return response()->json(["fail" => "Invalid Coupon Code"]);
        }
        
        $CheckCode = Coupon::where("code", $request->code)->first();
        
        if($CheckCode->coupon_status == 0){
            return response()->json(["fail" => "Coupon Expired"]);
        }
        
        if($CheckCode->expiry_date < date("Y-m-d")){
            return response()->json(["fail" => "Coupon Expired"]);
        }
        
        $Amount = ($request->amount * $CheckCode->amount) / 100;
        return response()->json(["success" => $Amount.""]);
    }
    
    public function viewPrintOrder(Request $request){
        $order = Order::where("id", $request->ID)->first();
        $products = OrderProducts::with('Products')->with("OrderProductsFeature.ProductsFeature")->where("order_id", $request->ID)->get();

        $sub_total = floatval($order->total);
        $delivery_fee = floatval($order->restaurant->fees);
        if (!$order->is_pickup) $sub_total -= $delivery_fee;

        $sub_total = sprintf("%.2f", $sub_total);
        $delivery_fee = sprintf("%.2f", $delivery_fee);

        foreach ($products as &$item) {
            $addon_ids = OrderProductsFeature::with("ProductsFeature")->where('order_products_id', $item->id)->get();
            $item->product_addons = $addon_ids;
        }
        
        if($order->is_pickup == 1){
            $delivery_fee = sprintf("%.2f", "0");
        }
        
        $filename = "InvoicePDF/".$request->ID.".pdf";
        
        $pdf = PDF::loadView('print', compact('order', 'products', 'sub_total', 'delivery_fee'));
        \Storage::put($filename, $pdf->output());
        
        return parent::successjson(URL('/')."/storage/app/".$filename, 200);
        //return view('print', compact('order', 'products', 'sub_total', 'delivery_fee'));
    }
    
    public function ViewInvoice(Request $request){
        $order = Order::where("id", $request->ID)->first();
        $products = OrderProducts::with('Products')->with("OrderProductsFeature.ProductsFeature")->where("order_id", $request->ID)->get();

        $sub_total = floatval($order->total);
        $delivery_fee = floatval($order->restaurant->fees);
        if (!$order->is_pickup) $sub_total -= $delivery_fee;

        $sub_total = sprintf("%.2f", $sub_total);
        $delivery_fee = sprintf("%.2f", $delivery_fee);
        
        
        if($order->is_pickup == 1){
            $delivery_fee = sprintf("%.2f", "0");
        }
        
        

        foreach ($products as &$item) {
            $addon_ids = OrderProductsFeature::with("ProductsFeature")->where('order_products_id', $item->id)->get();
            $item->product_addons = $addon_ids;
        }
        return view('print', compact('order', 'products', 'sub_total', 'delivery_fee'));
    }
    
    public function TodayOrderSummary(Request $request){
        $order = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->orderBy("id")->where("status", 5)->get()->toArray();
        
        $CategoryArray = array();
        $AddOnTotal = array();
        
        $FirstOrder = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->where("status", 5)->orderBy("id")->first();
        $LastOrder = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->where("status", 5)->orderBy("id")->get()->last();
        
        $FirstOrderDate = date("d-m-Y H:i:s", strtotime(@$FirstOrder->created_at));
        $LastOrderDate = date("d-m-Y H:i:s", strtotime(@$LastOrder->created_at));
        $FirstOrderNo = @$FirstOrder->id;
        $LastOrderNo = @$LastOrder->id;
        
        $TotalOrders = "";
        $PickupOrders = 0;
        $DeliveryOrder = 0;
        $CashOrders = 0;
        $CardOrder = 0;
        $OnlineOrder = 0;
        $PickupOrderSales = 0;
        $DeliveryOrderSales = 0;
        $CashSales = 0;
        $CardSales = 0;
        $OnlineSales = 0;
        
        foreach($order as $ORD){
            $TotalOrders++;
            if($ORD["is_pickup"] == 1){
                $PickupOrders++;
                $PickupOrderSales += $ORD["total"];
            }else{
                $DeliveryOrder++;
                $DeliveryOrderSales += $ORD["total"];
            }
            
            if($ORD["payment_type"] == 1){
                $CashOrders++;
                $CashSales += $ORD["total"];
            }
            
            if($ORD["payment_type"] == 2){
                $CardOrder++;
                $CardSales += $ORD["total"];
            }
            
            if($ORD["payment_type"] == 3){
                $CashOrders++;
                $OnlineSales += $ORD["total"];
            }
            
            $products = OrderProducts::with('Products')->with("Products.ProductsCategory")->with("Products.ProductsCategory.SubCategory")->with("OrderProductsFeature.ProductsFeature")->where("order_id", $ORD['id'])->get()->toArray();
            foreach($products as $Prod){
                $CategoryArray[$Prod["products"]["products_category"][0]["sub_category"]["name"]][$Prod["products"]["id"]][] = array("name" => $Prod["products"]["name"], "amount" => $Prod["total"]);
                
                foreach($Prod["order_products_feature"] as $fet){
                    $AddOnTotalArray[$fet["products_feature"]["products_id"]][] = $fet["quantity"] * $fet["products_feature"]["amount"];
                }
            }
        }
        
        $NewCatArray = array();
        
        foreach($CategoryArray as $CatName => $Array){
            $NewArr = array();
            foreach($Array as $ProdID => $Val){
                $Total = 0;
                $Name = "";
                $AddOnTotal = 0;
                
                if(isset($AddOnTotalArray[$ProdID])){
                    foreach($AddOnTotalArray[$ProdID] as $Am){
                        $AddOnTotal += $Am;
                    }
                }
                
                foreach($Val as $Pro){
                    $Total += $Pro["amount"];
                    $Name = $Pro["name"];
                }
                $NewArr[] = array("Name" => $Name, "Amount" => $Total, "AdOns" => $AddOnTotal);
            }
            $NewCatArray[$CatName] = $NewArr;
        }
        
        $order = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->first();
        $RestName = @$order->restaurant->user->name;
        $CityName = @$order->City->name;
        
        return view('OrderSummary', compact('NewCatArray', "RestName", "CityName", "OnlineSales", 'FirstOrderDate', 'LastOrderDate', 'FirstOrderNo', "LastOrderNo", "TotalOrders", "PickupOrders", "DeliveryOrder", "CashOrders", "CardOrder", "OnlineOrder", "PickupOrderSales", "DeliveryOrderSales", "CardSales", "CashSales"));
    }
    
    public function TodayOrderSummaryPDF(Request $request){
        $order = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->where("status", 5)->orderBy("id")->get()->toArray();
        
        $CategoryArray = array();
        $AddOnTotal = array();
        
        $FirstOrder = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->where("status", 5)->orderBy("id")->first();
        $LastOrder = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->where("status", 5)->orderBy("id")->get()->last();
        
        $FirstOrderDate = date("d-m-Y H:i:s", strtotime($FirstOrder->created_at));
        $LastOrderDate = date("d-m-Y H:i:s", strtotime($LastOrder->created_at));
        $FirstOrderNo = $FirstOrder->id;
        $LastOrderNo = $LastOrder->id;
        
        $TotalOrders = "";
        $PickupOrders = 0;
        $DeliveryOrder = 0;
        $CashOrders = 0;
        $CardOrder = 0;
        $OnlineOrder = 0;
        $PickupOrderSales = 0;
        $DeliveryOrderSales = 0;
        $CashSales = 0;
        $CardSales = 0;
        $OnlineSales = 0;
        
        foreach($order as $ORD){
            $TotalOrders++;
            if($ORD["is_pickup"] == 1){
                $PickupOrders++;
                $PickupOrderSales += $ORD["total"];
            }else{
                $DeliveryOrder++;
                $DeliveryOrderSales += $ORD["total"];
            }
            
            if($ORD["payment_type"] == 1){
                $CashOrders++;
                $CashSales += $ORD["total"];
            }
            
            if($ORD["payment_type"] == 2){
                $CardOrder++;
                $CardSales += $ORD["total"];
            }
            
            if($ORD["payment_type"] == 3){
                $CashOrders++;
                $OnlineSales += $ORD["total"];
            }
            
            $products = OrderProducts::with('Products')->with("Products.ProductsCategory")->with("Products.ProductsCategory.SubCategory")->with("OrderProductsFeature.ProductsFeature")->where("order_id", $ORD['id'])->get()->toArray();
            foreach($products as $Prod){
                $CategoryArray[$Prod["products"]["products_category"][0]["sub_category"]["name"]][$Prod["products"]["id"]][] = array("name" => $Prod["products"]["name"], "amount" => $Prod["total"]);
                
                foreach($Prod["order_products_feature"] as $fet){
                    $AddOnTotalArray[$fet["products_feature"]["products_id"]][] = $fet["quantity"] * $fet["products_feature"]["amount"];
                }
            }
        }
        
        $NewCatArray = array();
        
        foreach($CategoryArray as $CatName => $Array){
            $NewArr = array();
            foreach($Array as $ProdID => $Val){
                $Total = 0;
                $Name = "";
                $AddOnTotal = 0;
                
                if(isset($AddOnTotalArray[$ProdID])){
                    foreach($AddOnTotalArray[$ProdID] as $Am){
                        $AddOnTotal += $Am;
                    }
                }
                
                foreach($Val as $Pro){
                    $Total += $Pro["amount"];
                    $Name = $Pro["name"];
                }
                $NewArr[] = array("Name" => $Name, "Amount" => $Total, "AdOns" => $AddOnTotal);
            }
            $NewCatArray[$CatName] = $NewArr;
        }
        
        $order = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("created_at", "<=", date("Y-m-d 23:59:59"))->where("restaurant_id", $request->ID)->first();
        $RestName = $order->restaurant->user->name;
        $CityName = $order->City->name;
        
        $filename = "SalesReportPDF/".$request->ID."-".date("Y-m-d").".pdf";
        
        $pdf = PDF::loadView('OrderSummary', compact('NewCatArray', "RestName", "CityName", "OnlineSales", 'FirstOrderDate', 'LastOrderDate', 'FirstOrderNo', "LastOrderNo", "TotalOrders", "PickupOrders", "DeliveryOrder", "CashOrders", "CardOrder", "OnlineOrder", "PickupOrderSales", "DeliveryOrderSales", "CardSales", "CashSales"));
        \Storage::put($filename, $pdf->output());
        
        return parent::successjson(URL('/')."/storage/app/".$filename, 200);
    }
    
    public function GetOrderDriverLocation(Request $Request){
        $Order = Order::find($Request->id);
        $MyDriver = MyDriver::find($Order->accepted_driver_id);
        $Lat = (double) $Order->driver_current_latitude;
        $Lon = (double) $Order->driver_current_longitude;
        $PhoneNo = $MyDriver->mobile;
        return json_encode(["Lat" => $Lat, "Lon" => $Lon, "Phone" => $PhoneNo.""]);
    }
}

