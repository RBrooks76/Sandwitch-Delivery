<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    public $table = "partners";

    public $fillable = ['id',
        'products_id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function name(){
        return $this->avatar;
    }

    public function img(){
        return url('/').env('PATH_IMAGE').'upload/partners/'.$this->avatar;
    }

}
