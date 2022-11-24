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

use App\AdRequest;

class AdvertController extends Controller
{
    public function RegisterAds(Request $request){
        $x = [
            'RestID' => 'required',
            'AdType' => 'required',
            'Name' => 'required',
            'RestName' => 'required',
            'City' => 'required',
            'Mobile' => 'required',
        ];
        
        $validation = Validator::make($request->all(), $x);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {

            $rider = new AdRequest();
            $rider->rest_id = $request->RestID;
            $rider->ad_type = $request->AdType;
            $rider->name = $request->Name;
            $rider->rest_name = $request->RestName;
            $rider->City = $request->City;
            $rider->Mobile = $request->Mobile;
            $rider->save();

            return parent::successjson("Request sent successfully, our team will contact you soon", 200);
        }
    }
}
