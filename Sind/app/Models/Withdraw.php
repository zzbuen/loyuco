<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraw';
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','user_id');
    }
    public function userbank()
    {
        return $this->hasOne('App\Models\UserBank', 'id','user_bank_id');
    }
}