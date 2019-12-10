<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journalaccount extends Model
{
    protected $table = 'journal_account';
    public $timestamps = false;

    public function status(){
        return $this->hasOne("App\Models\Accountstatus","id","change_status");
    }

    public function info(){
        return $this->hasOne("App\Models\UserInfo","user_id","user_id");
    }
    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }

}