<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffersRestaurant extends Model
{
    public $table = "offers_restaurant";

    public $fillable = ['id','name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'user_id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function Restaurant(){
        return $this->belongsTo(Restaurant::class,"restaurant_id","id");
    }

    public function Offers(){
        return $this->belongsTo(Offers::class,"offers_id","id");
    }

}
