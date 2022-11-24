<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model{
    protected $guarded = [];
    
    public function Drivers(){
        return $this->belongsTo(MyDriver::class,"driver_id","id");
    }
}
