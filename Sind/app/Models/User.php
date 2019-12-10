<?php
/**
 * Created by 信.
 * Date: 2018/3/28
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $table = 'user';

    public function userinfo(){
        return $this->hasOne("App\Models\UserInfo","user_id","user_id");
    }

    public function userbank(){
        return $this->hasMany("App\Models\UserBank","user_id","user_id");
    }

    public function shangji(){
        return $this->hasOne("App\Models\User","user_id","parent_user_id");
    }

    public function wage(){
        return $this->hasOne("App\Models\Wage","user_id","user_id");
    }

    public function wage_status(){
        return $this->hasOne("App\Models\Wage","user_id","user_id");
    }


    public function bonus(){
        return $this->hasOne("App\Models\Bonus","user_id","user_id");
    }

    public function bonus_status(){
        return $this->hasOne("App\Models\Bonus","user_id","user_id");
    }
}