<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    public $table = "newsletter";

    public $fillable = ['id', 'name',
        'email',
        'created_at', 'updated_at'];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';
}
