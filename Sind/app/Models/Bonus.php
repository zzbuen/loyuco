<?php
/**
 * Created by 信.
 * Date: 2018/3/28
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model{
    protected $table = 'bonus';
    public $timestamps = false;

    public function userinfo(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }
    public function shangji(){
        return $this->hasOne("App\Models\User","user_id","agent_id");
    }
}