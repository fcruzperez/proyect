<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestImage extends Model
{
    protected $fillable = ['request_id', 'url'];
}
