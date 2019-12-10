<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderBackup extends Model
{
    public $table = 'order_backup';
    public $timestamps = false;
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','user_id');
    }
}
