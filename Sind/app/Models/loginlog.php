<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
    protected $table = 'login_log';
    public $timestamps = false;

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }

    public function userinfo(){
        return $this->hasOne("App\Models\UserInfo","user_id","user_id");
    }

    public function session(){
        return $this->hasOne("App\Models\Session","user_id","user_id");
    }
}