<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    public $table = "email_setting";

    public $fillable = [
        'id', 'hours',
        'phone',
        'created_at', 'updated_at'
    ];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';
}
