<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odds extends Model
{
    protected $table = 'odds';
    public $timestamps = false;
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id','category_id');
    }
    public function rule()
    {
        return $this->belongsTo('App\Models\Rule');
    }
}