<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $table = 'user';
    public $timestamps = false;

    public function shangji(){
        return $this->hasOne("App\Models\User","user_id","parent_user_id");
    }
    public function userinfo(){
        return $this->hasOne("App\Models\UserInfo","user_id","user_id");
    }

    public function userbank(){
        return $this->hasMany("App\Models\UserBank","user_id","user_id");
    }

    public function wage(){
        return $this->hasMany("App\Models\Wage","user_id","user_id");
    }
    public function bonus(){
        return $this->hasMany("App\Models\Bonus","user_id","user_id");
    }

    public function wage_status(){
        return $this->hasOne("App\Models\Wage","user_id","user_id");
    }

    public function bonus_status(){
        return $this->hasOne("App\Models\Bonus","user_id","user_id");
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
