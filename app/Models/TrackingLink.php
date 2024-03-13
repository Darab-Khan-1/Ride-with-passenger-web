<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingLink extends Model
{
    use HasFactory;

    public function trips()
    {
        return $this->belongsToMany(Trip::class,'tracking_links_trips');
    }
}
