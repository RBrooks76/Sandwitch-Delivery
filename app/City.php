<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $table = "city";

    public $fillable = [
        'id', 'name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'user_id',
        'priority',
        'created_at', 'updated_at'
    ];

    public $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'id';

    public function User()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function date()
    {
        return date_format($this->created_at, 'd m,Y');
    }
}
