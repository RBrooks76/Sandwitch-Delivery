<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $table = "post";

    public $fillable = ['id','name',
        'type',
        'summary',
        'avatar',
        'language_id',
        'user_id',
        'created_at','updated_at'];

    public $dates = ['created_at','updated_at'];
    public $primaryKey = 'id';

    public function User(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function date(){
        return date_format($this->created_at,'d m,Y');
    }

    public function img(){
        return env('PATH_IMAGE').$this->avatar;
    }

    public function route(){
        return route('post',['id'=>$this->id,'name'=>$this->name]);
    }

}
