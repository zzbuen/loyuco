<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityAnswer extends Model
{
    protected $table = 'security_answer';
    public $timestamps = false;

    public function question(){
        return $this->hasOne("App\Models\SecurityQuestion","id","question_id");
    }

}