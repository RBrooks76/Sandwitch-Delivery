<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HomepageController extends Controller
{
    public function index(){
        $user = user();
        if($user){
            if (($user->role == 1 && $user->active == 1) || ($user->role == 5 && $user->active == 1)) {
                return redirect()->route('dashboard_admin.index');
            }
            else {
                Auth::logout();
                return redirect()->route('login')->with("error", "Account not active");
            }
        }
        else{
            return redirect()->route('login');
        }
    }

}
