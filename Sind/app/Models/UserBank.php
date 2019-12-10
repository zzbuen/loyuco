<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    protected $table = 'user_bank';
    public $timestamps = false;
    public function bankname()
    {
        return $this->hasOne('App\Models\Bank', 'id','bank_id');
    }
}