<?php

namespace App\Jobs;

use App\Models\DrawResultJnd;
use App\Models\GameConfig;
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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Jnd28Periods implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    public $delay = [];
    public $time;
    public $config;
    public function __construct($time)
    {
        $this->time = $time;
        $this->queue = 'Jnd28';
    }

    public function handle()
    {
        Log::info('Jnd进入生成期数1,开奖时间:'.$this->time.'/当前时间:'.Carbon::now()->toDateTimeString());
        $list = DrawResultJnd::query()->whereDate('start_time',Carbon::tomorrow()->toDateString())->first();
        if($list){
            return false;
        }
        Log::info('Jnd进入生成期数2,开奖时间:'.$this->time.'/当前时间:'.Carbon::now()->toDateTimeString());
        $this->config = GameConfig::query()->where('id',5)->first();
        $period_list = [];
        $daily_start = $this->time;
        $start_time = strtotime($daily_start);//strtotime(Carbon::now()->toDateString() . ' ' . '21:04:20');//strtotime($daily_start);
        $end_time = strtotime(Carbon::tomorrow()->toDateString() . ' ' . '19:03:30');
        $time = $end_time - $start_time;
        $flag = ceil($time/210);
        $start_time = $end_time - $flag*210;
        $allGameList = getTheGameTable(5);
        $last_period_number = $allGameList->orderBy('id','desc')->value('periods');
        while ($start_time < $end_time) {
            $last_period_number += 1;
            $good = [];
            $good['game_id'] = $this->config['game_id'];
            $good['type'] = $this->config['game_type'];
            $good['periods'] = $last_period_number;
            $good['start_time'] = date('Y-m-d H:i:s', $start_time);
            $good['bet_end_time'] = date('Y-m-d H:i:s', $start_time +  $this->config['period_bet_time']);
            $good['kaijiang_time'] = date('Y-m-d H:i:s', $start_time + $this->config['period_draw_time']);
            $good['update_at'] = date('Y-m-d H:i:s');
            $period_list[] = $good;
            $start_time += $this->config['period_time'];
    }
        if (!DrawResultJnd::query()->insert($period_list)) {
            Log::info('Jnd进入生成期数3,开奖时间:'.$this->time.'/当前时间:'.Carbon::now()->toDateTimeString());
            return false;
        }
        Log::info('Jnd进入生成期数4,开奖时间:'.$this->time.'/当前时间:'.Carbon::now()->toDateTimeString());
        $thePeriod = $allGameList->orderBy('id','desc')->value('periods');
        DrawResultJnd::query()->where('periods',$thePeriod)->update([
            'kaijiang_time'=>Carbon::tomorrow()->toDateString().' '. '20:00:00'
        ]);
        Cache::store('redisCache')->add('Jnd28s',1, 300);
        return true;
    }

}
