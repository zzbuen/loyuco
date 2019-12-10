<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentInfo extends Model
{
    protected $table = 'agent_info';
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo' ,'user_id','user_id');
    }
    public function relation()
    {
        return $this->hasOne('App\Models\Relation','user_id','user_id');
    }
}