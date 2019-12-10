<?php

namespace App\Http\Controllers\Manager;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\OrderBackup;
use App\Models\Relation;
use App\Models\UserDailySettle;
use App\Models\Payoff;
use App\Models\Order;
use App\Models\Profit;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\IncomeDailySettle;
use App\Models\AgentDailySettle;
use App\Models\TradeRecord;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class statManagerController  extends Controller
{
    /*今日报表*/
    public function statIncome(Request $request){
        $time = Carbon::now()->toDateString();
        $model = Order::query()
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)
            ->where('delete_time',0)->whereDate('order_dateTime',$time);
        $data[0] = $model->where('gameId',1)->sum('bet_money');
        $model = Order::query()
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)->where('delete_time',0)->whereDate('order_dateTime',$time);
        $data[1]= $model->where('gameId',2)->sum('bet_money');
        $model = Order::query()
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)->where('delete_time',0)->whereDate('order_dateTime',$time);
        $data[2] = $model->where('gameId',3)->sum('bet_money');
        $model = Order::query()
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)->where('delete_time',0)->whereDate('order_dateTime',$time);
        $data[3] = $model->where('gameId',4)->sum('bet_money');
        $model = Order::query()
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)->where('delete_time',0)->whereDate('order_dateTime',$time);
        $data[4] = $model->where('gameId',5)->sum('bet_money');
        $month = Carbon::now()->month;
        $timeList = [
            Carbon::create(Carbon::now()->year, Carbon::now()->month)->startOfMonth()->toDateTimeString(),
            Carbon::create(Carbon::now()->year, Carbon::now()->month)->endOfMonth()->toDateTimeString()
        ];
        $orderList[$month.'月'] = Order::query()
            ->select(DB::raw("sum(bet_money) as bet_money"),DB::raw("sum(bonus) as bonus"))
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)
            ->where('order.delete_time',0)
            ->whereDate('order.order_dateTime','>=',$timeList[0])
            ->whereDate('order.order_dateTime','<=',$timeList[1])
            ->first();
        $orderList['今天'] = Order::query()
            ->select(DB::raw("sum(bet_money) as bet_money"),DB::raw("sum(bonus) as bonus"))
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)
            ->where('order.delete_time',0)
            ->whereDate('order.order_dateTime','=',$time)
            ->first();
        $orderList['昨天'] = Order::query()
            ->select(DB::raw("sum(bet_money) as bet_money"),DB::raw("sum(bonus) as bonus"))
            ->leftJoin('user','user.user_id','order.user_id')
            ->where('user.is_fictitious','!=',1)
            ->where('order.delete_time',0)
            ->whereDate('order.order_dateTime','=',Carbon::yesterday()->toDateString())
            ->first();
        if($orderList[$month.'月']['bet_money'] == null) $orderList[$month.'月']['bet_money'] = 0 ;
        if($orderList[$month.'月']['bonus'] == null) $orderList[$month.'月']['bonus'] = 0 ;
        if($orderList['今天']['bet_money'] == null) $orderList['今天']['bet_money'] = 0 ;
        if($orderList['今天']['bonus'] == null) $orderList['今天']['bonus'] = 0 ;
        if($orderList['昨天']['bet_money'] == null) $orderList['昨天']['bet_money'] = 0 ;
        if($orderList['昨天']['bonus'] == null) $orderList['昨天']['bonus'] = 0 ;

        $userList[0] = BuyUser::query()->where('is_tourist',0)->where('user.username','!=','admin')->count();
        $userList[1] = BuyUser::query()
            ->leftJoin('userinfo','userinfo.user_id','=','user.user_id')
            ->where('is_tourist',0)
            ->whereDate('create_time',$time)
            ->where('user.username','!=','admin')
            ->count();
        $userList[2] = BuyUser::query()
            ->where('is_tourist',0)
            ->where('role_id',2)
            ->where('username','!=','admin')
            ->count();
        $userList[3] = BuyUser::query()
            ->where('is_tourist',0)
            ->where('role_id',1)
            ->where('username','!=','admin')
            ->count();
        $userList[4] = BuyUser::query()
            ->leftJoin('account','account.user_id','=','user.user_id')
            ->where('user.is_tourist',0)
            ->where('role_id',1)
            ->where('user.username','!=','admin')
            ->where('user.is_fictitious','!=',1)
            ->sum('remaining_money');
        return view('manager.statManager.statIncome',[
            "data"      => $data,
            "orderList"=> $orderList,
            "userList" => $userList
        ]);
    }



    /**
     * 作用：近几天和时间
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $time
     * @return array
     */
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




    public function day_money_detail(Request $request) {
        $unsettle_userids = UserInfo::query()->where('profit_status', 2)->get(['user_id'])->toArray();
        $time = $request->input('time');
        $day_money_list = OrderBackup::query()->with('info')
                         ->whereNotIn('user_id', array_column($unsettle_userids, 'user_id'))
                         ->select(['user_id',DB::raw('sum(result) as result'),DB::raw('sum(bet_money) as bet_money'),
                                             'order_dateTime',DB::raw('sum(bet_num) as bet_num')])
                         ->whereDate('order_dateTime','=',$time);

        if ($request->input('user_id')) {
            $day_money_list = $day_money_list->where('user_id', $request->input('user_id'));
        }
        switch ($request->input('column')) {
             case 'result':
                 $order = 'result';
                 break;
             default:
                 $order = 'user_id';
                 break;
        }
        $day_money_list = $day_money_list->groupBy('user_id')->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);

        return view('manager.statManager.day_money_detail',[
            'day_money_list'=> $day_money_list,
            'time' => $time
        ]);
    }
    public function server_charge_detail(Request $request) {
        $time = $request->input('time');
        $server_charge = TradeRecord::query()->with('info')
            ->select( ['trade_record.user_id',DB::raw('sum(service_money) as result')])
            ->whereDate('updated_at','=',$time)->where('trade_state',2);

        if ($request->input('user_id')) {
            $server_charge= $server_charge->where('trade_record.user_id', $request->input('user_id'));
        }
        if($request->input('user_name')) {
            $server_charge= $server_charge->where('user.username', $request->input('user_name'));
        }
        switch ($request->input('column')) {
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'user_id';
                break;
        }
        $server_charge = $server_charge->groupBy('trade_record.user_id')->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);

        return view('manager.statManager.server_charge_detail',[
            'server_charge'=> $server_charge,
            'time' => $time
        ]);
    }
    public function share_profit_detail(Request $request) {
        $time = $request->input('time');
        $share_profit = Profit::query()->with('agentinfo')
            ->leftJoin('user','user.user_id','=','profit.agent_user_id')
            ->select( ['profit.agent_user_id','profit.user_id',DB::raw('sum(profit_amount) as result'),'user.username'])
            ->whereDate('created_at','=',$time)->where('leader_user_id',235689);

        if ($request->input('user_id')) {
            $share_profit= $share_profit->where('profit.agent_user_id', $request->input('user_id'));
        }
        if ($request->input('user_name')) {
            $share_profit= $share_profit->where('user.username', $request->input('user_name'));
        }
        switch ($request->input('column')) {
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'profit.agent_user_id';
                break;
        }
        $share_profit = $share_profit->groupBy('agent_user_id')->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);
        return view('manager.statManager.share_profit_detail',[
            'share_profit'=> $share_profit,
            'time' => $time
        ]);
    }
    public function day_income_user(Request $request) {
        $time = $request->input('time');
        $user_id = $request->input('user_id');
        $day_money_list = OrderBackup::query()->with('info')->with('user')
                          ->whereDate('order_dateTime','=',$time)
                          ->where('user_id',$user_id)->paginate(10);
        return view('manager.statManager.day_money_user',[
            'day_money_list' => $day_money_list,
            'time' => $time,
            'user_id' => $user_id
        ]);
    }

    public function day_income_stat(Request $request) {
        $time = $request->input('time');
        $user_id = $request->input('user_id');
        $day_money_list = OrderBackup::query()->with('info')->with('user')
            ->whereDate('order_dateTime','=',$time)
            ->where('user_id',$user_id)->paginate(10);

        return view('manager.statManager.day_money_statuser',[
            'day_money_list' => $day_money_list,
            'time' => $time,
            'user_id' => $user_id
        ]);
    }

    public function server_charge_user(Request $request) {
        $time = $request->input('time');
        $day_start = $time . ' 07:00:00';
        $day_end = date('Y-m-d', strtotime($time)+24*60*60).' 06:59:59';
        $user_id = $request->input('user_id');

        $server_charge = TradeRecord::query()->with('info')->with('user')
            ->whereBetween('updated_at',[$day_start, $day_end])
            ->where('trade_state',2)
            ->where('user_id',$user_id);

        $server_charge = $server_charge->paginate(10);

        return view('manager.statManager.server_charge_user',[
            'server_charge'=> $server_charge,
            'time' => $time
        ]);
    }

    public function share_profit_user(Request $request) {
        $time = $request->input('time');
        if (empty($time)) {
            if (date('H')<7) {
                $time = date('Y-m-d', time()-24*60*60);
            } else {
                $time = date('Y-m-d');
            }
        }
        $day_start = $time . ' 07:00:00';
        $day_end = date('Y-m-d', strtotime($time)+24*60*60).' 06:59:59';
        $user_id = $request->input('user_id');
        $share_profit = Profit::query()->with('agentinfo')->with('orderinfo')->with('agentuser')
            ->where('agent_user_id',$user_id)
            ->whereBetween('created_at',[$day_start, $day_end])
            ->where('leader_user_id',235689);
        $share_profit = $share_profit->paginate(10);

        return view('manager.statManager.share_profit_user',[
            'share_profit'=> $share_profit,
            'time' => $time
        ]);
    }


    /**
     * 作用：用户盈亏数据
     * 作者：信
     * 时间：2018/4/18
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statUser(Request $request) {
        $time = $request->time?$request->time:1;
        $val  = $request->val;
        $user_id = $request->user_id;
        $shangji = $request->shangji;
        $xiaji   = $request->xiaji;
        $select = [
            DB::raw("sum(total) as total"),
            DB::raw("sum(betting) as betting"),
            DB::raw("sum(betNum) as betNum"),
            DB::raw("sum(bonus) as bonus"),
            DB::raw("sum(winning) as winning"),
            DB::raw("sum(rebate) as rebate"),
            DB::raw("sum(recharge) as recharge"),
            DB::raw("sum(withdrawals) as withdrawals"),
            "account.remaining_money"
        ];
        $data = UserDailySettle::query()
            ->select($select)
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')
            ->leftJoin('account','account.user_id','user_daily_settle.user_id')
            ->where('user.is_fictitious','!=',1)
            ->where('user.username','!=','admin');

        $all_data = UserDailySettle::query()
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')
            ->leftJoin('account','account.user_id','user_daily_settle.user_id')
            ->where('user.username','!=','admin')
            ->where('user.is_fictitious','!=',1)->with(["user"=>function($query){
               $query->select("user_id","username","parent_user_id");
            }])
            ->groupBy('user_daily_settle.user_id');

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

        return view('manager.statManager.statUser',[
            'data'      => $data,
            "all_data"  => $all_data,
            "time"      => $time,
            "val"       => $val,
            "user_id"   => $user_id,
            "start_time"   => $start_time,
            "end_time"   => $end_time,
        ]);
    }

    /*平台盈亏数据*/

    public function statPlat(Request $request) {
        $time = $request->time?$request->time:1;
        $val  = $request->val;

        $select = [
            DB::raw("sum(total) as total"),
            DB::raw("sum(betting) as betting"),
            DB::raw("sum(betNum) as betNum"),
            DB::raw("sum(bonus) as bonus"),
            DB::raw("sum(winning) as winning"),
            DB::raw("sum(recharge) as recharge"),
            DB::raw("sum(withdrawals) as withdrawals"),
            'create_time'
        ];
        $data = UserDailySettle::query()->select($select)->leftJoin('user','user.user_id','user_daily_settle.user_id')->where('user.is_fictitious','!=',1);
        $all_data = UserDailySettle::query()->select($select)->leftJoin('user','user.user_id','user_daily_settle.user_id')->where('user.is_fictitious','!=',1);

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
        $all_data = $all_data->groupBy('user_daily_settle.create_time')->paginate(20);


        return view('manager.statManager.statPlat',[
            'data'      => $data,
            "all_data"  => $all_data,
            "time"      => $time,
            "val"       => $val,
            "start_time"   => $start_time,
            "end_time"   => $end_time,
        ]);
    }


    public function statUser_detail(Request $request) {
        $date_begin = $request->input('date_begin');
        $date_end = $request->input('date_end');
        $user_id = $request->input("user_id");

        $day_money_list = Payoff::query()->with('info')
            ->where('user_id',$user_id)
            ->whereDate('date','>=',$date_begin)
            ->whereDate('date','<=',$date_end);
        switch ($request->input('column')) {
            case 'bet_num':
                $order = 'bet_num';
                break;
            case 'bet_money':
                $order = 'bet_money';
                break;
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'payoff.user_id';
                break;
        }
        $day_money_list = $day_money_list->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);

        return view('manager.statManager.statUser_detail',[
            'day_money_list'=> $day_money_list,
            'user_id' => $user_id,
            'date_begin' => $date_begin,
            'date_end' => $date_end
        ]);
    }


    /**
     * 作用：充值数据统计
     * 作者：
     * 时间：2018/4/17
     * 修改：信
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statRecharge(Request $request) {
        $user_id    = $request->user_id;
        $date_begin = $request->date_begin;
        $date_end   = $request->date_end;
        $time = $request->time?$request->time:1;

        $select = ["user.username","trade_record.id","trade_record.trade_amount","trade_record.trade_info",
            "trade_record.created_at","account.remaining_money","trade_record.user_id","account.remaining_money"
        ];
        $data = TradeRecord::query()
            ->select($select)
            ->whereIn("trade_record.trade_type",[1,3])
            ->where("trade_record.trade_state",2)
            ->leftJoin("user","user.user_id","=","trade_record.user_id")
            ->leftJoin("account","account.user_id","=","trade_record.user_id")
            ->where('user.is_fictitious','!=',1)
            ->orderBy("trade_record.created_at","desc");

        $sousuo_chongzhi    = TradeRecord::query()
            ->where("trade_state",2)
            ->whereIn("trade_record.trade_type",[1,3])
            ->leftJoin('user','user.user_id','trade_record.user_id')
            ->where('user.is_fictitious','!=',1);
        $all_chongzhi       = TradeRecord::query()
            ->where("trade_state",2)
            ->whereIn("trade_record.trade_type",[1,3])
            ->leftJoin('user','user.user_id','trade_record.user_id')
            ->where('user.is_fictitious','!=',1)
            ->sum("trade_amount");

        if($time&&!$date_begin){
            $all_time = $this->near_time($time);
//            $data = $data->whereDate("trade_record.created_at",">=",$all_time[0])
//                        ->whereDate("trade_record.created_at","<=",$all_time[1]);
//            $sousuo_chongzhi    = $sousuo_chongzhi->whereDate("trade_record.created_at",">=",$all_time[0])
//                                                    ->whereDate("trade_record.created_at","<=",$all_time[1]);
            $date_begin = substr($all_time[0],0,10);
            $date_end = substr($all_time[1],0,10);
        }

        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data               = $data->where("trade_record.user_id",$user_id_select);
            $sousuo_chongzhi    = $sousuo_chongzhi->where("trade_record.user_id",$user_id_select);
        }
        if($date_begin){
            $date_begin_res     = $date_begin." 00:00:00";
            $data               = $data->whereDate("trade_record.created_at",">=",$date_begin_res);
            $sousuo_chongzhi    = $sousuo_chongzhi->whereDate("trade_record.created_at",">=",$date_begin_res);
        }
        if($date_end){
            $date_end_res       = $date_end." 23:59:59";
            $data               = $data->whereDate("trade_record.created_at","<=",$date_end_res);
            $sousuo_chongzhi    = $sousuo_chongzhi->whereDate("trade_record.created_at","<=",$date_end_res);
        }

        $data = $data->paginate(20);
        $sousuo_chongzhi = $sousuo_chongzhi->sum("trade_amount");

        return view('manager.statManager.statRecharge',[
            "data"              => $data,
            "all_chongzhi"      => $all_chongzhi,
            "user_id"           => $user_id,
            "sousuo_chongzhi"   => $sousuo_chongzhi,
            "date_begin"        => $date_begin,
            "date_end"          => $date_end,
            "time"              => $time
        ]);
    }




    public function statRecharge_detail(Request $request) {
        list($date_begin, $date_end) = $this->getStatDate($request);
        $user_id = $request->input("user_id");
        $user_info = UserInfo::query()->where('user_id', $user_id)->first()->toArray();
        $day_money_list = Payoff::query()->with('info')
            ->leftJoin('user','user.user_id','=','payoff.user_id')
            ->where('payoff.user_id',$user_id)
            ->whereDate('date','>=',$date_begin)
            ->whereDate('date','<',$date_end);
        switch ($request->input('column')) {
            case 'recharge':
                $order = 'recharge';
                break;
            default:
                $order = 'date';
                break;
        }
        $day_money_list = $day_money_list->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->get()->toArray();
        if (strtotime($date_end)>=strtotime(date('Y-m-d'))) {
            $toady_recharge = TradeRecord::query()->with('info')
                ->select(['user_id', DB::raw('sum(trade_amount) as recharge'), 'trade_time'])
                ->where('user_id', $user_id)
                ->where('trade_type', 1)
                ->where('trade_state', 2)
                ->whereBetween('trade_time', [date('Y-m-d', strtotime($date_end)-24*60*60).' 07:00:00', $date_end.' 07:00:00'])
                ->get()->toArray();
            $toady_recharge = $toady_recharge[0];
            $insert = [
                'recharge' => (double)$toady_recharge['recharge'],
                'user_id' => $user_id,
                'info' => $user_info,
                'date' => $request->input('date_end'),
            ];
            array_unshift($day_money_list, $insert);
        }
        return view('manager.statManager.statRecharge_detail',[
            'day_money_list'=> $day_money_list,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
        ]);
    }

    public function day_recharge_user(Request $request) {
        $time = $request->input('time');
        $user_id = $request->input('user_id');
        $day_money_list = TradeRecord::query()->with('info')
            ->whereBetween('trade_time',[$time.' 07:00:00', date('Y-m-d', strtotime($time)+24*60*60).' 07:00:00'])
            ->where('user_id',$user_id)
            ->where('trade_state',2)
            ->where('trade_type',1)
            ->paginate(10);
        return view('manager.statManager.day_recharge_user',[
            'day_money_list' => $day_money_list,
            'time' => $time,
            'user_id' => $user_id
        ]);
    }

    public function statAgent(Request $request) {
        list($date_begin, $date_end) = $this->getStatDate($request);
        $agent_daily_list = Profit::query()->with('agentinfo')
            ->leftJoin('user','user.user_id','=','profit.agent_user_id')
            ->select(['username','profit.agent_user_id',DB::raw('sum(profit_amount) as daymoney')])
            ->where('leader_user_id',235689)
            ->where('created_at',">=",$date_begin . ' 07:00:00')
            ->where('created_at','<',$date_end . ' 07:00:00')
            ->groupBy('profit.agent_user_id');
        if($request->input('user_name')) {
            $agent_daily_list =  $agent_daily_list->where('user.username',$request->input('user_name'));
        }
        switch ($request->input('column')) {
            case 'daymoney':
                $order = 'daymoney';
                break;
            default:
                $order = 'profit.agent_user_id';
                break;
        }
        $agent_daily_list = $agent_daily_list->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);
        $all_income = Profit::query()
            ->where('leader_user_id',235689)
            ->where('created_at',">=",$date_begin . ' 07:00:00')
            ->where('created_at','<',$date_end . ' 07:00:00')
            ->select([DB::raw('sum(profit_amount) as all_income')])
            ->get()->toArray();
        return view('manager.statManager.statAgent',[
            'agent_daily_list'=> $agent_daily_list,
            'date_begin' => $date_begin,
            'date_end' => date('Y-m-d', strtotime($date_end)-24*60*60),
            'all_income' => $all_income
        ]);
    }
    public function statAgent_detail(Request $request) {
        list($date_begin, $date_end) = $this->getStatDate($request);
        $user_id = $request->input("user_id");
        $user_info = UserInfo::query()->where('user_id', $user_id)->first()->toArray();
        $day_money_list = AgentDailySettle::query()->with('info')
            ->leftJoin('user','user.user_id','=','agent_daily_settle.agent_user_id')
            ->where('agent_daily_settle.agent_user_id',$user_id)
            ->whereDate('stat_day','>=',$date_begin)
            ->whereDate('stat_day','<=',$date_end);
        switch ($request->input('column')) {
            case 'daymoney':
                $order = 'daymoney';
                break;
            default:
                $order = 'agent_daily_settle.agent_user_id';
                break;
        }
        $day_money_list = $day_money_list->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->get()->toArray();
        $today_profit = Profit::query()->with('agentinfo')
                        ->select([DB::raw('sum(profit_amount) as profit'),'created_at','agent_user_id'])
                        ->where('leader_user_id', 235689)
                        ->whereBetween('created_at', [date('Y-m-d', strtotime($date_end)-24*60*60).' 07:00:00', $date_end.' 07:00:00'])
                        ->first();

        $now = Carbon::now()->toDateTimeString();
        if (strtotime($date_end.' 07:00:00')>strtotime($now)||strtotime($date_end.' 07:00:00')==strtotime($now))
        {
            $show_flag = true;
        } else{
            $show_flag = false;
        }
        return view('manager.statManager.statAgent_detail',[
            'day_money_list'=> $day_money_list,
            'user_id' => $user_id,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
            'today_profit' => $today_profit,
            'show_flag' => $show_flag,
        ]);
    }
    public function day_agent_user(Request $request) {

        $time = $request->input('time');
        $user_id = $request->input('user_id');

        $day_money_list = Profit::query()->with('info')
                        ->with('user')
                        ->with('orderinfo')
                        ->with('agentuser')
                        ->with('agentinfo')
                        ->whereDate('created_at',$time)
                        ->where('agent_user_id',$user_id)->paginate(10);
        return view('manager.statManager.day_agent_user',[
            'day_money_list' => $day_money_list,
            'time' => $time,
            'user_id' => $user_id
        ]);
    }

    protected function getStatDate(Request $request)
    {
        if (date('H')<7) {
            $date_begin = $request->input('date_begin')?$request->input('date_begin'):date('Y-m-d', time()-24*60*60);
            $date_end = $request->input('date_end')?date('Y-m-d', strtotime($request->input('date_end')+24*60*60)):date('Y-m-d');
        } else {
            $date_begin = $request->input('date_begin')?$request->input('date_begin'):date('Y-m-d');
            $date_end = $request->input('date_end')?date('Y-m-d', strtotime($request->input('date_end'))+24*60*60):date('Y-m-d', time()+24*60*60);
        }
        return [$date_begin, $date_end];
    }

    public function order_ratio(Request $request){
        $time = $request->time?$request->time:1;
        $val  = $request->val;
        $user_id = $request->user_id;
        $shangji = $request->shangji;
        $xiaji   = $request->xiaji;

        $select = [
            DB::raw("sum(total) as total"),
            DB::raw("sum(betting) as betting"),
            DB::raw("sum(betNum) as betNum"),
            DB::raw("sum(winning) as winning"),
            DB::raw("sum(rebate) as rebate"),
            DB::raw("sum(recharge) as recharge"),
            DB::raw("sum(withdrawals) as withdrawals"),
            DB::raw("sum(dxds) as dxds"),
            DB::raw("sum(zh) as zh"),
            DB::raw("sum(xsdd) as xsdd"),
            DB::raw("sum(sz) as sz"),
            DB::raw("sum(jz) as jz"),
            DB::raw("sum(sb) as sb"),
            "userinfo.name",
            "user.username"
        ];
        $all_data = UserDailySettle::query()
            ->select($select)
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')
            ->leftJoin('userinfo','userinfo.user_id','user_daily_settle.user_id')
            ->where('user.username','!=','admin')
            ->where('user.is_fictitious','!=',1)
            ->where('user_daily_settle.total','!=',0)
            ->groupBy('user_daily_settle.user_id');

        if($shangji){
            $shangji = User::query()->where("username",$shangji)->value("user_id");
            $all_data   = $all_data->where("user_daily_settle.user_id",$shangji);
        }
        if($xiaji){
            $all_user_id = User::query()->where("parent_user_id",$xiaji)->pluck("user_id")->toArray();
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
            $all_data = $all_data->whereDate("user_daily_settle.create_time",">=",$start_time)
                ->whereDate("user_daily_settle.create_time","<=",$end_time);
        }

        $all_data = $all_data->paginate(20);

        return view('manager.statManager.orderRadio',[
            "all_data"  => $all_data,
            "time"      => $time,
            "val"       => $val,
            "user_id"   => $user_id,
            "start_time"   => $start_time,
            "end_time"   => $end_time,
        ]);
    }
}





