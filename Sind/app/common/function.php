<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/7/4
 * Time: 10:48
 */
use Carbon\Carbon;
if (!function_exists("api_response")) {
    /*
     * api接口响应信息
     */
    function api_response($status = true, $json_data = '', $message = '', $rspCode = '200')
    {
        return json_encode(array('success'=>$status, 'data'=>$json_data, 'message'=>$message, 'code'=>$rspCode));
    }
}
if (!function_exists("grantActivity")) {
    function grantActivity($user_id,$money){
        $data['user_id'] = $user_id;
        $data["money"] = $money;
        $active = \App\Models\Active::query()
            ->where('user_id',$data['user_id'])
            ->where('status',1)
            ->where('start_time','<=',time())
            ->where('end_time','>',time())
            ->first();
        $total = floatval($active['total_chongzhi'])+floatval($data["money"]);
        $remaining_money = \App\Models\Account::query()->where('user_id',$data['user_id'])->value('remaining_money');
        if($active){
            try{
                \Illuminate\Support\Facades\DB::beginTransaction();
                $result = \App\Models\Active::query()->where('id',$active['id'])->increment('total_chongzhi',$data['money']);
                if(!$result){
                    throw new \Exception('累计充值添加失败');
                }

                if($active['chongzhi_money']<= $total){
                    //判断上级冻结金额是否足够
                    $frozenMoney = \App\Models\Account::query()->where('user_id',$active['parent_user_id'])->value('unliquidated_money');

                    if($frozenMoney >= $active['jiangli_money']){

                        $result =  \App\Models\Account::query()->where('user_id',$active['parent_user_id'])->decrement('unliquidated_money',$active['jiangli_money']);
                        if(!$result){
                            throw new \Exception('扣除上级冻结金额失败');
                        }
                        $old_money = \App\Models\Account::query()->where('user_id',$active['user_id'])->value('remaining_money');

                        $result = \App\Models\Account::query()->where('user_id',$active['user_id'])->increment('remaining_money',$active['jiangli_money']);
                        if(!$result){
                            throw new \Exception('发放活动奖金失败');
                        }
                        $result = \App\Models\Active::query()->where('id',$active['id'])->update(['status'=>3]);
                        if(!$result){
                            throw new \Exception('活动状态改变失败');
                        }
                        $result = \App\Models\JournalAccount::query()->insert([
                            'user_id'=>$active['user_id'],
                            'tran_num'=>'',
                            'old_money'=>$old_money,/*原金额*/
                            'change_status'=>6,
                            'change_money'=>$active['jiangli_money'],
                            'bet_money'=>$old_money + $active['jiangli_money'],
                            'remarks'=>'获得充值活动金额'.$active['jiangli_money'].'元',
                            'create_time'=>Carbon::now()->toDateTimeString()
                        ]);
                        if (!$result){
                            throw new \Exception('生成帐变记录失败');
                        }
                        $result = \App\Models\UserDailySettle::query()
                            ->where('user_id',$active['user_id'])
                            ->where('create_time',now()->toDateString())
                            ->increment('activity',$active['jiangli_money'],['total'=>\Illuminate\Support\Facades\DB::raw('total + '.$active['jiangli_money'])]);
                        if (!$result){
                            throw new \Exception('修改今日盈利记录失败');
                        }

                        updateStatus($data['user_id'],'活动',$active['jiangli_money'],$remaining_money);

                        $agentUsername = \App\Models\User::query()->where('user_id',$active['parent_user_id'])->value('username');
                        $username = \App\Models\User::query()->where('user_id',$active['user_id'])->value('username');
                        adminSendUser('系统消息','您的下级会员:'.$username.'的充值已达到条件,奖金已发放至下级账户',$agentUsername);
                        adminSendUser('系统消息','您的充值活动已达到条件,奖金已发至您的账户',$username);
                    }else{
                        throw new \Exception('上级冻结金额不足');
                    }
                }
                \Illuminate\Support\Facades\DB::commit();
                return ['success'=>true];
            } catch (\Exception $e){
                \Illuminate\Support\Facades\DB::rollBack();
                return ['success'=>false,'msg'=>$e->getMessage()];//错误
            }
        }
        else{
            return ['success'=>true];
        }
    }
}
if (!function_exists("adminSendUser")) {
    function adminSendUser($title,$content,$userId){
        if(!$title||!$content||!$userId) return 0;
        $result = \App\Models\MessUser::query()->insert([
            'user_id'=>$userId,
            'send_user_id'=>'admin',
            'title'=>$title,
            'content'=>$content,
            'type'=>2,
            'create_time'=>Carbon::now()->toDateTimeString(),
            'status'=>0,
            'send_type'=>2
        ]);
        if(!$result) return 0;
        return 1;
    }
}

