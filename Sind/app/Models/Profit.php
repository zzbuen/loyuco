<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $table = 'profit';
    public function orderinfo()
    {
        return $this->hasOne('App\Models\Order' ,'id','order_id');
    }
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','user_id');
    }
    public function agentinfo()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','agent_user_id');
    }
    public function agentuser()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','agent_user_id');
    }
}