<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'user';

    public function info()
    {
        return $this->hasOne('App\Models\AgentInfo' ,'user_id','user_id');
    }
    public function uinfo()
    {
        return $this->hasOne('App\Models\UserInfo' ,'user_id','user_id');
    }
    public function childreninfo()
    {
        return $this->hasMany('App\Models\UserInfo' ,'parent_user_id','user_id');
    }
    public function user()
    {
        return $this->hasMany('App\Models\User' ,'user_id','user_id');
    }
    public function agentuser()
    {
        return $this->hasMany('App\Models\UserInfo' ,'user_id','user_id');
    }
    public function wage(){
        return $this->hasOne("App\Models\Wage","user_id","user_id");
    }
    public function bonus(){
        return $this->hasOne("App\Models\Bonus","user_id","user_id");
    }
    public function fandian(){
        return $this->hasOne("App\Models\Fandianset","user_id","user_id");
    }

    public function shangji(){
        return $this->hasOne("App\Models\User","user_id","parent_id");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}