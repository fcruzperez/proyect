<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'client_id', 'image1', 'image2', 'image3', 'image4', 'image5', 'nick_name', 'design_name', 'width', 'height', 'hours', 'deposit', 'status', 'accepted_offer_id',
        'accepted_at', 'delivered_at', 'completed_at', 'mediated_at', 'canceled_at'
    ];

//    public function images() {
//      return $this->hasMany('App\Models\RequestImage');
//    }

    public function technics()
    {
        return $this->belongsToMany('App\Models\Technic', 'request_technic');
    }

    public function fabrics()
    {
        return $this->belongsToMany('App\Models\Fabric', 'request_fabric');
    }

    public function formats()
    {
        return $this->belongsToMany('App\Models\Format', 'request_format');
    }

    public function client()
    {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery');
    }
}
