<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveUser extends Model
{
    protected $table = 'active_user';
    public $timestamps = false;

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }

    public function active(){
        return $this->hasOne("App\Models\Active","id","active_id");
    }
}