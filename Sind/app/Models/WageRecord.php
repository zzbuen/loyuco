<?php
/**
 * Created by 信.
 * Date: 2018/3/28
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WageRecord extends Model{
    protected $table = 'wage_record';
    public $timestamps = false;

    public function info(){
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User', 'user_id','user_id');
    }
    public function wage(){
        return $this->hasOne('App\Models\Wage', 'user_id','user_id');
    }
    public function daili(){
        return $this->hasOne('App\Models\User', 'user_id','parent_id');
    }
}