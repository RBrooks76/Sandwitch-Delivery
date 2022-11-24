<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Parser;

class AuthController extends Controller
{
    public function error_login()
    {
        return parent::errorjson('url is empty', 400);
    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rulesregister());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        $code = parent::RandomOrderId(10);
        $request['password'] = bcrypt($request->password);
        $user = new User();
        $user->name = $request['name'];
        $user->phone = $request['phone'];
        $user->email = $request['email'];
        $user->code = $code;
        $user->active = 1;
        $user->password = $request['password'];
        $user->role = 2;
        $user->save();

        Auth::login($user, true);
        $user = Auth::user();
        $r = ['user' => $user];
        return parent::successjson($r, 200);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), $this->rules());
        if ($validation->fails()) {
            return parent::errorjson($validation->errors(), 400);
        }
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ],true)) {
            
            $GetRestData = Restaurant::where("user_id", Auth::user()->id)->first();
            
            $restaurant = Restaurant::with('category')->with('city')->with('user')->find($GetRestData->id);
            
            $restaurant->user_id = $restaurant->user_id."";
            $restaurant->restaurant_city =$restaurant->restaurant_city."";
            $restaurant->priority = $restaurant->priority."";
            $restaurant->status = $restaurant->status."";
            $restaurant->all_priority = $restaurant->all_priority."";
            $restaurant->restaurant_category = $restaurant->restaurant_category."";
            $restaurant->user = $restaurant->user;
            $restaurant->city->priority = $restaurant->city->priority."";
            return parent::successjson($restaurant, 200);
        } else {
            return parent::errorjson('This data does not match our data', 400);
        }
    }

    public function log_out(Request $request)
    {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens->find($id);
        $token->revoke();
        return parent::successjson('You Are Log Out', 200);
    }

    public function active(Request $request)
    {
        $user = Auth::user();
        if ($request->code == $user->code) {
            $user->code = null;
            $user->active = 1;
            $user->save();
            return parent::successjson('تم تفعيل الحساب', 200);
        }
        return parent::errorjson('الكود غير صحيح', 200);
    }

    public function current(Request $request)
    {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens->find($id);
        return parent::successjson($token, 200);
    }

    private function rules()
    {
        return [
            'password' => 'required|string',
            'email' => 'required|string|email|max:255'
        ];
    }

    private function rulesregister($edit = null)
    {
        if ($edit) {
            return [
                'name' => 'required|string|max:255',
                'phone' => 'required|numeric',
                'password' => 'required|string|min:6|confirmed',
                'email' => 'required|string|email|max:255|unique:users,email,' . $edit,
            ];
        } else {
            return [
                'name' => 'required|string|max:255',
                'phone' => 'required|numeric',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ];
        }
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $img = $request->img;
        $pass = $request->password;
        $validator = Validator::make($request->all(), $this->rules_prodile($img, $pass));
        if ($validator->fails()) {
            return parent::errorjson($validator->errors(), 400);
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        if ($request->hasFile('avatar')) {
            $user->avatar = parent::upladImage($request->file('avatar'), 'avatar');
        }
        $user->update();
        return parent::successjson('Done Updated Successfully Info', 200);
    }


    public function rules_prodile($img = null, $password = null)
    {
        $x = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . parent::CurrentID(),
        ];
        if ($img != null) {
            $x['img'] = 'required|mimes:png,jpg,jpeg';
        }
        if ($password != null) {
            $x['password'] = 'required|string|min:6|confirmed';
        }
        return $x;
    }


}
