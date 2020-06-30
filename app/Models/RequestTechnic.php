<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestTechnic extends Model
{
    protected $table = 'request_technic';
    protected $fillable = ['request_id','technic_id'];
}
