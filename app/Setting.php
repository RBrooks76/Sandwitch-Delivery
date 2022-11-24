<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $table = "setting";

    public $fillable = ['id','name',
        'hours',
        'language_id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function Language(){
        return $this->belongsTo(Language::class,"language_id","id");
    }

    public function fav(){
        return url('/').env('PATH_IMAGE').$this->fav;
    }

    public function avatar(){
        return url('/').env('PATH_IMAGE').$this->avatar;
    }

    public function avatar1(){
        return url('/').env('PATH_IMAGE').$this->avatar1;
    }

    public function bunner(){
        return url('/').env('PATH_IMAGE').$this->bunner;
    }

    public function contact(){
        return url('/').env('PATH_IMAGE').$this->contact;
    }

}
