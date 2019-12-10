<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Active extends Model
{
    protected $table = 'active_new';
    public $timestamps = false;

    public function user(){
       return $this->hasOne("App\Models\User","user_id","user_id");
    }
    public function parent(){
        return $this->hasOne("App\Models\User","user_id","parent_user_id");
    }
}