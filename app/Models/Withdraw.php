<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model{
    protected $fillable = ['designer_id', 'total', 'fee', 'paid', 'status', 'pending_at', 'paid_at'];

    protected $appends = ['status_label'];

    public function offers() {
        return $this->hasMany('App\Models\Offer');
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'pending': return 'Pending';
            case 'paid': return 'Paid';
            default: return 'undefined';
        }
    }

    public function designer() {
        return $this->belongsTo('App\Models\User', 'designer_id', 'id');
    }
}
