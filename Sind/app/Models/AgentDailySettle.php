<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentDailySettle extends Model
{
    public $table = 'agent_daily_settle';
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo' ,'user_id','agent_user_id');
    }
}
