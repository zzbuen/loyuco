<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryOdds extends Model
{
    protected $table = 'temporary_odds';
    public $timestamps = false;
    public function category()
    {
//        return $this->belongsToMany('App\Models\Category');
        return $this->hasOne('App\Models\Category', 'id','category_id');
    }
    public function rule()
    {
        return $this->belongsTo('App\Models\Rule');
    }
}