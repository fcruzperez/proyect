<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    //
    protected $fillable = ['client_fee', 'designer_fee', 'minimum_work_time', 'minimum_work_price', 'delta_time',
        'claim_time', 'correction_time', 'payment_time_to_designer', 'minimum_withdrawal_amount'];
}
