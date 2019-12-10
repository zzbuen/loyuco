<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDailySettle extends Model
{
    public $table = 'user_daily_settle';
    public $timestamps = false;
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_id','user_id');
    }
}
