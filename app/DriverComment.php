<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class DriverComment extends Model{
    protected $guarded = [];
    
    public function Drivers(){
        return $this->belongsTo(MyDriver::class,"driver_id","id");
    }
}
