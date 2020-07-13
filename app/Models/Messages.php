<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = ['user_id', 'request_id', 'offer_id', 'subject', 'content', 'status'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function offer() {
        return $this->belongsTo('App\Models\Offer');
    }
}
