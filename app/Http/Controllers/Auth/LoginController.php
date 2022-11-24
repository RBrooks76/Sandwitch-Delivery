<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Subscriptions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        );
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            return true;
        } else {
            return false;
        }
    }

    protected function authenticated(Request $request, $user)
    {
        // if ($user->role == 1 || ($user->role == 5 && $user->active == 1)) {
            return redirect()->route('dashboard_admin.index');
        // } else { 
        //     Auth::logout();
        //     return redirect()->route('login')->with("error", "Account not active");
        // }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

}
