<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payoff extends Model
{
    public $table = 'payoff';
    public $timestamps = false;
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
}
