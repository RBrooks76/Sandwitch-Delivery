<?php

namespace App\Http\Controllers\Dashboard;

use App\Order;
use App\Products;
use App\Restaurant;
use App\User;
use App\Category;
use App\City;
use App\Client;
use App\FCM;
use App\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $Restaurants = percentage(User::where("role", 4)->count(), 10000);
        $rest = percentage(Restaurant::count(), 10000);
        $pro = percentage(Products::count(), 10000);
        $users = percentage(Order::where('client_name', '!=', "")->count(), 10000);
        $orrder = percentage(Order::where('client_name', '!=', "")->sum('total'), 100000);

        $orrder_this_month = percentage(Order::where('client_name', '!=', '')->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count(), 350);
        $orrder_this_month1 = percentage(Order::where('client_name', '!=', '')->where("status", 1)->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count(), 350);
        $orrder_this_month5 = percentage(Order::where('client_name', '!=', '')->where("status", 5)->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count(), 350);
        $orrder_this_month3 = percentage(Order::where('client_name', '!=', '')->where("status", 3)->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count(), 350);
        $ordersCount = (object)([
            'all' => Order::where('client_name', '!=', '')->count(),
            'rejected' => Order::where('client_name', '!=', '')->where('status', 3)->count(),
            'accepted' => Order::where('client_name', '!=', '')->where('status', 5)->count(),
            'pending' => Order::where('client_name', '!=', '')->where('status', 1)->count()
        ]);
        $city_ids = City::where("active", 1)->pluck('id');
        $restaurant_id = Restaurant::whereIn('restaurant_city', $city_ids)->get();

        $category_id = Category::where("active", 1)->get();
        $city = City::where("active", 1)->get();

        $chartOrderLabels = [];
        $chartOrderValues = [];
        for ($i = 89; $i >= 0; $i--) {
            $lower = date('Y-m-d', time() - $i * 3600 * 24);
            $upper = date('Y-m-d', time() - ($i - 1) * 3600 * 24);
            if ($i == 89 || date('d', strtotime($lower)) == '1') {
                array_push($chartOrderLabels, date('M', strtotime($lower)));
            } else {
                array_push($chartOrderLabels, date('d', strtotime($lower)));
            }
            $count = Order::where('client_name', '!=', '')->where('created_at', '>=', $lower)->where('created_at', '<', $upper)->count();
            array_push($chartOrderValues, $count);
        }
        $chartOrderLabels = json_encode($chartOrderLabels);
        $chartOrderValues = json_encode($chartOrderValues);

        return view('dashboard.index', compact('restaurant_id', 'orrder', 'rest', 'pro', 'Restaurants', 'users', 'orrder_this_month3', 'orrder_this_month1', 'orrder_this_month5', 'orrder_this_month', 'category_id', 'city', 'chartOrderLabels', 'chartOrderValues', 'ordersCount'));
    }

    public function user_das()
    {
        return response()->json([User::where("role", 2)->orwhere("role", 3)->orwhere("role", 5)->with("UserAddress")->get()]);
    }

    public function send_email()
    {
        return view('dashboard.send_email');
    }
    
    public function SendPushNotification(){
        return view('dashboard.push_notification');
    }
    
    public function SendPushNotificationUser(Request $request){
        
        if($request->AppType == "SandwichMenu"){
            $getAllToken = FCM::where("app_type", $request->AppType)->get()->unique("device_id");
            
            foreach($getAllToken as $GAT){
                $DeviceID = $GAT->device_id;
                $GetRestDetail = Restaurant::find($GAT->device_id);
                $DeviceID = @$GetRestDetail->user_id;
                if($DeviceID != ""){
                    $NewNoti = new Notification();
                    $NewNoti->type = "Other";
                    $NewNoti->users = $DeviceID;
                    $NewNoti->title = $request->title;
                    $NewNoti->description = $request->notification;
                    $NewNoti->notification_app = $request->AppType;
                    $NewNoti->save();
                }
            }
            
            
            $getAllToken = FCM::where("app_type", $request->AppType)->get()->unique("token");
            foreach($getAllToken as $GAT){
                $DeviceID = $GAT->device_id;
                $GetRestDetail = Restaurant::find($GAT->device_id);
                $DeviceID = @$GetRestDetail->user_id;
                
                if($DeviceID != ""){
                    $this->SendNotificationSendPushNotification($GAT->token, $request->title, $request->notification);
                }
            }
        }else{
            $getAllToken = FCM::where("app_type", $request->AppType)->groupBy("device_id")->get();
            foreach($getAllToken as $GAT){
                $DeviceID = $GAT->device_id;
                
                if($DeviceID != ""){
                    $NewNoti = new Notification();
                    $NewNoti->type = "Other";
                    $NewNoti->users = $DeviceID;
                    $NewNoti->notification_app = $request->AppType;
                    $NewNoti->title = $request->title;
                    $NewNoti->description = $request->notification;
                    $NewNoti->save();
                    $this->SendNotificationSendPushNotification($GAT->token, $request->title, $request->notification);
                }
            }
        }
        
        return response()->json(['success' => 'تم ارسال البريد بنجاح', 'dashboard' => '1']);
    }

    public function send_email_send(Request $request){
        $validation = Validator::make($request->all(), $this->rules());
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            parent::send_Email_template($request->name_temp, $request->name, $request->email, $request->summary);
            return response()->json(['success' => 'تم ارسال البريد بنجاح', 'dashboard' => '1']);
        }
    }


    private function rules()
    {
        $x = [
            'name' => 'required|string|min:1',
            'name_temp' => 'required|string|min:1',
            'email' => 'required|email|string|min:1',
            'summary' => 'required|string|min:1',
        ];
        return $x;
    }
}
