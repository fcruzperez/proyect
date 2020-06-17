<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignerPayment extends Model
{
    protected $fillable = ['designer_id', 'amount', 'status'];

    public function offers() {
      return $this->hasMany('App\Models\Offer');
    }
}
