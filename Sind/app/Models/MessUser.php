<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessUser extends Model
{
    protected $table = 'mess_user';
    public $timestamps = false;

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }

    public function send_user(){
        return $this->hasOne("App\Models\User","user_id","send_user_id");
    }

    public function userinfo(){
        return $this->hasOne("App\Models\UserInfo","user_id","send_user_id");
    }

}