<?php
namespace App\Http\Controllers\API;

use App\MyDriver;
use App\Order;
use App\FCM;
use App\User;
use App\Complaint;
use App\DriverComment;
use App\RestaurantPayments;
use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller{
    
    function codexworldGetDistanceOpt($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
        $rad = M_PI / 180;
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad) * sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad)* cos($latitudeTo * $rad) * cos($theta * $rad);
        return acos($dist) / $rad * 60 *  1.853;
    }
    
    public function AutoLogout(Request $request){
        $GetDriver = MyDriver::find($request->id);
        if($GetDriver->device_id == $request->device_id){
            return json_encode(array("status" => 1));
        }
        else{
            return json_encode(array("status" => 0));
        }
    }
    
    public function SaveComplaint(Request $request){
        $Comp = new Complaint();
        $Comp->driver_id = $request->Driver;
        $Comp->order_id = $request->Order;
        $Comp->complaint_for = $request->For;
        $Comp->save();
        return parent::successjson("Complaint Saved", 200);
    }
    
    public function Login(Request $request){
        $CheckDriver = MyDriver::where("username", $request->email)->where("password", $request->password)->count();
        
        if($CheckDriver > 0){
            $CheckDriver = MyDriver::where("username", $request->email)->where("password", $request->password)->first();
            
            if($CheckDriver->status == 0){
                return parent::errorjson('Your id is blocked by Admin', 400);
            }
            
            $CheckDriver->device_id = $request->device_id;
            $CheckDriver->save();
            
            $Data = array("id" => $CheckDriver->id, "Name" => $CheckDriver->name);
            return parent::successjson($Data, 200);
        } else {
            return parent::errorjson('This data does not match our data', 400);
        }
    }
    
    public function AllOrders(Request $request){
        $AllData = $request->all();
        $Offset = $AllData["page"] * 10;
        $DriverID = $request->driver;
        $GetDriver = MyDriver::find($DriverID);
        
        $GetTotalToday = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("driver_status", ">=", 1)->where("created_at", "<=", date("Y-m-d 23:59:59"))->sum("total");
        $GetOrders = Order::with("Items", "OrderLog", "Items.Products", "Items.OrderProductsFeature", "Restaurant", "Restaurant.User")->where("city_id", $GetDriver->city)->where("is_pickup", 0)->whereRaw("id NOT IN (SELECT id FROM `order` WHERE FIND_IN_SET(".$DriverID.", IgnoreOrder))")->orderBy('id', 'desc')->take(10)->offset($Offset)->get();
        
        
        foreach($GetOrders as $ord){
            $Distance = 0;
            
            if($ord->lat != null && $ord->lat > 0 && $ord->lat != ""){
                $Distance = $this->codexworldGetDistanceOpt($ord->lat, $ord->log, $ord->restaurant->user->latitude, $ord->restaurant->user->longtitude);
                $Distance = round($Distance, 0);
            }
            
            $ord->distance = (double)$Distance;
            $ord->phone_active = $ord->phone_active."";
            $ord->restaurnt_phone = $ord->restaurant->user->phone."";
            $ord->status = $ord->status."";
            $ord->TotalAmount = $GetTotalToday."";
            $ord->Commission = "0";
            $ord->payment_type = $ord->payment_type."";
            $ord->house = $ord->house_number."";
            $ord->city_id = $ord->city_id."";
            $ord->restaurant_id = $ord->restaurant_id."";
            $ord->user_id = $ord->user_id."";
            $ord->code = $ord->code."";
            $ord->pickup_sent = $ord->pickup_sent."";
            $ord->b_view = $ord->b_view."";
            $ord->lat = $ord->lat."";
            $ord->log = $ord->log."";
            $ord->is_pickup = $ord->is_pickup."";
            $ord->driver_status = $ord->driver_status;
            $ord->send_driver = $ord->send_driver."";
            $ord->panelty_level = $ord->panelty_level."";
            $ord->UpdateMyOrder = $ord->UpdateMyOrder."";
            $ord->delivery_time = $ord->delivery_time."";
            
            $ord->restaurant_name = $ord->restaurant->user->name."";
            $ord->avatar = $ord->restaurant->user->avatar."";
            
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
            $ord->maplink = $ord->restaurant->user->map_link."";
            
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
    
    public function MyOrders(Request $request){
        $AllData = $request->all();
        $Offset = $AllData["page"] * 10;
        $DriverID = $request->driver;
        
        
        $GetDeliveryCharges = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("accepted_driver_id", $DriverID)->where("driver_status", ">=", 1)->where("created_at", "<=", date("Y-m-d 23:59:59"))->sum("delivery_fee");
        $GetOrders = Order::with("Items", "OrderLog", "Items.Products", "Items.OrderProductsFeature", "Restaurant", "Restaurant.User")->where("accepted_driver_id", $DriverID)->orderBy('id', 'desc')->take(10)->offset($Offset)->get();
        
        
        $TotalOrd = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("accepted_driver_id", $DriverID)->where("driver_status", ">=", 1)->where("created_at", "<=", date("Y-m-d 23:59:59"))->get();
        $TotalAmountPaid = 0;
        foreach($TotalOrd as $ord){
            if($ord->driver_status >= 1){
                $TotalAmountPaid += $ord->total - $ord->original_delivery_fee;
            }
        }
        
        foreach($GetOrders as $ord){
            $Distance = 0;
            
            if($ord->lat != null && $ord->lat > 0 && $ord->lat != ""){
                $Distance = $this->codexworldGetDistanceOpt($ord->lat, $ord->log, $ord->restaurant->user->latitude, $ord->restaurant->user->longtitude);
                $Distance = round($Distance, 0);
            }
            
            $ord->distance = (double)$Distance;
            $ord->phone_active = $ord->phone_active."";
            $ord->restaurnt_phone = $ord->restaurant->user->phone."";
            $ord->status = $ord->status."";
            $ord->TotalAmount = $TotalAmountPaid."";
            $ord->Commission = $GetDeliveryCharges."";
            $ord->payment_type = $ord->payment_type."";
            $ord->house = $ord->house_number."";
            $ord->city_id = $ord->city_id."";
            $ord->restaurant_id = $ord->restaurant_id."";
            $ord->user_id = $ord->user_id."";
            $ord->code = $ord->code."";
            $ord->pickup_sent = $ord->pickup_sent."";
            $ord->b_view = $ord->b_view."";
            $ord->lat = $ord->lat."";
            $ord->log = $ord->log."";
            $ord->panelty_level = $ord->panelty_level."";
            $ord->is_pickup = $ord->is_pickup."";
            $ord->driver_status = $ord->driver_status;
            $ord->send_driver = $ord->send_driver."";
            $ord->UpdateMyOrder = $ord->UpdateMyOrder."";
            $ord->delivery_time = $ord->delivery_time."";
            
            $ord->restaurant_name = $ord->restaurant->user->name."";
            $ord->avatar = $ord->restaurant->user->avatar."";
            
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
            $ord->maplink = $ord->restaurant->user->map_link."";
            
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
    
    public function PaymentSummary(Request $request){
        $DriverID = $request->id;
        $TotalOrd = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("accepted_driver_id", $DriverID)->where("driver_status", ">=", 1)->where("created_at", "<=", date("Y-m-d 23:59:59"))->latest()->get();
        
        $Payments = array();
        foreach($TotalOrd as $ord){
            $Payments[] = array("ID" => $ord->id, "Name" => $ord->restaurant->user->name, "Logo" => $ord->restaurant->user->avatar, "Amount" => ($ord->total - $ord->original_delivery_fee)."", "Date" => date("Y-m-d", strtotime($ord->created_at)), "DriverStatus" => $ord->driver_make_pay, "RestaurantStatus" => $ord->restaurant_received_payment);
        }
        
        echo json_encode($Payments);
    }
    
    public function RestaurantPaymentSummary(Request $request){
        $DriverID = $request->id;
        $TotalOrd = Order::where("created_at", ">=", date("Y-m-d 00:00:00"))->where("restaurant_id", $DriverID)->where("driver_status", ">=", 1)->where("created_at", "<=", date("Y-m-d 23:59:59"))->latest()->get();
        
        $Payments = array();
        foreach($TotalOrd as $ord){
            $CheckRating = DriverComment::where("order_id", $ord->id)->where("AppType", "SandwichMenu")->count();
            $Driver = MyDriver::find($ord->accepted_driver_id);
            
            $Payments[] = array("ID" => $ord->id, "Name" => isset($Driver->name) ? $Driver->name : "", "DriverID" => $ord->accepted_driver_id, "Logo" => $ord->restaurant->user->avatar, "Amount" => ($ord->total - $ord->original_delivery_fee)."", "Date" => date("Y-m-d", strtotime($ord->created_at)), "DriverStatus" => $ord->restaurant_received_payment, "RateByMe" => $CheckRating);
        }
        
        echo json_encode($Payments);
    }
    
    public function ShowDerliveryCharges(Request $request){
        $DriverID = $request->id;
        $TotalOrd = Order::where("accepted_driver_id", $DriverID)->where("driver_status", ">=", 1)->latest()->get();
        
        $Payments = array();
        foreach($TotalOrd as $ord){
            $Key = date("Y-m-d", strtotime($ord->created_at));
            
            if(!isset($Payments[$Key])){
                $Payments[$Key] = 0;
            }
            
            $Payments[$Key] += $ord->delivery_fee;
        }
        
        $NewArray = array();
        foreach($Payments as $K => $V){
            $NewArray[] = array("Date" => $K, "Amount" => $V."");
        }
        
        echo json_encode($NewArray);
    }
    
    public function ChangeStatus(Request $request){
        $Order = Order::find($request->id);
        $ID = $request->id;
        $DriverData = MyDriver::find($request->driver);
        
        $Order->driver_status = $request->status;
        
        if($request->status == 1){
            $CheckOtherAccept = Order::where("accepted_driver_id", $request->driver)->where("driver_status", "!=", 9)->count();
            
            if($CheckOtherAccept > 0){
                return parent::errorjson('Please Finish old Order', 400);
            }
            
            $Order->accepted_driver_id = $request->driver;
            $Order->status = 5;
            $Order->UpdateMyOrder = 0;
            
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Order #".$ID." Accepted By Sandwich map", 1, "Orders", $NewExtData);
            }
            
            
            /********** Notification For Driver ***********/
            $GetFCM = FCM::where("device_id", $request->driver)->where("app_type", "SandwichMenuDriver")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "You Have Accepted The Order Note Delivery Timing is 50 Minutes", 1, "Orders", $NewExtData);
            }
        }
        
        
        
        
        if($request->status == 2){
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Driver is heading to the resturrnt", 1, "Orders", $NewExtData);
            }
            
            
            /********** Notification For Resturant ***********/
            $GetFCM = FCM::where("device_id", $Order->restaurant->id)->where("app_type", "SandwichMenu")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Mr. ".$DriverData->name." is heading to you Please Prepere the order", 1, "Orders", $NewExtData);
            }
        }
        
        
        
        
        if($request->status == 4){
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Order is Cooking", 1, "Orders", $NewExtData);
            }
        }
        
        
        if($request->status == 5){
            $Date = date("Y-m-d H:i:s", strtotime("+40 minute"));
            $Order->delivery_time = $Date;
            
            
            
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Order is taking By the Driver", 1, "Orders", $NewExtData);
            }
            
            
            /********** Notification For Resturant ***********/
            $GetFCM = FCM::where("device_id", $Order->restaurant->id)->where("app_type", "SandwichMenu")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Order is taking By the Driver", 1, "Orders", $NewExtData);
            }
            
            /********** Notification For Driver ***********/
            $GetFCM = FCM::where("device_id", $request->driver)->where("app_type", "SandwichMenuDriver")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "I have taken order From Restaurant Now going to Customer", 1, "Orders", $NewExtData);
            }
        }
        
        
        
        
        if($request->status == 6){
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Driver is headng to you", 1, "Orders", $NewExtData);
            }
            
            
            /********** Notification For Resturant ***********/
            $GetFCM = FCM::where("device_id", $Order->restaurant->id)->where("app_type", "SandwichMenu")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Driver is headng to Customer", 1, "Orders", $NewExtData);
            }
        }
        
        
        
        if($request->status == 7){
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Driver is arrived Target Location", 1, "Orders", $NewExtData);
            }
            
            
            /********** Notification For Resturant ***********/
            $GetFCM = FCM::where("device_id", $Order->restaurant->id)->where("app_type", "SandwichMenu")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "I Reached Target Location", 1, "Orders", $NewExtData);
            }
            
            /********** Notification For Driver ***********/
            $GetFCM = FCM::where("device_id", $request->driver)->where("app_type", "SandwichMenuDriver")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Driver is arrived Target Location", 1, "Orders", $NewExtData);
            }
        }
        
        
        
        
        if($request->status == 9){
            /********** Notification For Customer ***********/
            $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Order Completd ( Please tell  Your Order Experince in the review ) thank you", 1, "Orders", $NewExtData);
            }
            
            
            /********** Notification For Resturant ***********/
            $GetFCM = FCM::where("device_id", $Order->restaurant->id)->where("app_type", "SandwichMenu")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "I have completed the order on time", 1, "Orders", $NewExtData);
            }
            
            /********** Notification For Driver ***********/
            $GetFCM = FCM::where("device_id", $request->driver)->where("app_type", "SandwichMenuDriver")->get();
            foreach($GetFCM as $GAT){
                $NewExtData["OrderID"] = $request->id;
                $NewExtData["UnreadBadge"] = 0;
                
                $this->SendNotificationSendPushNotification($GAT->token, "Order #".$ID, "Order Completd ( Please tell  Your Order Experince in the review ) thank you", 1, "Orders", $NewExtData);
            }
        }
        
        
        
        if($request->status == 8){
            $GetIDS = explode(",", $Order->IgnoreOrder);
            $GetIDS[] = $request->driver;
            $Order->IgnoreOrder = implode(",", $GetIDS);
        }
        
        $Order->save();
        return parent::successjson("Order Accepted Successfully", 200);
    }
    
    public function UpdateDriverLocation(Request $request){
        $Order = Order::find($request->id);
        $Order->driver_current_latitude = $request->lat;
        $Order->driver_current_longitude = $request->lon;
        $Order->save();
        
        logger($request->id."===".$request->lat."++++".$request->lon);
        return parent::successjson("Order Rejected Successfully", 200);
    }
    
    public function ChageOrderTime(){
        $GetAllOrder = Order::whereIn("driver_status", [5, 6])->get();
        
        foreach($GetAllOrder as $GAOD){
            if($GAOD->delivery_time < date("Y-m-d H:i:s", strtotime("+1 minute"))){
                if($GAOD->panelty_level == 0){
                    $Order = Order::find($GAOD->id);
                    $Date = date("Y-m-d H:i:s", strtotime("+20 minute"));
                    $Order->delivery_time = $Date;
                    $Order->delivery_fee = 0;
                    $Order->panelty_level = 1;
                    $Order->save();
                    
                    
                    /********** Notification For Customer ***********/
                    $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
                    foreach($GetFCM as $GAT){
                        $NewExtData["OrderID"] = $GAOD->id;
                        $NewExtData["UnreadBadge"] = 0;
                        
                        $this->SendNotificationSendPushNotification($GAT->token, "Order #".$GAOD->id, "Driver didn't delivery your order in time, Now your delivery fee is 0 and your order will be delivered to you in 20 Minute", 1, "Orders", $NewExtData);
                    }
                }else if($GAOD->panelty_level == 1){
                    $Order = Order::find($GAOD->id);
                    $Date = date("Y-m-d H:i:s", strtotime("+15 minute"));
                    $Order->delivery_time = $Date;
                    $Order->delivery_fee = 0;
                    $Order->panelty_level = 2;
                    $Order->save();
                    
                    
                    /********** Notification For Customer ***********/
                    $GetFCM = FCM::where("device_id", $Order->device_id)->where("app_type", "SandwichMap")->get();
                    foreach($GetFCM as $GAT){
                        $NewExtData["OrderID"] = $GAOD->id;
                        $NewExtData["UnreadBadge"] = 0;
                        
                        $this->SendNotificationSendPushNotification($GAT->token, "Order #".$GAOD->id, "Driver didn't delivery your order in time, Now your order is free and your order will be delivered to you in 15 Minute", 1, "Orders", $NewExtData);
                    }
                }
            }
        }
    }
    
    public function SavePaymentHandover(Request $request){
        $PaymentObj = Order::find($request->restaurant);
        $PaymentObj->driver_make_pay = 1;
        $PaymentObj->save();
        
        /********** Notification For Resturant ***********/
        $GetFCM = FCM::where("device_id", $PaymentObj->restaurant->id)->where("app_type", "SandwichMenu")->get();
        foreach($GetFCM as $GAT){
            $NewExtData["OrderID"] = $PaymentObj->id;
            $NewExtData["UnreadBadge"] = 0;
            
            $this->SendNotificationSendPushNotification($GAT->token, "Order #".$PaymentObj->id, "Driver wants to pay the bill please approve receiving", 1, "Orders", $NewExtData);
        }
        
        return parent::successjson("Payment Send", 200);
    }
    
    public function CheckForNewOrder(Request $request){
        $CheckOrder = Order::where("id", ">", $request->id)->count();
        
        if($CheckOrder == 0){
            return parent::successjson("No", 200);
        }else{
            return parent::successjson("Yes", 200);
        }
    }
    
    public function RestaurantPaymentCollect(Request $request){
        $PaymentObj = Order::find($request->id);
        $PaymentObj->restaurant_received_payment = 1;
        $PaymentObj->save();
        return parent::successjson("Done", 200);
    }
    
    public function SaveDriverRestaurantComment(Request $request){
        $Obj = new DriverComment();
        $Obj->comment = $request->comment;
        $Obj->star = $request->star;
        $Obj->driver_id = $request->DriverID;
        $Obj->order_id = $request->orderID;
        $Obj->AppType = $request->AppType;
        $Obj->client_name = $request->restaurant_id;
        $Obj->save();
        
        return parent::successjson("Commented Created", 200);
    }
    
    public function customer_driver_comment(Request $request){
        $Order = Order::find($request->restaurant_id);
        $Obj = new DriverComment();
        $Obj->comment = $request->comment;
        $Obj->star = $request->star;
        $Obj->driver_id = $Order->accepted_driver_id;
        $Obj->order_id = $request->restaurant_id;
        $Obj->AppType = "SandwichMap";
        $Obj->client_name = $Order->client_name;
        $Obj->client_phone = $Order->phone;
        $Obj->save();
        
        return parent::successjson("Commented Created", 200);
    }
}
