<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantCategory extends Model
{
    public $table = "restaurant_sub_cat";

    public $fillable = ['id','name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'restaurant_id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function Category(){
        return $this->belongsTo(Category::class,"category_id","id");
    }

    public function Restaurant(){
        return $this->belongsTo(Restaurant::class,"restaurant_id","id");
    }

}
