<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeRecord extends Model
{
    public $table = 'trade_record';
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','user_id');
    }

    public function shanji()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','user_id');
    }

    public function userbank()
    {
        return $this->hasOne('App\Models\UserBank', 'id','user_bank_id');
    }
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'user_id','user_id');
    }

    public function self(){
        return $this->hasMany('App\Models\TradeRecord', 'user_id','user_id');
    }
}
