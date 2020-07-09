<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['designer_id', 'request_id', 'offer_id', 'path'];

    public function designer() {
        return $this->belongsTo('App\Models\User', 'designer_id', 'id');
    }

    public function request() {
        return $this->belongsTo('App\Models\Request');
    }

    public function offer() {
        return $this->belongsTo('App\Models\Offer');
    }
}
