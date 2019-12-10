<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    public $timestamps = false;
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'user_id','user_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\BuyUser', 'user_id','user_id');
    }
    public function odds()
    {
        return $this->hasOne('App\Models\Odds', 'serial_num','serial_num');
    }
    public function game()
    {
        return $this->hasOne('App\Models\Game', 'id','gameId');
    }
}