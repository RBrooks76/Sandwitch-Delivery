<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProductsFeature extends Model
{
    public $table = "order_products_feature";

    public $fillable = [
        'order_products_id',
        'products_feature_id',
        'created_at',
        'updated_at'
    ];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';

    public function OrderProducts()
    {
        return $this->belongsTo(OrderProducts::class, "order_products_id", "id");
    }

    public function ProductsFeature()
    {
        return $this->belongsTo(ProductsFeature::class, "products_feature_id", "id");
    }
}
