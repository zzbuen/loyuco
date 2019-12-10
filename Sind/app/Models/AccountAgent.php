<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountAgent extends Model
{
    protected $table = 'account_agent';
    public $timestamps = false;

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }
    public function into_user(){
        return $this->hasOne("App\Models\User","user_id","into_user_id");
    }
    public function oneself(){
        return $this->hasOne("App\Models\AccountAgent","user_id","user_id");
    }
}