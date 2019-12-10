<?php
/**
 * Created by 信.
 * Date: 2018/3/28
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusRecord extends Model{
    protected $table = 'bonus_record';
    public $timestamps = false;

    public function info(){
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User', 'user_id','user_id');
    }
    public function bonus(){
        return $this->hasOne('App\Models\Bonus', 'user_id','user_id');
    }
    public function daili(){
        return $this->hasOne('App\Models\User', 'user_id','parent_id');
    }
}