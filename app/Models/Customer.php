<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Customer extends Model
{
    use HasFactory;

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }


    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function locations() {
        return $this->hasMany(CustomerLocation::class, 'customer_id', 'id');
    }
}
