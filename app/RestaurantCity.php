<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantCity extends Model
{
    public $table = "restaurant_city";

    public $fillable = [
        'id', 'name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'user_id',
        'created_at', 'updated_at'
    ];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';

    public function City()
    {
        return $this->belongsTo(City::class, "city_id", "id");
    }

    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class, "restaurant_id", "id");
    }
}
