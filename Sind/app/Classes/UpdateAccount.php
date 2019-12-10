<?php
/**
 * Created by PhpStorm.
 * User: 皮皮奇
 * Date: 2018/4/29
 * Time: 13:33
 */

namespace App\Classes;


use App\Models\Account;
use App\Models\Accountstatus;
use App\Models\Journalaccount;
use App\Models\UserDailySettle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateAccount
{
    /*
     * 1.用户ID  2.状态名 3.账变金额 4.
     *
     *
     * */
    function updateStatus($userId,$statusName,$amount,$bet_money,$tranNum='',$remarks='',$periods=''){
        $UserAccount = Account::query()->where('user_id',$userId)->first();
        $status_id = Accountstatus::query()->where('status_name',$statusName)->value('id');
        if($statusName == '提款'||$statusName == '充值'){
            $hand = 1;
        }else{
            $hand = 2;
        }
        DB::beginTransaction();
        try{
            //帐变表
            $result = JournalAccount::query()->insert([
                'user_id'=>$userId,
                'tran_num'=>$tranNum,
                'old_money'=>$bet_money,/*原金额*/
                'change_status'=>$status_id,
                'change_money'=>$amount,
                'bet_money'=>$UserAccount['remaining_money'],
                'remarks'=>$remarks,
                'create_time'=>Carbon::now()->toDateTimeString(),
                'is_handle'=>$hand,
                'periods'=>$periods
            ]);
            if (!$result){
                throw new \Exception('生成帐变记录失败');
            }
            /*判断该用户是否有今日盈亏记录*/
            $userDailySettle = UserDailySettle::query()->where('user_id',$userId)->where('create_time',Carbon::now()->toDateString())->first();
            if(!$userDailySettle) {
                    $result = UserDailySettle::query()->insert([
                    'user_id' =>$userId,
                    'create_time'=>Carbon::now()->toDateString(),
                    'update_time'=>Carbon::now()->toDateString()
                ]);
                if(!$result){
                    return api_response(false,'','该用户无法插入盈利记录');//错误
                }
            }
            //用户每日盈亏结算统计
            $userDailySettle = UserDailySettle::query()->where('user_id',$userId)->where('create_time',Carbon::now()->toDateString());
            switch ($statusName){
                case '充值':
                    $userDailySettle->increment('recharge',$amount);
                    break;
                case '提款':
                    $userDailySettle->decrement('withdrawals',$amount);
                    break;
                case '投注':
                    $userDailySettle->decrement('betting',$amount,['betNum'=>DB::raw('betNum + 1')]);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '中奖':
                    $userDailySettle->increment('winning',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '返点':
                    $userDailySettle->increment('rebate',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '活动':
                    $userDailySettle->increment('activity',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '分红':
                    $userDailySettle->increment('bonus',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '撤单':
                //    if(!$betNum) $betNum = 1;
                    $userDailySettle->decrement('betting',$amount);//['betNum'=>DB::raw('betNum - '.$betNum)]);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '工资':
                    $userDailySettle->increment('wages',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '其他':
                    $userDailySettle->increment('winning',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
            }
            if (!$userDailySettle){
                throw new \Exception('用户每日盈亏结算统计失败');
            }
            DB::commit();

        }
        catch (\Exception $e){
            DB::rollBack();
            return api_response(false,'',$e->getMessage());//错误
        }
        return  api_response(true,'','用户每日盈亏结算统计与帐变表成功！');
    }
}