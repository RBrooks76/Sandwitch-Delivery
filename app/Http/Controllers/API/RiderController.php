<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Http\Controllers\Dashboard\Common;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

use App\User;
use App\Restaurant;
use App\RestaurantReview;
use App\Products;
use App\ProductsCategory;
use App\UserRide;
use App\UserRestaurant;

class RiderController extends Controller
{
    public function RegisterRider(Request $request){
        $x = [
            'name' => 'required|min:1|max:191',
            'license' => 'required|min:1|numeric',
            'city_id' => 'required|min:1|max:191',
            'country' => 'required|string',
            'phone' => 'required|min:1|numeric',
            'email' => 'required|string|email|max:255',
        ];
        
        $validation = Validator::make($request->all(), $x);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {

            $rider = new UserRide();
            $rider->name = $request->name;
            $rider->email = $request->email;
            $rider->phone = $request->phone;
            $rider->country = $request->country;
            $rider->city_id = $request->city_id;
            $rider->license = $request->license;
            $rider->save();

            $to = $request->email;
            $subject = "Sandwich Map";
            $message = 'Your Application has been successfully submitted
                        Expect a call form us shortly

                        Thank you for applying at SandwichMap delivery Team';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                        "CC: somebodyelse@example.com";

            $message1 = 'Your Application has been successfully submitted
                        Expect a call form us shortly

                        www.sandwichmap.com

                        Thank you for applying at SandwichMap delivery Team

                        Sandwich Map LLC
                        Restaurants Partners Support Team
                        UAE';
            Common::SendTextSMS($request->phone, $message);
            Common::SendEmail($to,$subject,$message1,$headers);

            return response()->json(['success'=>'The account has been opened. You will be contacted']);

        }
    }
}
