<?php
namespace App;

use App\City;
use App\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Driver extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone_number', 'restaurant_id', 'vehicle_type', 'nationality', 'licence'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
