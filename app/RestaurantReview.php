<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantReview extends Model
{
    public $table = "restaurant_comment";

    public $fillable = [
        'id',
        'comment',
        'star',
        'restaurant_id',
        'user_id',
        'created_at',
        'updated_at',
        'client_name',
        'client_phone'
    ];


    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function Restaurant(){
        return $this->belongsTo(Restaurant::class,"restaurant_id","id");
    }

    public function date(){
        return date_format($this->created_at,'d m,Y');
    }
}
