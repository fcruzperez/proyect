<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFormat extends Model
{
    protected $table = 'request_format';
    protected $fillable = ['request_id','format_id'];
}
