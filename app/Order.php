<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;


class Order extends Model
{
    public $table = "order";

    public $fillable = [
        'order_id',
        'client_name',
        'phone',
        'ip',
        'phone_active',
        'lat',
        'log',
        'total',
        'status',
        'payment_type',
        'city_id',
        'restaurant_id',
        'user_id',
        'code',
        'b_view',
        'is_pickup',
        'car_number',
        'created_at',
        'updated_at'
    ];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';
    
    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }
    
    public function date(){
        return date_format($this->created_at, 'd m,Y');
    }

    public function Items()
    {
        return $this->hasMany(OrderProducts::class, "order_id", "id");
    }
    
    public function OrderLog()
    {
        return $this->hasMany(OrderLog::class, "log_order_id", "id");
    }

    public function CountPrice()
    {
        return $this->hasMany(OrderProducts::class, "order_id", "id")->sum('total');
    }

    public function City()
    {
        return $this->belongsTo(City::class, "city_id", "id");
    }
    
    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class, "restaurant_id", "id");
    }

    public function status()
    {
        $st = "<span class='badge badge-warning'>Pending</span>";
        if ($this->status == 2) {
            $st = "<span class='badge badge-primary'>Progress</span>";
        } else if ($this->status == 3) {
            $st = "<span class='badge badge-danger'>Rejected</span>";
        } else if ($this->status == 4) {
            $st = "<span class='badge badge-dark'>Accepted</span>";
        } else if ($this->status == 5) {
            $st = "<span class='badge badge-dark'>Completed</span>";
        }
        return $st;
    }

    public function cash()
    {
        switch ($this->payment_type) {
            case 1:
                return "Cash";
            case 2:
                return "Card on delivery";
            case 3:
                return "Online";
            default:
                return 'Unknown';
        }
    }

    public function status1()
    {
        $st = "<td class=\"ball pending\">Pending</td>";
        if ($this->status == 2) {
            $st = "<span class='badge badge-primary'>Progress</span>";
        } else if ($this->status == 3) {
            $st = "<td class=\"ball rejected\">Rejected</td>";
        } else if ($this->status == 4) {
            $st = "<td class=\"ball accepted\">Canceled</td>";
        } else if ($this->status == 5) {
            $st = "<td class=\"ball accepted\">Accepted</td>";
        }
        return $st;
    }
}
