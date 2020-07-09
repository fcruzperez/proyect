<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
      'designer_id', 'request_id', 'price', 'hours', 'status',
      'accepted_at', 'delivered_at', 'mediated_at', 'canceled_at', 'completed_at'
      ];

    public function designer() {
      return $this->belongsTo('App\Models\User', 'designer_id', 'id');
    }

    public function request() {
      return $this->belongsTo('App\Models\Request');
    }
}
