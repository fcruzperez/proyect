<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
      'client_id', 'name', 'width', 'height', 'hours', 'technic_id', 'deposit', 'status', 'accepted_offer_id',
      'accepted_at', 'completed_at', 'mediated_at', 'canceled_at'
    ];

    public function images() {
      return $this->hasMany('App\Models\RequestImage');
    }

    public function technics() {
      return $this->belongsToMany('App\Models\Technic');
    }

    public function fabrics() {
      return $this->belongsToMany('App\Models\Fabric');
    }

    public function client() {
      return $this->hasOne('App\Models\User', 'id', 'client_id');
    }

    public function offers() {
      return $this->hasMany('App\Models\Offer');
    }
}
