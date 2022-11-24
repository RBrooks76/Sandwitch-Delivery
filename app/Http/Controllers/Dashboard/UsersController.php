<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Products;
use App\Http\Controllers\Dashboard\Common;
class UsersController extends Controller
{
    public function index()
    {
        return view('dashboard/users.index');
    }

    public function add_edit()
    {
        return view('dashboard/users.add_edit');
    }

    function get_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'avatar',
            4 => 'avatar',
            5 => 'confirm_email',
            6 => 'id',
        );

        $type = $request->type;
        $totalData = User::where(function ($q) use ($type) {
            if ($type) {
                $q->where("role", $type);
            } else {
                $q->where("role", '5');
            }
        })->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');

        $posts = User::
        where(function ($q) use ($type) {
            if ($type) {
                $q->where("role", $type);
            } else {
                $q->where("role", '5');
            }
        })->
        where(function ($q) use ($search) {
            if ($search) {
                $q->Where('name', 'LIKE', "%{$search}%");
                $q->orWhere('email', 'LIKE', "%{$search}%");
            }
        })
            ->offset($start)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->orderBy($order, $dir)
            ->get();

        if ($search != null) {
            $totalFiltered = User::where(function ($q) use ($type) {
                if ($type) {
                    $q->where("role", $type);
                } else {
                    $q->where("role", '5');
                }
            })->
            where(function ($q) use ($search) {
                if ($search) {
                    $q->Where('name', 'LIKE', "%{$search}%");
                    $q->orWhere('email', 'LIKE', "%{$search}%");
                }
            })->count();
        }


        $data = array();
        if (!empty($posts)) {
            $active_count = 1;
            foreach ($posts as $post) {
                $ava = url(parent::PublicPa() . $post->avatar);
                if ($post->type_login != null) {
                    $ava = $post->avatar;
                }

                $edit = route('dashboard_users.add_edit', ['id' => $post->id, 'type' => $request->type]);

                $role = $post->NameRole();

                $check1 = '';
                $active_or_no1 = 'Disable';
                if ($post->active == 1) {
                    $check1 = 'checked';
                    $active_or_no1 = 'Enable';
                }

                $add1= '<div class="material-switch pull-left">
                            <input data-id="'. $post->id .'" id="active_'.$active_count.'" class="btn_confirm_email_current" type="checkbox" '.$check1.'/>
                            <label for="active_'.$active_count.'" class="label-success"></label>
                        </div>';


                $add = "<label>
                            <input type=\"checkbox\" data-id='$post->id' class=\"btn_select_btn_deleted\">
                        </label>";

                $nestedData['id'] = $add;
                $nestedData['name'] = $post->name;
                $nestedData['phone'] = $post->phone;
                $nestedData['confirm_email'] = $add1;
                $nestedData['email'] = $post->email;
                $nestedData['role'] = '<div class="badge badge-primary">' . $role . '</div>' . '<br>';
                $nestedData['avatar'] = "<img style='width: 50px;height: 50px;' src='{$ava}' class='img-circle img_data_tables'>";

                $nestedData['options'] = "<a class='btn btn-sm btn-primary' href='{$edit}' title='Edit' ><i class='fa fa-edit'></i> Edit</a>
                                          <a class='btn_delete_current btn btn-sm btn-danger' href='#' data-id='{$post->id}' title='Delete' ><i class='fa fa-trash'></i> Delete</a>";
                $data[] = $nestedData;

                $active_count += 1;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    function get_data_by_id(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $user = User::where('id', '=', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        return response()->json(['success' => $user]);
    }

    function deleted(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $user = User::where('id', '=', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }

        if ($user->id == parent::CurrentID()) {
            return response()->json(['error' => __('language.msg.e')]);
        }
$to = $user['email'];
            $subject = "Sandwich Map";
            $message = 'Your have been terminated & Your Account has been Deleted 
            
Thank you For working with sandwich map Head Office 

www.sandwichmap.net

Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                        "CC: somebodyelse@example.com";

            $message1 = 'Your have been terminated & Your Account has been Deleted 
            
Thank you For working with sandwich map Head Office 

www.sandwichmap.net
Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    '. $user['email'] . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            Common::SendTextSMS($user['phone'], $message);
            Common::SendEmail($to,$subject,$message1,$headers);
        $user->delete();
        return response()->json(['error' => __('language.msg.d')]);
    }

    public function post_data(Request $request)
    {
        
        $edit = $request->id;
        $password = $request->password;
        $user_id = $request->email_id;
        $user_name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        
        $validation = Validator::make($request->all(), $this->rules($edit, $password), $this->languags());
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            if ($edit == null) {
                $user = new User();
                $user->phone = $request->phone;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role ? $request->role : 5;
                if ($request->password != null) {
                    $user->password = bcrypt($request->password);
                }
                $user->show_password = $request->password;
                $user->avatar = parent::upladImage($request->avatar, 'avatar');
                $user->save();
                if (!$user) {
                    return response()->json(['error' => __('language.msg.e')]);
                }
            
                $to = $email;
                $subject = "";
                $message1 = "";
                $message2 = "";
                $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                            "CC: somebodyelse@example.com";
                if($request->role && $request->role == 1) {
                    $subject = "Sandwich Map high level management";
                    $message1 = 'Welcome to Sandwich Map high level Management 
Your access information is:
                                
Agent Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'
Warning: Your access information is highly secret please never share it with others
www.sandwichmap.com
For more information please contact

Ibrahim Osman +971501212770
Fatima Aljaberi +971508698988
Alaa Osman +971561146518

Please do not reply to this email. 

We would also be happy to receive your feedback - suggestions and complaints on 

Management Email :
    sandwichmap@yahoo.com
                                
Owner Email :
    '. $email . '
fs.aljabri@yahoo.com
Sincerely,
                                
Sandwich Map LLC
Restaurants Partners Support Team
UAE
Copyright © 2021 sandwich map Operating Company, LLC. .All rights reserved.';
                                
                    $message2 = 'Welcome to Sandwich Map high level Management 
Your access information is:-
Agent Name : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'
                                
www.sandwichmap.com
                                
Warning: Your access information is highly secret please never share it !
www.sandwichmap.net
    
For more information please contact
    
Ibrahim Osman +971501212770
Fatima Aljaberi +971508698988
Alaa Osman +971561146518';

                } else {
                    $subject = "Welcome to Sandwich Map Support team";
                    $message1 = 'Welcome to Sandwich Map Support 
                                
Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'
                                
www.sandwichmap.com
                                
For more information please Reach Out your direct manger
                                
Please do not reply to this email. 
        
We would also be happy to receive your feedback - suggestions and complaints on 
        
Management Email :
    sandwichmap@yahoo.com
                                
Owner Email :
    '. $email . '
fs.aljabri@yahoo.com
Sincerely,
                                
Sandwich Map LLC
Restaurants Partners Support Team
UAE

Copyright © 2021 sandwich map Operating Company, LLC. .All rights reserved.';
                                
                    $message2 = 'Welcome to Sandwich Map Support
                    
Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'
                                
www.sandwichmap.com
                                
www.sandwichmap.net
    
For more information please Reach Out your direct manger
    
Management Email :
    sandwichmap@yahoo.com';
    
                }
                
                Common::SendTextSMS($phone, $message2);
                Common::SendEmail($to, $subject, $message1, $headers);
                return response()->json(['success' => __('language.msg.s'), 'dashboard' => '1', 'redirect' => route('dashboard_users.index', ['id' => null, 'type' => $request->type])]);
            } else {
                
                    $user = User::where('id', '=', $request->id)->first();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->role = $request->role;
                    $user->phone = $request->phone;
                    $user->show_password = $request->password;
                    if ($request->password != null) {
                        $user->password = bcrypt($request->password);
                    }
                    if ($request->avatar) {
                        if ($user->avatar != 'no.png') {
                            if (file_exists(public_path($user->avatar))) {
                                unlink(public_path($user->avatar));
                            }
                        }
                        $user->avatar = parent::upladImage($request->avatar, 'avatar');
                    }
                    $user->update();
                    if (!$user) {
                        return response()->json(['error' => __('language.msg.e')]);
                    }
                
                $to = $email;
                $subject = "";
                $message1 = "";
                $message2 = "";
                $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                            "CC: somebodyelse@example.com";
                if($request->role && $request->role == 1) {
                    $subject = "Sandwich Map high level management";
                    $message1 = 'Welcome to Sandwich Map high level Management 
                                
Your access information is:

Agent Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'
Warning: Your access information is highly secret please never share it with others
www.sandwichmap.com
For more information please contact
        
Ibrahim Osman +971501212770
Fatima Aljaberi +971508698988
Alaa Osman +971561146518
                                
Please do not reply to this email. 
        
We would also be happy to receive your feedback - suggestions and complaints on 
        
Management Email :
    sandwichmap@yahoo.com
                                
Owner Email :
    '. $email . '
fs.aljabri@yahoo.com
Sincerely,
                                
Sandwich Map LLC
Restaurants Partners Support Team
UAE

Copyright © 2021 sandwich map Operating Company, LLC. .All rights reserved.';
                                
                    $message2 = 'Welcome to Sandwich Map high level Management 
                                
Your access information is:-
Agent Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'

www.sandwichmap.com
                                
Warning: Your access information is highly secret please never share it !
www.sandwichmap.net
    
For more information please contact

Ibrahim Osman +971501212770
Fatima Aljaberi +971508698988
Alaa Osman +971561146518';

                } else {
                    $subject = "Welcome to Sandwich Map Support team";
                    $message1 = 'Welcome to Sandwich Map Support 
                                
Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'

www.sandwichmap.com

For more information please Reach Out your direct manger

Please do not reply to this email. 
    
We would also be happy to receive your feedback - suggestions and complaints on 

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    '. $email . '
fs.aljabri@yahoo.com
Sincerely,
                                
Sandwich Map LLC
Restaurants Partners Support Team
UAE

Copyright © 2021 sandwich map Operating Company, LLC. .All rights reserved.';
                                
                    $message2 = 'Welcome to Sandwich Map Support
                    
Name   : '. $user_name .'
Username : '. $email . '
password: ' . $password . '
phone: ' . $phone .'
                                
www.sandwichmap.com
                                
www.sandwichmap.net
    
For more information please Reach Out your direct manger
    
Management Email :
    sandwichmap@yahoo.com';
    
                }
                Common::SendTextSMS($phone, $message2);
                Common::SendEmail($to,$subject,$message1,$headers);
                return response()->json(['success' => __('language.msg.m'), 'dashboard' => '1', 'redirect' => route('dashboard_users.index', ['id' => null, 'type' => $request->type])]);
            }
        }
    }

    private function rules($edit = null, $pass = null)
    {
        $x = [
            'name' => 'required|min:3|regex:/^[ا-يa-zA-Z0-9]/',
            'email' => 'required|string|email|unique:users,email,' . $edit,
            'avatar' => 'required|mimes:png,jpg,jpeg,PNG,JPG,JPEG',
        ];
        if ($edit != null) {
            $x['id'] = 'required|integer|min:1';
            $x['avatar'] = 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG';
            $x['password'] = 'nullable|string|min:6|confirmed';
        } else {
            $x['password'] = 'required|string|min:6|confirmed';
        }

        if ($pass != null) {
            $x['password'] = 'required|string|min:6|confirmed';
        }


        return $x;
    }

    private function languags()
    {
        if (app()->getLocale() == "ar") {
            return [
                'name.required' => 'حقل الاسم مطلوب.',
                'name.regex' => 'حقل الاسم غير صحيح .',
                'name.min' => 'حقل الاسم مطلوب على الاقل 3 حقول .',

                'email.required' => 'حقل الايميل مطلوب.',
                'email.taken' => 'البريد الإلكتروني تم أخذه.',
                'email.email' => 'حقل الايميل غير صحيح .',

                'role.required' => 'حقل الدور مطلوب.',
                'role.integer' => 'حقل الدور غير صحيح .',
                'role.in' => 'حقل الدور غير صحيح .',

                'avatar.required' => 'حقل الصورة مطلوب.',
                'avatar.mimes' => 'حقل الصورة غير صحيح .',

                'password.required' => 'حقل كلمة المرور مطلوب.',
                'password.min' => 'حقل كلمة المرور على الاقل 6 حقول .',

            ];
        } else {
            return [];
        }
    }


    function confirm_email(Request $request)
    {
        $id = $request->id;
        if ($id == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        $user = User::where('id', '=', $id)->first();
        if ($user == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($user->id == parent::CurrentID()) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if ($user->active == 1) {
            $user->active = 0;
            $user->update();
            
            $to = $user['email'];
            $subject = "Sandwich Map";
            $message = 'Your Account has been freezed 
            
To reactivate your account please contact your relationship manager   

www.sandwichmap.net

Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                        "CC: somebodyelse@example.com";

            $message1 = 'Your Account has been Freezed 
            
To reactivate your account please contact your relationship manager.

www.sandwichmap.net
Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    '. $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            Common::SendTextSMS($user['phone'], $message);
            Common::SendEmail($to,$subject,$message1,$headers);
            
            return response()->json(['error' => __('table.a_n_ver')]);
        } else {
            $user->active = 1;
            $user->update();
            
            $to = $user['email'];
            $subject = "Sandwich Map";
            $message = 'Your Account has been successfully activated

www.sandwichmap.net

Thank you for using SandwichMap for more support Please call 0501212770';

            $headers = "From:   noreply@icheck-antibody.jp" . "\r\n" .
                        "CC: somebodyelse@example.com";

            $message1 = 'Your Account has been successfully activated.

www.sandwichmap.com
Thank you for using SandwichMap for more support Please call 0501212770
Please do not reply to this email.
We would also be happy to receive your feedback - suggestions and complaints on

Management Email :
    sandwichmap@yahoo.com

Owner Email :
    '. $to . '
fs.aljabri@yahoo.com
Sincerely,

Sandwich Map LLC
Restaurants Partners Support Team
UAE';
            Common::SendTextSMS($user['phone'], $message);
            Common::SendEmail($to,$subject,$message1,$headers);
            
            return response()->json(['success' => __('table.a_ver')]);
        }
    }

    function deleted_all(Request $request)
    {
        $array = $request->array;
        if ($array == null) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        if (count($array) == 0) {
            return response()->json(['error' => __('language.msg.e')]);
        }
        foreach ($array as $key => $value){
            $Post = User::where('id', '=', $value)->first();
            if ($Post == null) {
                return response()->json(['error' => __('language.msg.e')]);
            }
            $Post->delete();
        }
        return response()->json(['success' => __('language.msg.d')]);
    }

}
