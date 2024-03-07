<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    public function stops(){
        return $this->hasMany(Stop::class,'trip_id','id');
    }
    public function driver(){
        return $this->hasOne(Driver::class,'user_id','user_id');
    }
    public function attributes(){
        return $this->hasOne(Attribute::class,'refrence_id','id');
    }
}
