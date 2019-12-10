<?php

namespace App\Jobs;

use App\Classes\Odd;
use App\Classes\Scc;
use App\Classes\UpdateAccount;
use App\Models\Account;
use App\Models\AppSpecial;
use App\Models\AppSystem;
use App\Models\BonusRecord;
use App\Models\Fandianset;
use App\Models\JournalAccount;
use App\Models\Odds;
use App\Models\Order;
use App\Models\System;
use App\Models\Userinfo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OpenPrize implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $lotteryNo;
    public function __construct($data,$lotteryNo)
    {
        $this->data = $data;
        $this->lotteryNo = $lotteryNo;
        $this->queue = 'openPrize';
    }

    public function handle()
    {
        $data = $this->data;
        Log::info('进入OpenPrize订单id为:'.$data['order_id']);
        $lotteryNo = $this->lotteryNo;
        $Scc = new Scc();
        $fun_kj = 'kj_'.substr($data['serial_num'],2);
        $winNum =  $Scc->$fun_kj($lotteryNo,$data['position']-1,$data['bet_value']);
        $Odds = Odds::query()->where('serial_num',$data['serial_num']);
        $odds = $Odds->value('odds'.$data['room_type']);
        if($winNum == '0'){
            $winBonus = 0;
        }
        elseif ($winNum == 2){
            $winBonus = 0;
        }
        else{
            /*等级等于投注金额 * 赔率*/
            /*这里要判断一下开奖结果是不是13 或者 14*/
            if($data['gameId'] == 4||$data['gameId'] == 5){
                $kj = explode(',',$this->lotteryNo);
                sort($kj);
                $kjNum = $kj[0]+$kj[1]+$kj[2];
                if($kjNum==13||$kjNum==14){
                    $newSerial = substr($data['serial_num'],2);
                    if($newSerial=='030101'||$newSerial=='030102'||$newSerial=='030103'||$newSerial=='030104'
                        ||$newSerial=='030105'||$newSerial=='030106'||$newSerial=='030107'||$newSerial=='030108'||$newSerial=='030109'||$newSerial=='030110'){
                        /*获取是否开启*/
                        $AppSpecialValue1 = AppSpecial::query()->where('room_type',$data['room_type'])->first();
                        if($AppSpecialValue1['value1'] == 1){
                                $odds = $AppSpecialValue1['value3'];
                                if($odds==0)  $winNum = 0;
                        }
                    }
                }
                if($kj[0]==$kj[2]||$kj[0]==$kj[1]||$kj[1]==$kj[2]||$kj[0]+2==$kj[2]){
                    $newSerial = substr($data['serial_num'],2);
                    if($newSerial=='030101'||$newSerial=='030102'||$newSerial=='030103'||$newSerial=='030104'
                        ||$newSerial=='030105'||$newSerial=='030106'||$newSerial=='030107'||$newSerial=='030108'||$newSerial=='030109'||$newSerial=='030110'){
                        /*获取是否开启*/
                        $AppSpecialValue1 = AppSpecial::query()->where('room_type',$data['room_type'])->first();
                        if($AppSpecialValue1['value2'] == 1){
                            $odds = $AppSpecialValue1['value3'];
                            if($odds==0)  $winNum = 0;
                        }
                    }
                }
            }
            $winBonus = round($odds * $data['bet_money'],3);
        }
        $UpdateStatus = new UpdateAccount();
        $orderResult = 0;
        if($winBonus>0) $orderResult = 1;
        if($winBonus>1000000){$winBonus = 1000000;}
        try
        {
            DB::beginTransaction();
            /*修改order表 开奖状态 中奖金额 中奖注数  开奖时间*/
            $result = Order::query()->where('id',$data['id'])->update([
                'status' => 1,
                'result'=>$orderResult,
                'lotteryNo' => $lotteryNo,
                'zjCount' => $winNum,
                'bonus'=> $winBonus,
                'update_time' => now()->toDateTimeString(),
                'lottery_time' => now()->toDateTimeString()
            ]);
            if(!$result){
                throw new \Exception('更新订单表失败');
            }
            if($winNum > 0){
                /*中奖的话 给用户打钱*/
                $old_money = Account::query()->where('user_id',$data['user_id'])->value('remaining_money');
                $result = Account::query()->where('user_id',$data['user_id'])->increment('remaining_money',$winBonus);
                if(!$result){
                    throw new \Exception('更新余额表失败,添加这么多钱:'.$winBonus.'注数:'.$winNum);
                }
                /*获取用户原金额*/
                $result = $UpdateStatus->updateStatus($data['user_id'],'中奖',$winBonus,$old_money,$data['order_id'],'',$data['bet_period']);
                if(!$result){
                    throw new \Exception('更新账变表失败');
                }
            }
            /*
             * 需要判断是否停止追号
             * 所有剩下的订单 都变成撤单状态
             * 计算需退款的总金额 记录账变和今日盈利记录
            */
            if($data['zhuiHaoMode']!=0 && $winNum>0){
                $oldUserMoney = Account::query()->where('user_id',$data['user_id'])->value('remaining_money');
                $sumBetMoney = Order::query()
                    ->where('status',0)
                    ->where('user_id',$data['user_id'])
                    ->where('zhuiHaoMode',$data['zhuiHaoMode'])
                    ->sum('bet_money');
                Order::query()
                    ->where('status',0)
                    ->where('user_id',$data['user_id'])
                    ->where('zhuiHaoMode',$data['zhuiHaoMode'])
                    ->update(['delete_time'=>Carbon::now()->toDateTimeString()]);
                Account::query()->where('user_id',$data['user_id'])->increment('remaining_money',$sumBetMoney);
                $UpdateStatus->updateStatus($data['user_id'],'撤单',$sumBetMoney,$oldUserMoney);
            }
            Log::info('开奖处理:开奖成功'.$data['order_id']);
            DB::commit();
            return true;
        }
        catch(\Exception $e)
        {
            Log::info('开奖处理:开奖失败'.$data['order_id'].$e->getMessage());
            DB::rollBack();
            return false;//错误
        }
    }

}
