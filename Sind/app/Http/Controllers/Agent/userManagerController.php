<?php

namespace App\Http\Controllers\Agent;
use App\Jobs\OpenPrize;
use App\Models\Account;
use App\Models\Accountstatus;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\Journalaccount;
use App\Models\Payoff;
use App\Models\System;
use App\Models\UserBank;
use App\Models\Address;
use App\Models\Order;
use App\Models\Game;
use App\Models\UserDailySettle;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\TradeRecord;
use App\Models\Profit;
use App\Models\OrderBackup;
use App\Models\Relation;
use App\Models\Admin;
use App\Models\Withdraw;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class userManagerController  extends Controller
{
    public function cs(Request $request){
        $order_list = Order::query()->where('order_id',$request->id)->first();
        new OpenPrize($order_list,$request->lo);
        return 1;
    }
    public function getUser(Request $request)
    {
        $agent_id = auth('agent')->user()->user_id;
        $agent_user = auth('agent')->user()->username;
        $dl_level = auth('agent')->user()->dl_level;
        $select = [
            "user.user_id       as user_id",
            "user.username      as zhanghao",
            "user.role_id       as role_id",
            "user.user_state    as status",
            "user.is_fictitious    as is_fictitious",
            "user.parent_user_id        as parent_user_id",
            "account.remaining_money    as remaining_money",
            "userinfo.create_time  as register_time",
            "userinfo.last_login_time    as last_login_time",
            "userinfo.last_login_ip    as last_login_ip",
            "user.dl_level as dl_level"
        ];
        if($dl_level == 1){
            $teamList = BuyUser::query()->where('parent_user_id',$agent_user)->where('dl_level',2)->pluck('user_id');
        }else {
            $teamList = $this->getTeamUser($agent_id);
        }

        $data =  User::query()
            ->leftJoin("account","user.user_id","=","account.user_id")
            ->leftJoin("userinfo","userinfo.user_id","=","user.user_id")
            ->whereIn('user.user_id',$teamList);
        $leader_id = $request->leader_id;
        if($leader_id==1){
            $data = $data->where('user_state',1);
        }elseif ($leader_id==2){
            $data = $data->where('user_state',0);
        }
        /*用户名*/
        $user_name = $request->user_name;
        if($user_name){
            $data = $data->where("user.username",$user_name)->orWhere('user.user_id',$user_name);
        }


        /*金钱*/
        $order = $request->order;
        $name  = $request->name;
        if($order){
            $data =  $data ->orderBy($name,$order);
        }


        $data =  $data->select($select)
            ->groupBy("user.username")
            ->orderBy("user.id","desc")
            ->paginate(10);

        $all_a  =   BuyUser::query()->where('parent_user_id',$agent_user)->pluck('username');

        return view('agent.userManager.index',[
            "data"      => $data,
            "user_id"   => $user_name,
            "order"         => $order,
            "name"          => $name,
            "all_a"         => $all_a,
            "dl_level"      => $dl_level
        ]);
    }

    /*
      * 递归获取用户团队列表方法 不包括自己
      */
    function getTeamUser($pId){
        $teamList = '';
        $result = UserInfo::query()->where('parent_user_id',$pId)->get()->toArray();
        if ($result)
        {
            foreach ($result as $key=>$val)
            {
                $teamList .= $val['user_id'].',';
                $teamList .= $this->getTeamUser2($val['user_id']);
            }
        }
        $teamList = array_filter(explode(',',$teamList));
        return $teamList;
    }
    function getTeamUser2($pId)
    {
        $teamList = '';
        $result = UserInfo::query()->where('parent_user_id',$pId)->get()->toArray();
        if ($result)
        {
            foreach ($result as $key=>$val)
            {
                $teamList .= $val['user_id'].',';
                $teamList .= $this->getTeamUser2($val['user_id']);
            }
            return $teamList;
        }
    }

    public function getUser_detail(Request $request)
    {
        $user_id = $request->user_id;
        $user_data = User::query()
            ->leftJoin("account","user.user_id","=","account.user_id")
            ->where("user.user_id",$user_id)
            ->first(["username","remaining_money"])
            ->toArray();
        $select = [
            DB::raw("sum(recharge) as recharge"),
            DB::raw("sum(withdrawals) as withdrawals"),
            DB::raw("sum(betting) as betting"),
            DB::raw("sum(rebate) as rebate"),
            DB::raw("sum(wages) as wages"),
            DB::raw("sum(bonus) as bonus"),
            DB::raw("sum(activity) as activity"),
            DB::raw("sum(winning) as winning"),
            DB::raw("sum(total) as total"),
        ];
        $roleId = BuyUser::query()->where('user_id',$user_id)->value('role_id');
        if($roleId==1){
            $teamNum = 1;
            $teamMoney = Account::query()->where('user_id',$user_id)->value('remaining_money');
            $data = UserDailySettle::query()
                ->select($select)
                ->where("user_id",$user_id)
                ->where("is_status",1)
                ->first()
                ->toArray();
        }else{
            $teamList = $this->getTeamUser($user_id);
            array_push($teamList,$user_id);
            $teamNum = count($teamList);
            $teamMoney =  Account::query()->whereIn('user_id',$teamList)->value('remaining_money');
            $data = UserDailySettle::query()
                ->select($select)
                ->whereIn("user_id",$teamList)
                ->where("is_status",1)
                ->first()
                ->toArray();
        }




        return view('agent.userManager.getUser_detail', [
            "user_data"     => $user_data,
            "data"          => $data,
            'teamNum'       => $teamNum,
            'teamMoney'     => $teamMoney,
            'roleId'        =>$roleId
        ]);
    }
    public function becomeAgent(Request $request)
    {
        return view('agent.userManager.becomeAgent');
    }

    public function becomeAgent_ajax(Request $request)
    {
        $share = $request->input('share');
        $user_id = $request->input('user_id');
        $leader_agent_id = auth('agent')->user()->user_id;
        $self_list = AgentInfo::query()->where('user_id' ,$leader_agent_id)->get()->toArray();
        $user_info = UserInfo::query()->where('user_id',$user_id)->get()->toArray();
        $a_flag = Agent::query()->where('user_id',$user_id)->get()->toArray();
        if($a_flag){
            $retrun_arr = ['flag' => false, 'msg' => '指定失败,该用户已经是代理了'];
            return $retrun_arr;
        }
        if($user_info[0]['parent_user_id']!=$agent_id = auth('agent')->user()->user_id) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败'];
            return $retrun_arr;
        }
        if (!is_numeric($share)) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，请输入整数或小数字'];
            return $retrun_arr;
        }
        if ($share < 0) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，分润比不能低于0'];
            return $retrun_arr;
        }
        if ($share > 100) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，分润比不能大于100'];
            return $retrun_arr;
        }
        if ($share > $self_list[0]['share_percent']) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，二级代理分润不能大于一级代理分润'];
            return $retrun_arr;
        }

        $rand_number = $this->getRand();
        $user_lider = BuyUser::query()->with('info')->where('user_id' ,$user_id)->get()->toArray();

        $leader_id = $leader_agent_id ;
        $password= $user_lider[0]['password'];
        try {
            DB::begintransaction();
            $agent = new Agent();
            $agent_info = new AgentInfo();
            $relation = new Relation();
            $agent->username = $user_lider[0]['username'];
            $agent->password = $password;
            $agent->user_id = $user_id;
            $agent->leader_id = $leader_id;

            $agent_info->user_id = $user_id;
            $agent_info->share_percent = $share;
            $agent_info->valid_profit = 0;
            $agent_info->expend_profit = 0;
            $agent_info->totle_profit = 0;

            $relation->user_id = $user_id;
            $relation->belong_to = 't_caimi_user';
            $relation->invitation_num = $rand_number;

            if(!$agent->save()) {
                throw new \Exception("用户信息存储失败，请重试");
            }
            if(!$agent_info->save()) {
                throw new \Exception("用户信息存储失败，请重试");
            }
            if(!$relation->save()) {
                throw new \Exception("用户信息存储失败，请重试");
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['flag' => false, 'msg' => $e->getMessage()];
        }
        $retrun_arr = ['flag' => true, 'msg' => '指定成功'];
        return $retrun_arr;
    }

    public function getRand()
    {
        $rand_number = null;
        $invitation_code_prefix = System::query()->where('key', 'invitation_code_prefix')->value('value');
        $rand_number = $invitation_code_prefix;
        for ($j = 0; $j < 4; $j++) {
            $rand_number = $rand_number . rand(1, 9);
        }
        $invitation_arr = Relation::query()->get(['invitation_num'])->toArray();
        $flag = false;
        for ($i = 0; $i < count($invitation_arr); $i++) {
            if ($invitation_arr[$i]['invitation_num'] == $rand_number) {
                $flag = true;
                break;
            }
        }
        if($flag){
            $rand_number = $this->getRand();
        }
        return $rand_number;
    }

    public function withdrawCenter(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_list = Withdraw::query()->with('userbank.bankname')
                        ->where('user_id',$agent_id)->orderBy('id','desc');
        if($request->input('status')){
            $withdraw_list->where('status',$request->input('status')-1);
        }
        $withdraw_list = $withdraw_list->paginate(10);
        return view('agent.userManager.withdrawCenter',['withdraw_list' => $withdraw_list]);
    }

    public function applyWithdraw(Request $request) {
        $withdraw_info = System::query()->where('key','withdraw_time')->get()->toArray();
        $agent_id = auth('agent')->user()->user_id;
        $agent_list =  AgentInfo::query()->where('user_id' ,$agent_id)->get()->toArray();
        $user_bank = UserBank::query()->with('bankname')
                    ->where('user_id',$agent_id)->get()->toArray();
        return view('agent.userManager.applyWithdraw',['agent_list' => $agent_list,
                                                              'user_bank'=>$user_bank,
                                                              'withdraw_info'=>$withdraw_info[0]]);
    }

    public function applyWithdraw_ajax(Request $request){
        $withdraw_info = System::query()->where('key','withdraw_time')->get()->toArray();
        $week = date("w");
        $nowtime = strtotime(Carbon::now()->toDateTimeString());

        $timeBegin = strtotime(Carbon::now()->toDateString().' '.unserialize($withdraw_info[0]['value'])[1]);
        $timeEnd = strtotime(Carbon::now()->toDateString().' '.unserialize($withdraw_info[0]['value'])[2]);
        if($nowtime<=$timeBegin||$timeEnd<=$nowtime) {
            $retrun_arr = ['flag' => false, 'msg' => '提现失败,未在规定的时间点内提现'];
            return $retrun_arr;
        }
        if($week!=unserialize($withdraw_info[0]['value'])[0]){
            $retrun_arr = ['flag' => false, 'msg' => '提现失败,未在规定的时间点内提现'];
            return $retrun_arr;
        }
        $agent_id = auth('agent')->user()->user_id;
        $bank_id = $request->input('bank_id');
        if(!$bank_id) {
           $retrun_arr = ['flag' => false, 'msg' => '提现失败，请先选择银行'];
            return $retrun_arr;
        }
        $money = $request->input('money');
        $agent_list = AgentInfo::query()->where('user_id' ,$agent_id)->get()->toArray();
        $leader_sql = Agent::query()->where('user_id',$agent_id)->get()->toArray();
        $leader_id = $leader_sql[0]['leader_id'];
        $user_list = UserInfo::query()->where('user_id',$agent_id)->get()->toArray();
        $bank_info = UserBank::query()->where('user_id', auth('agent')->user()->user_id)->get()->keyBy('id')->toArray();
        if (!is_numeric($money)) {
            $retrun_arr = ['flag' => false, 'msg' => '提现失败，请输入整数或小数字'];
            return $retrun_arr;
        }
        if ($money < 0) {
            $retrun_arr = ['flag' => false, 'msg' => '提现失败，提现金额不能低于0'];
            return $retrun_arr;
        }
        if ($money > $agent_list['0']['valid_profit']) {
            $retrun_arr = ['flag' => false, 'msg' => '提现失败，您最大的提现金额是'.$agent_list['0']['valid_profit']];
            return $retrun_arr;
        }
        if(!$bank_info[$request->input('bank_id')]['bank_branch']) {
            $retrun_arr = ['flag' => false, 'msg' => '提现失败，您还没有填写汇款银行，请完善相关资料'];
            return $retrun_arr;
        }
        if(!$bank_info[$request->input('bank_id')]['account']) {
            $retrun_arr = ['flag' => false, 'msg' => '提现失败，您还没有填写汇款银行账号，请完善相关资料'];
            return $retrun_arr;
        }

        try {
            DB::begintransaction();
            $agent_info = AgentInfo::query()->where('user_id',$agent_id);
            $valid_profit = $agent_list['0']['valid_profit']-$money;
            $expend_profit = $agent_list['0']['expend_profit']+$money;
            $up_arr = ['valid_profit' => $valid_profit,'expend_profit'=>$expend_profit];
            $withdraw_sn = number_format($this->generateOrderSn(),0,'','');
            $withdraw = new Withdraw();
            $withdraw->withdraw_sn = $withdraw_sn;
            $withdraw->user_id = $agent_id;
            $withdraw->status = 0;
            $withdraw->leader_id = $leader_id;
            $withdraw->amount = $money;
            $withdraw->user_bank_id = $bank_id;

            if(!$agent_info->update($up_arr)) {
                throw new \Exception("用户信息更新失败，请重试");
            }
            if(!$withdraw->save()) {
                throw new \Exception("用户信息存储失败，请重试");
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['flag' => false, 'msg' => $e->getMessage()];
        }
        return ['flag' => true, 'msg' => '提现申请成功'];
    }
    public function generateOrderSn($machine_id = 1)
    {
        /*
        * Time - 41 bits
        */
        $time = floor(microtime(true) * 1000);

        /*
        * Substract custom epoch from current time
        */
        $time -= 1418801787000;

        /*
        * Create a base and add time to it
        */
        $base = decbin(pow(2,40) - 1 + $time);

        /*
        * Configured machine id - 10 bits - up to 512 machines
        */
        $machineid = decbin(pow(2,9) - 1 + $machine_id);
        $random = mt_rand(1, pow(2,11)-1);
        $random = decbin(pow(2,11)-1 + $random);
        $base = $base.$machineid.$random;
        return bindec($base);
    }
    public function user_result_detail(Request $request) {
        $user_id = $request->input('user_id');

        $order_list = Order::query()->with('info')
            ->leftJoin('qianduan','qianduan.serial_num','=','order.serial_num')
            ->where('order.user_id',$user_id)
            ->groupBy('order.id');

        if($request->input('bet_period')) {
            $order_list = $order_list->where('bet_period',$request->input('bet_period'));
        }
        if($request->input('order_sn')) {
            $order_list = $order_list->where('order_sn' ,$request->input('order_sn'));
        }

        if($request->input('date_begin')) {
            $order_list= $order_list->whereDate('order_dateTime','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $order_list = $order_list->whereDate('order_dateTime','<=' ,$request->input('date_end'));
        }

        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'bet_money':
                $order = 'bet_money';
                break;
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'order_dateTime';
                break;
        }

        $get_order = $order_list->get()->toArray();
        $result_sum = array_sum(array_column($get_order,'result'));
        $order_sum = array_sum(array_column($get_order,'bet_money'));
        $order_list = $order_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);

        return view('agent.userManager.user_result_detail',[
            'order_list' => $order_list,
            'result_sum' => $result_sum,
            'order_sum' => $order_sum
        ]);
    }
    public function user_result_history_detail(Request $request) {
        $user_id = $request->input('user_id');

        $order_list = OrderBackup::query()->with('info')
            ->leftJoin('qianduan','qianduan.serial_num','=','order_backup.serial_num')
            ->where('order_backup.user_id',$user_id)->groupBy('order_backup.order_sn');

        if($request->input('bet_period')) {
            $order_list = $order_list->where('bet_period',$request->input('bet_period'));
        }
        if($request->input('order_sn')) {
            $order_list = $order_list->where('order_sn' ,$request->input('order_sn'));
        }

        if($request->input('date_begin')) {
            $order_list= $order_list->whereDate('order_dateTime','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $order_list = $order_list->whereDate('order_dateTime','<=' ,$request->input('date_end'));
        }

        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'bet_money':
                $order = 'bet_money';
                break;
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'order_dateTime';
                break;
        }
        $get_order = $order_list->get()->toArray();
        $result_sum = array_sum(array_column($get_order,'result'));
        $order_sum = array_sum(array_column($get_order,'bet_money'));
        $order_list = $order_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);
        return view('agent.userManager.user_result_history_detail',[
            'order_list' => $order_list,
            'result_sum' => $result_sum,
            'order_sum' => $order_sum
        ]);
    }
    public function user_withdraw_detail(Request $request)
    {
        $user_id = $request->input('user_id');
        $withdraw_list = TradeRecord::query()
            ->where('user_id', $user_id)->with('user')->with('info')
            ->where('trade_type', 2)
            ->where('trade_state', 2);

        if ($request->input('date_begin')) {
            $withdraw_list = $withdraw_list->whereDate('trade_time', '>=', $request->input('date_begin'));
        }
        if ($request->input('date_end')) {
            $withdraw_list = $withdraw_list->whereDate('trade_time', '<=', $request->input('date_end'));
        }

        if ($request->input('date_begin') && $request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if (strtotime($end) < strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'trade_amount':
                $order = 'trade_amount';
                break;
            case 'trade_time':
                $order = 'trade_time';
                break;
            default:
                $order = 'trade_time';
                break;
        }
        $withdraw_list = $withdraw_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);

        return view('agent.userManager.user_withdraw_detail', [
            'withdraw_list' => $withdraw_list
        ]);
    }
    public function user_recharge_detail(Request $request){
        $user_id = $request->input('user_id');
        $withdraw_list = TradeRecord::query()
            ->where('user_id', $user_id)->with('user')->with('info')
            ->where('trade_type', 1);

        if ($request->input('date_begin')) {
            $withdraw_list = $withdraw_list->whereDate('trade_time', '>=', $request->input('date_begin'));
        }
        if ($request->input('date_end')) {
            $withdraw_list = $withdraw_list->whereDate('trade_time', '<=', $request->input('date_end'));
        }
        if ($request->input('trade_state')) {
            $withdraw_list = $withdraw_list->where('trade_state',  $request->input('trade_state'));
        }
        if ($request->input('date_begin') && $request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if (strtotime($end) < strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'trade_amount':
                $order = 'trade_amount';
                break;
            case 'trade_time':
                $order = 'trade_time';
                break;
            default:
                $order = 'trade_time';
                break;
        }
        $withdraw_list = $withdraw_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);

        return view('agent.userManager.user_recharge_detail', [
            'withdraw_list' => $withdraw_list
        ]);
    }

    public function getOrder(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $teamList = $this->getTeamUser($agent_id);

        $data = Order::query()
            ->with("user")
            ->with("info")
            ->with("account")
            ->with("odds")
            ->whereIn('user_id',$teamList);

        /*订单号查询*/
        if($request->input("order_id")){
            $data = $data->where("order.order_id",$request->input("order_id"));
        }
        /*用户名查询*/
        if($request->input("user_id")){
            $user_id = User::query()->where("username",$request->input("user_id"))->first(["user_id"]);
            if($user_id){
                $user_id = $user_id["user_id"];
            }else{
                $user_id = "";
            }
            $data = $data->where("order.user_id",$user_id);
        }
        /*期号查询*/
        if($request->input("bet_period")){
            $data = $data->where("order.bet_period",$request->input("bet_period"));
        }
        /*游戏类型查询*/
        if($request->input('game_id')) {
            $data = $data->where('order.gameId',$request->input('game_id'));
        }
        if($request->input('is_status')){
            if($request->input('is_status')==1){
                $data = $data->where('order.result','!=',0);
            }elseif ($request->input('is_status')==2){
                $data = $data->where('order.status',1)->where('order.result',0);
            }elseif ($request->input('is_status')==3){
                $data = $data->where('order.status',0)->where('order.delete_time',0);
            }elseif($request->input('is_status')==4){
                $data = $data->where('order.delete_time','!=',0);
            }
        }
        if($request->input('room_type')){
            $data = $data->where('order.room_type',$request->input('room_type'));
        }
        /*开始时间查询*/
        if($request->input('date_begin')) {
            $data= $data->whereDate('order_dateTime','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $data = $data->whereDate('order_dateTime','<=' ,$request->input('date_end'));
        }
        /*结束时间查询*/
        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        /*投注金额排序*/
        $order = $request->input('order');
        if($order){
            $data = $data->orderBy("order.bet_money",$order);
        }

//        $data1       = $data->orderBy("order_dateTime","desc")->paginate(20);
//        $datasum       = $data->orderBy("order_dateTime","desc")->get()->toArray();
//        $orderBetMoney = 0;
//        $orderBetZ = 0;
//        //var_dump($datasum);
//        foreach ($datasum as $item){
//            $orderBetMoney += $item['bonus'];
//            $orderBetZ += $item['bet_money'];
//        }
        $orderBetMoney    = $data->where('delete_time',0)->orderBy("order_dateTime","desc")->sum('bonus');
        $orderBetZ    = $data->where('delete_time',0)->orderBy("order_dateTime","desc")->sum('bet_money');
        $game_list  = Game::query()->get()->toArray();
        $return_arr = [
            'data'      => $data->orderBy("order_dateTime","desc")->paginate(20),
            'game_list' => $game_list,
            "game_id"   => $request->input('game_id'),
            "user_id"   => $request->input("user_id"),
            "date_begin"=> $request->input('date_begin'),
            "date_end"  => $request->input('date_end'),
            "order"     => $order,
            "orderBetMoney"     => $orderBetMoney,
            "orderBetZ"     => $orderBetZ,
            'is_status'   => $request->input('is_status'),
            'room_type'     => $request->input('room_type')
        ];
        return view('agent.userManager.getOrder_back',$return_arr);
    }

    public function getOrder_back(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $parent_arr = UserInfo::query()->where('parent_user_id',$agent_id)->get()->toArray();
        $second_agent_arr = array_column($parent_arr,'user_id');
        array_push($second_agent_arr,$agent_id);
        $search_id_arr = UserInfo::query()->whereIn('parent_user_id',$second_agent_arr)->get()->toArray();
        $order_list = OrderBackup::query()->with('info')
            ->select(['order_backup.id','order_sn','order_backup.user_id','game.name',
                'bet_money','bet_num','result','serial_num','bet_period','order_dateTime'])
            //->leftJoin('qianduan','qianduan.serial_num','=','order_backup.serial_num')
            ->leftJoin('game','game.id','=','order_backup.game_id')
            ->leftJoin('user','order_backup.user_id','=','user.user_id')
            ->where('user.role_id',1)
            ->whereIn('order_backup.user_id',array_column($search_id_arr,'user_id'))
            ->groupBy('order_backup.order_sn');

        $game_list = Game::query()->get()->toArray();
        if($request->input('user_id')) {
            $order_list = $order_list->where('order_backup.user_id',$request->input('user_id'));
        }
        if($request->input('game_id')) {
            $order_list = $order_list->where('order_backup.game_id',$request->input('game_id'));
        }
        if($request->input('order_id')) {
            $order_list = $order_list->where('order_backup.id',$request->input('order_id'));
        }
        if($request->input('user_name')) {
            $order_list = $order_list->where('order_backup.username',$request->input('user_name'));
        }
        if($request->input('bet_period')) {
            $order_list = $order_list->where('bet_period',$request->input('bet_period'));
        }
        if($request->input('order_sn')) {
            $order_list = $order_list->where('order_sn' ,$request->input('order_sn'));
        }

        if($request->input('date_begin')) {
            $order_list= $order_list->whereDate('order_dateTime','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $order_list = $order_list->whereDate('order_dateTime','<=' ,$request->input('date_end'));
        }

        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'bet_money':
                $order = 'bet_money';
                break;
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'order_sn';
                break;
        }

        $order_list = $order_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);
        $serial_nums = array_column($order_list->toArray()['data'], 'serial_num');
        $serial_names = DB::table('qianduan')->whereIn('serial_num', $serial_nums)->groupBy('serial_num')->get(['serial_num', 'show_name'])->keyBy('serial_num')->toArray();

        foreach ($order_list as $k => $v) {
            $order_list[$k]['show_name'] = $serial_names[$v['serial_num']]->show_name;
        }
        return view('agent.userManager.getOrder_back',[
            'order_list' => $order_list,
            'game_list' => $game_list
        ]);
    }
    public function getOrder_detail(Request $request) {
        $order_sn = $request->input('order_sn');
        $order_list = Order::query()->where('order_sn',$order_sn)->get()->toArray();

        $table = 'draw_results_'.$order_list[0]['game_id'];
        $game_list  = Game::query()->where('id',$order_list[0]['game_id'])->get()->toArray();
        $bet_period = $order_list[0]['bet_period'];
        $result_list = DB::table($table)->where('period_number' ,$bet_period)->get()->toArray();
        return view('agent.userManager.getOrder_detail',[
            'result_list' => $result_list,
            'order_list' => $order_list,
            'game_list' => $game_list
        ]);
    }
    public function getOrder_detail_back(Request $request) {

        $order_sn = $request->input('order_sn');
        $order_list = OrderBackup::query()->where('order_sn',$order_sn)->get()->toArray();

        $table = 'draw_results_'.$order_list[0]['game_id'];
        $game_list  = Game::query()->where('id',$order_list[0]['game_id'])->get()->toArray();
        $bet_period = $order_list[0]['bet_period'];
        $result_list = DB::table($table)->where('period_number' ,$bet_period)->get()->toArray();

        return view('agent.userManager.getOrder_detail_back',[
            'result_list' => $result_list,
            'order_list' => $order_list,
            'game_list' => $game_list
        ]);
    }

    public function teamMsg(Request $request){
        $user_id    = $request->user_id;
        $date_begin = $request->date_begin;
        $date_end   = $request->date_end;
        $time = $request->time?$request->time:1;
        $agent_id = auth('agent')->user()->user_id;
        $select = [
            DB::raw("sum(total) as total"),
            DB::raw("sum(betting) as betting"),
            DB::raw("sum(betNum) as betNum"),
            DB::raw("sum(bonus) as bonus"),
            DB::raw("sum(winning) as winning"),
            DB::raw("sum(recharge) as recharge"),
            DB::raw("sum(withdrawals) as withdrawals"),
        ];
        $teamList = $this->getTeamUser($agent_id);
        $yue = Account::query()->whereIn('user_id',$teamList)->sum('remaining_money');
        $data = UserDailySettle::query()
            ->select($select)
            ->whereIn('user_id',$teamList)
            ->where('is_status',1);
        if($time&&!$date_begin){
            $all_time = $this->near_time($time);
            $date_begin = substr($all_time[0],0,10);
            $date_end = substr($all_time[1],0,10);
        }

        if($date_begin){
            $date_begin_res     = $date_begin." 00:00:00";
            $data               = $data->whereDate("create_time",">=",$date_begin_res);
        }
        if($date_end){
            $date_end_res       = $date_end." 23:59:59";
            $data               = $data->whereDate("create_time","<=",$date_end_res);
        }

        $data = $data->first();

        return view('agent.teamMsg.index',[
            "data"              => $data,
            "user_id"           => $user_id,
            "date_begin"        => $date_begin,
            "date_end"          => $date_end,
            "time"              => $time,
            'yue'               => $yue
        ]);
    }

    public function teamJou(Request $request){
        $time = $request->time?$request->time:1;
        $user_id    = $request->user_id;
        $val        = $request->val;
        $status     = $request->select_status;
        $agent_id = auth('agent')->user()->user_id;
        $teamList = $this->getTeamUser($agent_id);
        $data = Journalaccount::query()->with(["info"=>function($query){
            $query->select("user_id","name","nickname");
        }])->with(["user"=>function($query){
            $query->select("user_id","username");
        }])
            ->with("status")
            ->whereIn('user_id',$teamList);

        if($time&&!$val){
            $near_time = $this->near_time($time);
            $start_time = $near_time[0];
            $end_time   = $near_time[1];
//           $data = $data->whereDate("create_time",">=",$start_time)
//               ->whereDate("create_time","<=",$end_time);
            $start_time = substr($near_time[0],0,10);
            $end_time = substr($near_time[1],0,10);
            $val = $start_time.' - '.$end_time;
        }
        if($user_id){
            $user_id2 = User::query()->where("username",$request->input("user_id"))->first(["user_id"]);
            if($user_id2){
                $user_id2 = $user_id2["user_id"];
            }else{
                $user_id2 = "";
            }
            $data = $data->where("user_id",$user_id2);
        }
        if($val){
            $start_time = substr($val,0,10)." 00:00:00";
            $end_time   = substr($val,13)." 23:59:59";
            $data = $data->whereDate("create_time",">=",$start_time)
                ->whereDate("create_time","<=",$end_time);
        }
        if($status){
            $data = $data->where("change_status",$status);
        }

        $data = $data->orderBy("create_time","desc")->paginate(20);
        $status_data = Accountstatus::query()->get()->toArray();

        return view("agent.teamMsg.teamJou",[
            "data"      => $data,
            "time"      => $time,
            "user_id"   => $user_id,
            "val"       => $val,
            "status_data" => $status_data,
            "status"    => $status,
        ]);
    }

    public function teamStat(Request $request) {
        $dl_level = auth('agent')->user()->dl_level;
        if($dl_level==2){
            $time = $request->time?$request->time:1;
            $val  = $request->val;
            $user_id = $request->user_id;
            $shangji = $request->shangji;
            $xiaji   = $request->xiaji;
            $agent_id = auth('agent')->user()->user_id;
            $teamList = $this->getTeamUser($agent_id);
            $select = [
                DB::raw("sum(total) as total"),
                DB::raw("sum(betting) as betting"),
                DB::raw("sum(betNum) as betNum"),
                DB::raw("sum(bonus) as bonus"),
                DB::raw("sum(winning) as winning"),
                DB::raw("sum(rebate) as rebate"),
                DB::raw("sum(recharge) as recharge"),
                DB::raw("sum(withdrawals) as withdrawals"),
                "account.remaining_money",
                "user.user_id",
                "user.role_id",
                "user.username"
            ];
            $data = UserDailySettle::query()
                ->select($select)
                ->leftJoin('user','user.user_id','user_daily_settle.user_id')
                ->leftJoin('account','account.user_id','user_daily_settle.user_id')
                ->where('user.is_fictitious','!=',1)
                ->whereIn('user.user_id',$teamList);
            $all_data = UserDailySettle::query()
                ->select($select)
                ->leftJoin('user','user.user_id','user_daily_settle.user_id')
                ->leftJoin('account','account.user_id','user_daily_settle.user_id')
                ->where('user.is_fictitious','!=',1)->with(["user"=>function($query){
                    $query->select("user_id","username","parent_user_id");
                }])
                ->groupBy('user_daily_settle.user_id')
                ->whereIn('user.user_id',$teamList);;

            if($shangji){
                $shangji = User::query()->where("username",$shangji)->value("user_id");
                $data       = $data->where("user_daily_settle.user_id",$shangji);
                $all_data   = $all_data->where("user_daily_settle.user_id",$shangji);
            }
            if($xiaji){
                $all_user_id = User::query()->where("parent_user_id",$xiaji)->pluck("user_id")->toArray();
                $data       = $data->whereIn("user_daily_settle.user_id",$all_user_id);
                $all_data   = $all_data->whereIn("user_daily_settle.user_id",$all_user_id);
            }

            /*用户名查找*/
            if($user_id){
                $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
                if($user_id_select){
                    $user_id_select = $user_id_select["user_id"];
                }else{
                    $user_id_select = "";
                }
                $data = $data->where("user_daily_settle.user_id",$user_id_select);
                $all_data = $all_data->where("user_daily_settle.user_id",$user_id_select);
            }
            /*快捷选时*/
            if($time && !$val){
                $near_time = $this->near_time($time);
                $start_time = substr($near_time[0],0,10);
                $end_time = substr($near_time[1],0,10);
                $val = $start_time.' - '.$end_time;
            }
            /*日期范围*/
            if($val){
                $start_time = substr($val,0,10);
                $end_time = substr($val,13);

                $data = $data->whereDate("user_daily_settle.create_time",">=",$start_time)
                    ->whereDate("user_daily_settle.create_time","<=",$end_time);

                $all_data = $all_data->whereDate("user_daily_settle.create_time",">=",$start_time)
                    ->whereDate("user_daily_settle.create_time","<=",$end_time);
            }

            $data = $data->first();
            $all_data = $all_data->paginate(20);

            $c=0;
            foreach($all_data as $k=>$v){
                $c=$c+$all_data[$k]['remaining_money'];
            }
            $data['remaining_money']=$c;
            return view('agent.teamMsg.teamStat',[
                'data'      => $data,
                "all_data"  => $all_data,
                "time"      => $time,
                "val"       => $val,
                "user_id"   => $user_id,
                "start_time"   => $start_time,
                "end_time"   => $end_time,
                "type"      =>2
            ]);
        }else{
            $time = $request->time?$request->time:1;
            $val  = $request->val;
            $user_id = $request->user_id;
            $shangji = $request->shangji;
            $xiaji   = $request->xiaji;
            $agent_id = auth('agent')->user()->user_id;
            $agent_username = auth('agent')->user()->username;
            $teamList = BuyUser::query()->where('parent_user_id',$agent_username)->where('dl_level',2)->pluck('user_id');
            $teamList1= $this->getTeamUser($agent_id);
            $data = [];
            //foreach ($teamList as $item){}
            $select = [
                DB::raw("sum(total) as total"),
                DB::raw("sum(betting) as betting"),
                DB::raw("sum(betNum) as betNum"),
                DB::raw("sum(bonus) as bonus"),
                DB::raw("sum(winning) as winning"),
                DB::raw("sum(rebate) as rebate"),
                DB::raw("sum(recharge) as recharge"),
                DB::raw("sum(withdrawals) as withdrawals"),
                "account.remaining_money",
                "user.user_id",
                "user.role_id",
                "user.username"
            ];
            $data = UserDailySettle::query()
                ->select($select)
                ->leftJoin('user','user.user_id','user_daily_settle.user_id')
                ->leftJoin('account','account.user_id','user_daily_settle.user_id')
                ->where('user.is_fictitious','!=',1)
                ->whereIn('user.user_id',$teamList1);
            /*用户名查找*/
            if($user_id){
                $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
                if($user_id_select){
                    $user_id_select = $user_id_select["user_id"];
                }else{
                    $user_id_select = "";
                }
                $data = $data->where("user_daily_settle.user_id",$user_id_select);
            }
            /*快捷选时*/
            if($time && !$val){
                $near_time = $this->near_time($time);
                $start_time = substr($near_time[0],0,10);
                $end_time = substr($near_time[1],0,10);
                $val = $start_time.' - '.$end_time;
            }
            if($val){
                $start_time = substr($val,0,10);
                $end_time = substr($val,13);
                $data = $data->whereDate("user_daily_settle.create_time",">=",$start_time)
                    ->whereDate("user_daily_settle.create_time","<=",$end_time);

            }
            $data = $data->first();
            $all_data = [];
            foreach ($teamList as $item){
                $teamLists= $this->getTeamUser($item);
                    $list_all = UserDailySettle::query()
                        ->select($select)
                        ->leftJoin('user','user.user_id','user_daily_settle.user_id')
                        ->leftJoin('account','account.user_id','user_daily_settle.user_id')
                        ->where('user.is_fictitious','!=',1)->with(["user"=>function($query){
                            $query->select("user_id","username","parent_user_id");
                        }])
                        ->whereIn('user.user_id',$teamLists);
                    /*用户名查找*/
                    if($user_id){
                        $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
                        if($user_id_select){
                            $user_id_select = $user_id_select["user_id"];
                        }else{
                            $user_id_select = "";
                        }
                        $list_all = $list_all->where("user_daily_settle.user_id",$user_id_select);
                    }
                    /*日期范围*/
                    if($val){
                        $start_time = substr($val,0,10);
                        $end_time = substr($val,13);

                        $list_all = $list_all->whereDate("user_daily_settle.create_time",">=",$start_time)
                            ->whereDate("user_daily_settle.create_time","<=",$end_time);
                    }
                    $list_all = $list_all->first();
                    if(!$list_all["remaining_money"])    $list_all["remaining_money"] = 0;
                    if(!$list_all["betting"]) $list_all["betting"] = 0;
                    if(!$list_all["betNum"]) $list_all["betNum"] = 0;
                    if(!$list_all["recharge"]) $list_all["recharge"] = 0;
                    if(!$list_all["withdrawals"]) $list_all["withdrawals"] = 0;
                    if(!$list_all["bonus"]) $list_all["bonus"] = 0;
                    if(!$list_all["winning"]) $list_all["winning"] = 0;
                    if(!$list_all["total"]) $list_all["total"] = 0;
                $itemUsername = BuyUser::query()->where('user_id',$item)->value('username');
                $role_id = BuyUser::query()->where('user_id',$item)->value('role_id');
                $list_all['username'] = $itemUsername;
                $list_all['user_id'] = $item;
                $list_all['role_id'] = $role_id;
                array_push($all_data,$list_all);
            }

            $c=0;
            foreach($all_data as $k=>$v){
                $c=$c+$all_data[$k]['remaining_money'];
            }
            $data['remaining_money']=$c;
            return view('agent.teamMsg.teamStat',[
                'data'      => $data,
                "all_data"  => $all_data,
                "time"      => $time,
                "val"       => $val,
                "user_id"   => $user_id,
                "start_time"   => $start_time,
                "end_time"   => $end_time,
                "type"      =>1
            ]);
        }

    }
    public function near_time($time){
        switch ($time){
            /*今天*/
            case 1:
                $startTime  =   date('Y-m-d 00:00:00', time());
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
            /*昨天*/
            case 2:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-1 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', strtotime("-1 day",time()));
                break;
            /*近三天 */
            case 3:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-3 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
            /*近七天 */
            case 7:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-7 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
            /*近半月 */
            case 15:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-15 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
            /*近一月 */
            case 30:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-30 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
        }
        return [$startTime,$endTime];
    }

}