if(!function_exists("updateStatus")){
    /*
   * 1.用户ID  2.状态名 3.账变金额 4.$tranNum 投注量  remarks 备注 bet_money原金额
   *
   *
   * */
    function updateStatus($userId,$statusName,$amount,$bet_money,$tranNum='',$remarks='',$periods=''){
        $UserAccount = \App\Models\Account::query()->where('user_id',$userId)->first();
        $status_id = \App\Models\Accountstatus::query()->where('status_name',$statusName)->value('id');
        \Illuminate\Support\Facades\DB::beginTransaction();
        if($statusName == '提款'||$statusName == '充值'){
            $hand = 1;
        }else{
            $hand = 2;
        }
        try{
            //帐变表
            $result = \App\Models\Journalaccount::query()->insert([
                'user_id'=>$userId,
                'tran_num'=>$tranNum,
                'old_money'=>$bet_money,/*old money*/
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
            //用户每日盈亏结算统计
            $userDailySettle = \App\Models\UserDailySettle::query()->where('user_id',$userId)->where('create_time',Carbon::now()->toDateString());
            switch ($statusName){
                case '充值':
                    $userDailySettle->increment('recharge',$amount);
                    break;
                case '提款':
                    $userDailySettle->decrement('withdrawals',$amount);
                    break;
                /*    case '提款失败':
                        $userDailySettle->increment('withdrawals',$amount);
                        break;*/
                case '投注':

                    $userDailySettle->decrement('betting',$amount,['betNum'=>\Illuminate\Support\Facades\DB::raw('betNum + 1')]);
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
                case '红利':
                    $userDailySettle->increment('bonus',$amount);
                    $userDailySettle->increment('total',$amount);
                    break;
                case '撤单':
                    $userDailySettle->decrement('betting',$amount,['betNum'=>\Illuminate\Support\Facades\DB::raw('betNum - 1')]);
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
            \Illuminate\Support\Facades\DB::commit();

        }
        catch (\Exception $e){
            \Illuminate\Support\Facades\DB::rollBack();
            return api_response(false,'',$e->getMessage());//错误
        }


        return  api_response(true,'','用户每日盈亏结算统计与帐变表成功！');
    }
    function getTheGameTable($gameId){
        switch ($gameId){
            case 1:
                $allGameList = \App\Models\DrawResultChongqing::query();
                break;
            case 2:
                $allGameList = \App\Models\DrawResultBeijingpk::query();
                break;
            case 3:
                $allGameList = \App\Models\DrawResultXyft::query();
                break;
            case 4:
                $allGameList = \App\Models\DrawResultPcdd::query();
                break;
            case 5:
                $allGameList = \App\Models\DrawResultJnd::query();
                break;
            case 6:
                $allGameList = \App\Models\DrawResultTenxun::query();
                break;
            case 7:
                $allGameList = \App\Models\DrawResultJisupk::query();
                break;
            case 8:
                $allGameList = \App\Models\DrawResultJisussc::query();
                break;
            case 9:
                $allGameList = \App\Models\DrawResultfucai::query();
                break;
        }
        return $allGameList;
    }
}