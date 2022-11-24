<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact_page extends Model
{
    public $table = "hp_contents";

    public $fillable = ['id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

}
