<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function requests() {
      if($this->role !== 'client') return [];

      return $this->hasMany('App\Models\Request', 'client_id', 'id');
    }

    public function offers() {
      if($this->role !== 'designer') return [];

      return $this->hasMany('App\Models\Offer', 'designer_id', 'id');
    }

    public function rate() {
      if($this->role !== 'designer') return 0;

      $rates = $this->hasMany('App\Models\DesignerRate', 'designer_id', 'id');

      $count = $rates->count();
      if($count === 0) return 0;
      $sum = 0;
      foreach ($rates as $rate) {
        $sum += $rate->rate;
      }
      return $sum / $count;
    }

}
