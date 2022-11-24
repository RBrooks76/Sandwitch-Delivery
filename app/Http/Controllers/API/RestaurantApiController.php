<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use App\Restaurant;
use App\RestaurantReview;
use App\Products;
use App\ProductsCategory;
use App\Http\Controllers\Dashboard\Common;
use App\UserRestaurant;

class RestaurantApiController extends Controller
{
    public function GetRestPrice(Request $request){
        $Input = $request->all();
        $ProID = array();
        
        foreach($Input as $INP){
            $ProID[] = $INP['product_id'];
        }
        
        $AllPrd = Products::whereIn("id", $ProID)->get();
        
        $NewData = array();
        
        foreach($AllPrd as $RP){
            $NewData[] = array("product_id" => $RP->id, "restaurant_id" => $RP->restaurant_id, "price" => $RP->amount);
        }
        
        return response()->json($NewData, 200);
    }
    
    public function GetRestaurant()
    {
        $restaurants = Restaurant::with('category')->with('city')->with('user')->get();
        return response()->json($restaurants, 200);
    }
    // Zita added
    public function SearchRestaurant(Request $request)
    {
        $search = $request->search_text;
        $city_id = $request->city_id;
        $restaurants = Restaurant::with('user')->whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })->where('restaurant_city', $city_id)->where("active", 1)->get();
        
        foreach($restaurants as $item){
            $item->user_id = $item->user_id."";
            $item->restaurant_city = $item->restaurant_city."";
            $item->restaurant_category = $item->restaurant_category."";
             $item->priority = $item->priority."";
            $item->status = $item->status."";
            $item->all_priority = $item->all_priority."";
        }
        
        return response()->json($restaurants, 200);
    }
    // Zita added

    public function GetProduct($id)
    {
        $products = Products::with('productsfeature')->where('restaurant_id', $id)->get();

        return response()->json($products, 200);
    }

    public function GetProductSearch(Request $request)
    {
        $search = $request->search;
        if ($request->city_id) {
            $city_id = $request->city_id;
            $products = Products::with('productsfeature')->whereHas("Restaurant", function ($q) use ($city_id) {
                if ($city_id) {
                    $q->where("restaurant_city", $city_id);
                }
            })->where('name', 'like', "%$search%")->get();
        } else {
            $products = Products::with('productsfeature')->where('name', 'like', "%$search%")->get();
        }
        foreach ($products as $product) {
            $product->restaurant_name = $product->Restaurant->name;
        }
        return response()->json($products, 200);
    }

    public function GetRestaurantCategory($id)
    {
        $restaurant = Restaurant::with('user')->where('id', $id)->first();
        
        $restaurant->views = $restaurant->views + 1;
        $restaurant->save();
        
        $restaurant = Restaurant::with('user')->where('id', $id)->first();
        
        $restaurant->user_id = $restaurant->user_id."";
        $restaurant->restaurant_city = $restaurant->restaurant_city."";
        $restaurant->priority = $restaurant->priority."";
        $restaurant->status = $restaurant->status."";
        $restaurant->all_priority = $restaurant->all_priority."";
        $restaurant->restaurant_category = $restaurant->restaurant_category."";
        $restaurant->City = $restaurant->City->name;
        
        $restaurant->total_rating = RestaurantReview::where('restaurant_id', $id)->avg('star');
        $comment = RestaurantReview::where('restaurant_id', $id)->get();
        
        foreach($comment as $cmt){
            $cmt->star = $cmt->star."";
            $cmt->restaurant_id = $cmt->restaurant_id."";
        }
        
        $product_ids = Products::where('restaurant_id', $id)->pluck('id');
        $product_sub_cats = ProductsCategory::select("products_sub_cat.*")->leftjoin("sub_category", "sub_category.id", "products_sub_cat.sub_category_id")->whereIn('products_id', $product_ids)->orderBy('priority')->get();
        $products = [];
        if (count($product_sub_cats) > 0) {
            foreach ($product_sub_cats as $sub) {
                $SsubCat = Products::with('productsfeature')->where('restaurant_id', $id)->where('id', $sub['products_id'])->orderBy("priority")->get();
                
                foreach($SsubCat as $cats){
                    $cats->restaurant_id = $cats->restaurant_id."";
                    $cats->priority = $cats->priority."";
                    foreach($cats->productsfeature as $Fet){
                        $Fet->level = $Fet->level."";
                        $Fet->products_id = $Fet->products_id."";
                    }
                }
                
                array_push($products, [
                    'sub_cat_name' => $sub['subcategory']['name'],
                    'sub_cat_id' => $sub['subcategory']['id'],
                    'priority' => $sub['subcategory']['priority']."",
                    'products' => $SsubCat
                ]);
            }
        }

        return response()->json(compact('restaurant', 'comment', 'products'), 200);
    }

    public function GetVerifySMS($phone)
    {
        $code = rand(100000, 999999);
        $message = "Please verify your Phone using code " . $code;
        Common::SendTextSMS($phone, $message);
        return json_encode(["code" => $code]);
    }
    
    public function Register(Request $request){
        $x = [
            'shop_name' => 'required|min:1|max:191',
            'website' => 'required|min:1|max:191',
            'owner_name' => 'required|min:1|max:191',
            'city_id' => 'required|min:1|max:191',
            'phone' => 'required|min:1|numeric',
            'email' => 'required|string|email|max:255',
        ];
        
        $validation = Validator::make($request->all(), $x);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {

            $restaurant = new UserRestaurant();
            $restaurant->name = $request->shop_name;
            $restaurant->email = $request->email;
            $restaurant->username = $request->owner_name;
            $restaurant->website = $request->website;
            $restaurant->city_id = $request->city_id;
            $restaurant->phone = $request->phone;

            $restaurant->save();

            $to = $request->email;
            $subject = "Sandwich Map";
            $message = 'Your Application has been successfully submitted
                        Expect a call form us shortly

                        Thank you for applying at SandwichMap Restaurants Team

                        for more support Please What’s up this Number 0501212770';

                                    $headers = "From:   noreply@icheck-antibody.jp" . "\r\n";

                                    $message1 = 'Your Application has been successfully submitted
                        Expect a call form us shortly

                        Thank you for applying at SandwichMap Restaurants Team

                        for more support Please What’s up this Number 0501212770.

                        Please do not reply to this email.

                        We would also be happy to receive your feedback - suggestions and complaints on
                        Management Email : sandwichmap@yahoo.com

                        Owner Email :
                        i.osmann@yahoo.com
                        fs.aljabri@yahoo.com

                        Sincerely,

                        Sandwich Map LLC
                        Restaurants Partners Support Team
                        UAE';
            Common::SendTextSMS($request->phone, $message);
            Common::SendEmail($to,$subject,$message1,$headers);
            
            return response()->json(['success'=>'The account has been opened. You will be contacted']);
        }
    }
}
