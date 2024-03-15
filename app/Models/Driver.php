<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Driver extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function trips(){
        return $this->hasMany(Trip::class,'user_id','user_id');
    }
}
