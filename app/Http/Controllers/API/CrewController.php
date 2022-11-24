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

use App\Crew;

class CrewController extends Controller
{
    public function CrewList(Request $request){
        $Car = Crew::orderBy("id", "DESC")->get();
        
        return parent::successjson($Car, 200);
    }
}
