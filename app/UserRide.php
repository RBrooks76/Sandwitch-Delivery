<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRide extends Model
{
    public $table = "user_ride";

    public $fillable = [
        'id',
        'name',
        'phone',
        'country',
        'city',
        'email',
        'license',
        'created_at',
        'updated_at'
    ];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    // public function User(){
    //     return $this->belongsTo(User::class,"user_id","id");
    // }

    public function City(){
        return $this->belongsTo(City::class,"city_id","id");
    }

}
