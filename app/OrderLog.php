<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    public $fillable = [
        'log_order_id',
        'message',
        'type',
        'created_at',
        'updated_at'
    ];
    
    public function Orders()
    {
        return $this->belongsTo(Order::class, "log_order_id", "id");
    }
}
