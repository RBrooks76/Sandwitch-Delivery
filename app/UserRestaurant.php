<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRestaurant extends Model
{
    public $table = "user_restaurant";

    public $fillable = ['id','hours',
        'phone',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    // public function User(){
    //     return $this->belongsTo(User::class,"user_id","id");
    // }

    public function City(){
        return $this->belongsTo(City::class,"city_id","id");
    }

}
