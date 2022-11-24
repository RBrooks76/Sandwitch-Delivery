<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\FCM;

class NotificationController extends Controller{
    
    public function logout(Request $Request){
        $AllData = $Request->all();
        FCM::where("token", $AllData['token'])->delete();
        return parent::successjson("Token Added", 200);
    }
    
    public function SaveFCM(Request $Request){
        $AllData = $Request->all();
        $CheckID = FCM::where("device_id", $AllData['device_id'])->where("token", $AllData['token'])->count();
        
        $AppType = "SandwichMap";
        
        if(isset($AllData['AppType'])){
            $AppType = $AllData['AppType'];
        }
        
        if($CheckID == 0){
            $FM = new FCM();
            $FM->device_id = $AllData['device_id'];
            $FM->token = $AllData['token'];
            $FM->app_type = $AppType;
            $FM->save();
        }else{
            $CheckID = FCM::where("device_id", $AllData['device_id'])->first();
            $CheckID->token = $AllData['token'];
            $CheckID->app_type = $AppType;
            $CheckID->save();
        }
        
        return parent::successjson("Token Added", 200);
    }
}
