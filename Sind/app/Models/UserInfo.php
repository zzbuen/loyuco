<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'userinfo';
    public $timestamps = false;

    public function money(){
        return $this->hasOne("App\Models\TradeRecord","user_id","user_id");
    }

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }

    public function shangji(){
        return $this->hasOne("App\Models\User","user_id","parent_user_id");
    }
}