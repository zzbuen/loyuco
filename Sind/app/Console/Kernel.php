<?php

namespace App\Console;


use App\Jobs\GamePeriod;
use App\Jobs\GetDrawResult;
use App\Jobs\Jnd28Periods;
use App\Jobs\UserDailyJob;
use App\Models\BuyUser;
use App\Models\DrawResultBeijingpk;
use App\Models\DrawResultChongqing;
use App\Models\DrawResultJisupk;
use App\Models\DrawResultJisussc;
use App\Models\DrawResultJnd;
use App\Models\DrawResultPcdd;
use App\Models\DrawResultTenxun;
use App\Models\DrawResultXyft;
use App\Models\GameConfig;
use App\Models\DrawResultfucai;

use App\Models\Goods;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        /*开奖调度任务*/
        $schedule->call(function () {
//            $list1 = DrawResultChongqing::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $list2 = DrawResultBeijingpk::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $list3 = DrawResultXyft::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
            $list5 = DrawResultJnd::query()->where('res_status',1)
                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $list6 = DrawResultTenxun::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $list7 = DrawResultJisussc::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $list4 = DrawResultJisupk::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $list9 = DrawResultfucai::query()->where('res_status',1)
//                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'));
//            $draw_list = DrawResultPcdd::query()
            $draw_list = DrawResultPcdd::query()
//                ->union($list1)
//                ->union($list2)
//                ->union($list3)
//                ->union($list4)
                ->union($list5)
//                ->union($list6)
//                ->union($list7)
//                ->union($list9)
                ->where('res_status',1)
                ->where('kaijiang_time', '<=', date('Y-m-d H:i:s'))->get()->toArray();

            foreach ($draw_list as $item) {
                if (Cache::store('redisCache')->get('GetDrawResult'.$item['game_id'].$item['periods'])) {
                    continue;
                }
                Cache::store('redisCache')->add('GetDrawResult'.$item['game_id'].$item['periods'], 1, 4);
                dispatch(new GetDrawResult($item['periods'],$item['game_id'],$item['kaijiang_time']))->onQueue('game'.$item['game_id']);;
            }
            return true;
        })->cron('* * * * *');

        /*生成游戏的明日期数*/
        $schedule->call(function(){
            /*获取用户表所有用户信息*/
            $config_list = GameConfig::query()->whereIn('id',[4,5])->get()->toArray();
            foreach ($config_list as $configList) {
                if (Cache::store('redisCache')->get('GameConfigTomorrows'.$configList['id'])) {
                    continue;
                }
                Cache::store('redisCache')->add('GameConfigTomorrows'.$configList['id'], 1, 5);
                dispatch(new GamePeriod($configList));
            }
            return true;
        })->dailyAt('16:06')->when(function () {
            if (Cache::store('redisCache')->get('GameConfig'.date('Y-m-d'))) return false;
            Cache::store('redisCache')->add('GameConfig'.date('Y-m-d'), 1, 5);
            return true;
        });

//        /*生成加拿大28期数*/
//        $schedule->call(function(){
//            $list = DrawResultJnd::query()->where('kaijiang_time','=',Carbon::now()->toDateString().' 20:00:00')->first();
//            if($list['res_status']==1){
//                return true;
//            }
//            elseif($list['res_status']==2&&$list['result']!=null){
//                if (Cache::store('redisCache')->get('Jnd28')){
//                    return true;
//                }
//                Cache::store('redisCache')->add('Jnd28', 1, 1);
//                Log::info('Jnd进入准备生成期数了,开奖时间:'.$list['update_at'].'/当前时间:'.Carbon::now()->toDateTimeString());
//                dispatch(new Jnd28Periods($list['update_at']));
//            }
//            return true;
//        })->cron('* * * * *')->when(function () {
//            //return false;
//            if(Cache::store('redisCache')->get('Jnd28s')) return false;
//            if(Carbon::now()->hour<20)  return false;
//            return true;
//        });

        /*生成每日盈利表 脚本進入*/
        $schedule->call(function(){
            if (Cache::store('redisCache')->get('UsertDailySette')) return true;
            Cache::store('redisCache')->add('UsertDailySette', 1, 5);
            /*获取用户表所有用户信息*/
            $Userlist = BuyUser::query()->get()->toArray();
            foreach ($Userlist as $users) {
                dispatch(new UserDailyJob($users));
            }
            return true;
        })->dailyAt('23:55');
        /*生成进入所有期数*/
        $schedule->call(function(){
            $Userli=DrawResultJnd::query();
            $Userlist = $Userli
                ->whereDate('start_time',Carbon::tomorrow()->toDateString())
                ->get()
                ->toArray();
            Log::info("123456456456465897");
//            Log::info($Userlist);
            if($Userlist[0]['result']){
                return true;

            }
            foreach ($Userlist as $k=>$users) {
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(0, 9);
                }
                $draw_number = implode(',', $numbers);
                $code = $draw_number;
                $Userlist2=DrawResultJnd::query();
                if(empty($Userlist[$k]['result'])){
                    $result = $Userlist2->where('periods',$Userlist[$k]['periods'])->update([
                        'res_status'=>1,
                        'result'=> $code
//                    'update_at' => now()->toDateTimeString()
                    ]);
                }

            }
            return true;
        })->dailyAt('20:30');
        /*生成进入所有期数*/
        $schedule->call(function(){
            $Userli=DrawResultPcdd::query();
            $Userlist = $Userli
                ->whereDate('start_time',Carbon::tomorrow()->toDateString())
                ->get()
                ->toArray();
            Log::info("123456456456465897");
//            Log::info($Userlist);
            if($Userlist[0]['result']){
                return true;

            }
            foreach ($Userlist as $k=>$users) {
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(0, 9);
                }
                $draw_number = implode(',', $numbers);
                $code = $draw_number;
                $Userlist2=DrawResultPcdd::query();
                if(empty($Userlist[$k]['result'])){
                    $result = $Userlist2->where('periods',$Userlist[$k]['periods'])->update([
                        'res_status'=>1,
                        'result'=> $code
//                    'update_at' => now()->toDateTimeString()
                    ]);
                }

            }
            return true;
        })->dailyAt('20:33');
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
