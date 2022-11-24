<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HPContactUS extends Model
{
    public $table = "hp_contact_us";

    public $fillable = ['id','hours',
        'phone',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

}
