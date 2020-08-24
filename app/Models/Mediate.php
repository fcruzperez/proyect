<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mediate extends Model
{
    protected $fillable = ['client_id', 'designer_id', 'offer_id', 'title', 'content', 'error_images', 'status'];
    protected $appends = ['status_label'];

    public function client() {
        return $this->belongsTo('App\Models\User', 'client_id', 'id');
    }

    public function offer() {
        return $this->belongsTo('App\Models\Offer');
    }

    public function getStatusLabelAttribute() {
        switch($this->status) {
            case 'issued':      $label = 'Issued'; break;
            case 'completed':   $label = 'Completed'; break;
            default:            $label = 'undefined'; break;
        }
        return $label;
    }
}
