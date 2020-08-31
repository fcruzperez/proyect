<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
      'designer_id', 'request_id', 'withdraw_id', 'price', 'fee', 'paid', 'hours', 'status', 'real_state',
      'accepted_at', 'delivered_at', 'mediated_at', 'canceled_at', 'completed_at', 'paid_at'
      ];

    public function designer() {
      return $this->belongsTo('App\Models\User', 'designer_id', 'id');
    }

    public function request() {
      return $this->belongsTo('App\Models\Request');
    }

//    public function withdraw() {
//      return $this->belongsTo('App\Models\Withdraw');
//    }
}
