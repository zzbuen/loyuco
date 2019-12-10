<?php
namespace App\Models;

use function GuzzleHttp\Psr7\_parse_request_uri;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'relation';
    public $timestamps = false;

    public function user(){
        return $this->hasOne("App\Models\User","user_id","user_id");
    }
}