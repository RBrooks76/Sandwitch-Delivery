<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\InvoicesExport;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd('asdfasd');
        $user = user();
        // return redirect()->route('dashboard_admin.index');
        if($user){
            if (($user->role == 1 && $user->active == 1) || ($user->role == 5 && $user->active == 1)) {
                // return redirect()->route('dashboard_admin.index');
            }
            else {
                Auth::logout();
                return redirect()->route('login')->with("error", "Account not active");
            }
        }
        else{
            Auth::logout();
            dd('asdfasd');
            return redirect()->route('login')->with("error", "Account not active");
        }
    }

}
