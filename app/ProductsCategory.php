<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsCategory extends Model
{
    public $table = "products_sub_cat";

    public $fillable = [
        'sub_category_id', 'products_id',
        'created_at', 'updated_at'
    ];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';

    public function SubCategory()
    {
        return $this->belongsTo(SubCategory::class, "sub_category_id", "id");
    }

    public function Products()
    {
        return $this->belongsTo(Products::class, "products_id", "id");
    }
}
