<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
    public $table = "hp_contents";

    public $fillable = ['id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function img1(){
        return url('/').env('PATH_IMAGE').$this->avatar1;
    }

    public function img2(){
        return url('/').env('PATH_IMAGE').$this->avatar2;
    }

    public function img3(){
        return url('/').env('PATH_IMAGE').$this->avatar3;
    }

    public function img4(){
        return  url('/').env('PATH_IMAGE').$this->avatar4;
    }

    public function date(){
        return date_format($this->created_at,'d m,Y');
    }

}
