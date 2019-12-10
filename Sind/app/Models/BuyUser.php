<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyUser extends Model
{
    protected $table = 'user';
    public $timestamps = false;
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id','id');
    }
    public function agent()
    {
        return $this->hasOne('App\Models\Agent', 'user_id','user_id');
    }

    public function account(){
        return $this->hasMany("App\Models\Account","user_id","user_id");
    }
}