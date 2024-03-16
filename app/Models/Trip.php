<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Trip extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('customer', function ($query) {
            if (Auth::check() && Auth::user()->type === 'customer') {
                $query->where('customer_id', Auth::id());
            }
        });
    }

    public function stops()
    {
        return $this->hasMany(Stop::class, 'trip_id', 'id');
    }
    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id', 'user_id');
    }
    public function attributes()
    {
        return $this->hasOne(Attribute::class, 'refrence_id', 'id');
    }
    public function trackingLinks()
    {
        return $this->belongsToMany(TrackingLink::class, 'tracking_links_trips');
    }
}
