<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScriptsSetting extends Model
{
    public $table = "scripts_setting";

    public $fillable = ['id','hours',
        'phone',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

}
