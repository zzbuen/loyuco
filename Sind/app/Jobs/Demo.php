<?php

namespace App\Jobs;

use App\Models\Grow;
use App\Models\GrowBonusRecord;
use App\Models\System;
use App\Models\UserAssets;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Demo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    public $delay = [];

    public function __construct()
    {
    }

    public function handle()
    {
        Log::info('测试的123 在跑'.Carbon::now()->toDateString());
//        $this->a();
//      	$this->a();
//        $this->a();
//        $this->a();
//        $this->a();
    }
    public function a(){
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://0.0.0.0:9502");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1); //设置为POST方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //设置post数据
        $post_data = [
            'room_id'=> '2',
            'user_id'=> '2',
            'type'=>'message',
            'message'=> '测试',
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        var_dump($result);
        curl_close($ch);
        return;
    }
}
