<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zhuanzhang extends Model
{
    protected $table = 'zhuanzhang';
    public $timestamps = false;

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }
    public function account(){
        return $this->hasOne("App\Models\Account","user_id","user_id");
    }
    public function account_agent(){
        return $this->hasOne("App\Models\AccountAgent","user_id","user_id");
    }
}