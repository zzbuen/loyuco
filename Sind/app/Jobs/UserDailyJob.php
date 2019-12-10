<?php

namespace App\Jobs;

use App\Models\UserDailySettle;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Filesystem\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UserDailyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $list;
    public function __construct($list)//
    {
        $this->list = $list;
        $this->queue = 'userDailyJob';
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $list = $this->list;
        try{
            Log::info('开始生成'.$list['user_id'].'的今日盈利表');
            DB::beginTransaction();
            $result = UserDailySettle::query()->where('user_id',$list['user_id'])->whereDate('create_time',Carbon::tomorrow()->toDateString())->first();
            if($result){
                 Log::info('出去了');
                return true;
            } /*说明已有今天的这条记录*/
            $result  = UserDailySettle::query()->insert([
                'user_id' =>$list['user_id'],
                'create_time'=>Carbon::tomorrow()->toDateString(),
                'update_time'=>Carbon::tomorrow()->toDateString()
            ]);
            if(!$result){
                throw new \Exception('添加每日盈利记录失败');
            }
            DB::commit();
            Log::info('成功了');
            return true;
        }catch (\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

}
