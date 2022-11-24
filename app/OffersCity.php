<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffersCity extends Model
{
    public $table = "offers_city";

    public $fillable = ['id','name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'user_id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function City(){
        return $this->belongsTo(City::class,"city_id","id");
    }

    public function Offers(){
        return $this->belongsTo(Offers::class,"offers_id","id");
    }

}
