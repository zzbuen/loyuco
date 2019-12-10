<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    public $timestamps = false;
    public function info()
    {
        return $this->hasOne('App\Models\UserInfo', 'user_id','user_id');
    }
}