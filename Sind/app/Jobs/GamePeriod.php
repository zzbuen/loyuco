<?php

namespace App\Jobs;

use App\Models\DrawResultAnhui;
use App\Models\DrawResultBeijing;
use App\Models\DrawResultBeijingkl8;
use App\Models\DrawResultBeijingpk;
use App\Models\DrawResultfucai;
use App\Models\DrawResultChongqing;
use App\Models\DrawResultFenfen3d;
use App\Models\DrawResultFenfenpk;
use App\Models\DrawResultFucai3d;
use App\Models\DrawResultHanguo;
use App\Models\DrawResultHenei;
use App\Models\DrawResultJiangsu;
use App\Models\DrawResultJisupk;
use App\Models\DrawResultJisussc;
use App\Models\DrawResultJnd;
use App\Models\DrawResultLiuhecai;
use App\Models\DrawResultOuzhou;
use App\Models\DrawResultPailie3;
use App\Models\DrawResultPCDD;
use App\Models\DrawResultTengxun;
use App\Models\DrawResultTenxun;
use App\Models\DrawResultTianjin;
use App\Models\DrawResultXinjiang;
use App\Models\DrawResultXinjiapo;
use App\Models\DrawResultGuangdong;
use App\Models\DrawResultShandong;
use App\Models\DrawResultShanghai;
use App\Models\DrawResultJiangxi;
use App\Models\DrawResultXyft;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class GamePeriod implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $config;
    public $tries = 20;
    //public $delay = [];

    public function     __construct($config)
    {
        $this->config = $config;
        $this->queue = 'createGoods';
    }

    public function handle()
    {
        Log::info('创建期数-ID为:'.$this->config['id'].'的明日期数');
        $week = Carbon::tomorrow()->dayOfWeek;
        $period_list = [];
        $today = Carbon::tomorrow()->toDateString();
        $start_time = strtotime($today . ' ' . $this->config['daily_start']);
        $end_time = strtotime($today . ' ' . $this->config['daily_end']);
        $weeks = explode(',', $this->config['weeks']);
        if (!in_array($week, $weeks)) {
            return true;
        }
        if ($start_time > $end_time) {
            $end_time += 24*60*60;
        }
        $allGameList = getTheGameTable($this->config['game_id']);
        if($this->config['game_id']!=3){
            $allGameListCount = $allGameList->whereDate('start_time',Carbon::tomorrow()->toDateString())->count();
            if($allGameListCount>0){
                Log::info('明日的期数已生成,游戏ID为:'.$this->config['game_id']);
                return true;
            }
        }else{
            $allGameListCount = $allGameList->whereDate('start_time',date('Y-m-d',strtotime("+2 day")))->count();
            if($allGameListCount>0){
                Log::info('明日的期数已生成,游戏ID为:'.$this->config['game_id']);
                return true;
            }
        }
        /*判断明天的开奖期数是否已生成*/
        switch ($this->config['id']) {
            case 1:
                $last_period_number = date('Ymd',time()+86400).'000';
                $count = 0;
                while ($start_time < $end_time) {
                    $count ++;
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
                    if(($this->config['id'] == 1) && $count==9){
                        $start_time += 14400;
                    }
                }
                if (!DrawResultChongqing::query()->insert($period_list)) {
                    Log::info('重庆时时彩生成明日期数失败');
                    return false;
                }
                return true;
                break;
            case 2:
                $allGameList = getTheGameTable(2);
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
                if (!DrawResultBeijingpk::query()->insert($period_list)) {
                    Log::info('北京赛车PK10生成期数失败');
                    return false;
                }
                return true;
                break;
            case 3:
                $last_period_number = date('Ymd',time()+86400).'000';
                while ($start_time < $end_time) {
                    $last_period_number += 1;
                    $good = [];
                    $good['game_id'] = $this->config['game_id'];
                    $good['type'] = $this->config['game_type'];
                    $good['periods'] = $last_period_number;
                    $good['start_time'] = date('Y-m-d H:i:s', $start_time);
                    $good['bet_end_time'] = date('Y-m-d H:i:s', $start_time +  $this->config['period_bet_time']);//300秒
                    $good['kaijiang_time'] = date('Y-m-d H:i:s', $start_time + $this->config['period_draw_time']);//360秒
                    $good['update_at'] = date('Y-m-d H:i:s');
                    $period_list[] = $good;
                    $start_time += $this->config['period_time'];
                }
                if (!DrawResultXyft::query()->insert($period_list)) {
                    Log::info('幸运飞艇生成明日期数失败');
                    return false;
                }
                return true;
                break;
            case 4:
//                $allGameList = getTheGameTable(4);
//                $last_period_number = $allGameList->orderBy('id','desc')->value('periods');
                $last_period_number = date('Ymd',time()+86400).'000';

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
                if (!DrawResultPCDD::query()->insert($period_list)) {
                    Log::info('北京PC蛋蛋生成期数失败');
                    return false;
                }
                return true;
                break;
            case 5:
//                $last_period_number = date('Ymd',time()+86400).'000';
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
                    Log::info('北京28成期数失败');
                    return false;
                }
                return true;
                break;
            case 6:
                $last_period_number = date('Ymd',time()+86400).'0000';
                while ($start_time < $end_time) {
                    $last_period_number += 1;
                    $good = [];
                    $good['game_id'] = $this->config['game_id'];
                    $good['type'] = $this->config['game_type'];
                    $good['periods'] = $last_period_number;
                    $good['start_time'] = date('Y-m-d H:i:s', $start_time);
                    $good['bet_end_time'] = date('Y-m-d H:i:s', $start_time +  $this->config['period_bet_time']);//300秒
                    $good['kaijiang_time'] = date('Y-m-d H:i:s', $start_time + $this->config['period_draw_time']);//360秒
                    $good['update_at'] = date('Y-m-d H:i:s');
                    $period_list[] = $good;
                    $start_time += $this->config['period_time'];
                }
                if (!DrawResultTenxun::query()->insert($period_list)) {
                    Log::info('腾讯分分彩生成明日期数失败');
                    return false;
                }
                return true;
                break;
            case 8:
                $last_period_number = date('Ymd',time()+86400).'0000';
                while ($start_time < $end_time) {
                    $last_period_number += 1;
                    $good = [];
                    $good['game_id'] = $this->config['game_id'];
                    $good['type'] = $this->config['game_type'];
                    $good['periods'] = $last_period_number;
                    $good['start_time'] = date('Y-m-d H:i:s', $start_time);
                    $good['bet_end_time'] = date('Y-m-d H:i:s', $start_time +  $this->config['period_bet_time']);//300秒
                    $good['kaijiang_time'] = date('Y-m-d H:i:s', $start_time + $this->config['period_draw_time']);//360秒
                    $good['update_at'] = date('Y-m-d H:i:s');
                    $period_list[] = $good;
                    $start_time += $this->config['period_time'];
                }
                if (!DrawResultJisussc::query()->insert($period_list)) {
                    Log::info('极速分分彩生成明日期数失败');
                    return false;
                }
                return true;
                break;
            case 7:
                $last_period_number = date('Ymd',time()+86400).'000';
                while ($start_time < $end_time) {
                    $last_period_number += 1;
                    $good = [];
                    $good['game_id'] = $this->config['game_id'];
                    $good['type'] = $this->config['game_type'];
                    $good['periods'] = $last_period_number;
                    $good['start_time'] = date('Y-m-d H:i:s', $start_time);
                    $good['bet_end_time'] = date('Y-m-d H:i:s', $start_time +  $this->config['period_bet_time']);//300秒
                    $good['kaijiang_time'] = date('Y-m-d H:i:s', $start_time + $this->config['period_draw_time']);//360秒
                    $good['update_at'] = date('Y-m-d H:i:s');
                    $period_list[] = $good;
                    $start_time += $this->config['period_time'];
                }
                if (!DrawResultJisupk::query()->insert($period_list)) {
                    Log::info('极速pk10生成明日期数失败');
                    return false;
                }
                return true;
                break;
            case 9:
                $last_period_number = date('Ymd',time()+86400).'000';
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
                if (!DrawResultfucai::query()->insert($period_list)) {
                    Log::info('福彩生成期数失败');
                    return false;
                }
                return true;
                break;
            default:
                break;
        }
        return true;
    }

}
