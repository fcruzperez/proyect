<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFabric extends Model
{
    protected $table = 'request_fabric';
    protected $fillable = ['request_id','fabric_id'];
}
