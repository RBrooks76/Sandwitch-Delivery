<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    public $table = "offers";

    public $fillable = ['id','name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'user_id',
        'city_id',
        'category_id',
        'sub_category_id',
        'product_id',
        'restaurant_id',
        'created_at',
        'updated_at'
    ];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function User(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function Category(){
        return $this->belongsTo(Category::class,"category_id","id");
    }

    public function City(){
        return $this->belongsTo(City::class,"city_id","id");
    }

    public function SubCategory(){
        return $this->belongsTo(SubCategory::class,"sub_category_id","id");
    }

    public function Products(){
        return $this->belongsTo(Products::class,"product_id","id");
    }

    public function Restaurant(){
        return $this->belongsTo(Restaurant::class,"restaurant_id","id");
    }

    public function date(){
        return date_format($this->created_at,'d m,Y');
    }

    public function img(){
        return env('PATH_IMAGE').$this->avatar;
    }

}
