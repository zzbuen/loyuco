<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
    protected $table = 'game_type';
    public $timestamps = false;

    public function game(){
        return $this->hasOne("App\Models\Game","id","type");
    }
}