<?php

namespace App\Http\Controllers;

use App\Jobs\GamePeriod;
use App\Jobs\ODBACchunk;
use App\Jobs\OrderDataBackupAndClear;
use App\Models\Account;
use App\Models\Agentdownload;
use App\Models\BonusRecord;
use App\Models\BuyUser;
use App\Models\GameConfig;
use App\Models\OrderBackup;
use App\Models\Payoff;
use App\Models\Session;
use App\Models\UserDailySettle;
use App\Models\WageRecord;
use Illuminate\Http\Request;
use App\Http\Middleware\ManagerAuthMiddleware;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\IncomeDailySettle;
use App\Models\Order;
use App\Models\Game;
use App\Models\Player;
use App\Models\Profit;
use App\Models\Relation;
use App\Models\TradeRecord;
use App\Models\UserInfo;
use App\Models\Withdraw;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\Finder\SplFileInfo;

class ViewController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function main(Request $request)
    {
        return view('main');
    }


    /**
     * 作用：Admin控制面板信息
     * 作者：信
     * 时间：2018/4/18
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getmain(Request $request){
        /*今日统计*/
        $today_data = UserDailySettle::query()
            ->select(DB::raw('sum(betting) as betting'),DB::raw('sum(recharge) as recharge'),DB::raw('sum(withdrawals) as withdrawals'))
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')->where('user.is_fictitious','!=',1)
            ->where("user_daily_settle.create_time","=",date("Y-m-d",time()))
            ->first();
        if($today_data){
            $today_data['yinyu'] = $today_data['recharge'] - $today_data['withdrawals'];
        }

        /*昨日统计*/
        $yseterday_data = UserDailySettle::query()
            ->select(DB::raw('sum(betting) as betting'),DB::raw('sum(recharge) as recharge'),DB::raw('sum(withdrawals) as withdrawals'))
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')->where('user.is_fictitious','!=',1)
            ->where("user_daily_settle.create_time","=",date("Y-m-d",strtotime("-1 day")))
            ->first();
        $yseterday_data['yinyu'] = $yseterday_data['recharge'] - $yseterday_data['withdrawals'];
        /*累计统计*/
//        $select = [
//            DB::raw("sum(shouru) as shouru"),
//            DB::raw("sum(yinkui) as yinkui"),
//            DB::raw("sum(chongzhi) as chongzhi"),
//        ];

        $all_data = UserDailySettle::query()->select(DB::raw('sum(betting) as betting'),DB::raw('sum(recharge) as recharge'),DB::raw('sum(withdrawals) as withdrawals'))->leftJoin('user','user.user_id','user_daily_settle.user_id')->where('user.is_fictitious','!=',1)->first();
        $all_data['yinyu'] = $all_data['recharge'] - $all_data['withdrawals'];

        /*用户人数*/
        $user_count     = User::query()->count();
        /*代理人数*/
        $agent_number   = User::query()->where("role_id",2)->count();
        /*会员人数*/
        $huiyuan_number = User::query()->where("role_id",1)->count();
        /*昨日注册人数*/
        $yesterdat_people_number = UserInfo::query()
            ->whereDate("create_time",">=",date("Y-m-d 00:00:00",strtotime("-1 day")))
            ->whereDate("create_time","<=",date("Y-m-d 23:59:59",strtotime("-1 day")))
            ->leftJoin('user','user.user_id','userinfo.user_id')->where('user.is_fictitious','!=',1)
            ->count();
        /*今日注册人数*/
        $toady_people_number = UserInfo::query()
            ->whereDate("create_time",">=",date("Y-m-d 00:00:00",time()))
            ->whereDate("create_time","<=",date("Y-m-d 23:59:59",time()))
            ->leftJoin('user','user.user_id','userinfo.user_id')->where('user.is_fictitious','!=',1)
            ->count();

        /*今日中奖总额*/
      /*  $zhongjiang = Order::query()
            ->whereDate("lottery_time",">=",date("Y-m-d 00:00:00",time()))
            ->whereDate("lottery_time","<=",date("Y-m-d 23:59:59",time()))
            ->count("bonus");*/
        $user_today_data = UserDailySettle::query()
            ->select(DB::raw('sum(winning) as winning'),DB::raw('sum(betting) as betting' ),DB::raw('sum(recharge) as recharge'),DB::raw('sum(withdrawals) as withdrawals'))
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')->where('user.is_fictitious','!=',1)
            ->whereDate("create_time",date("Y-m-d",time()))->first();


        /*余额*/
        $yuer = Account::query()->leftJoin('user','user.user_id','account.user_id')->where('user.is_fictitious','!=',1)->sum("remaining_money");

        /*当前在线人数*/
        $zaixian_people = Session::query()->where("access_time",">=",time()-300)->count();



        /*彩种投注金额统计*/
        $select_arr = [
            DB::raw("sum(bet_money) as money"),
            "game.name"
        ];

        $game_data = Order::query()
            ->leftJoin("game","game.id","=","gameId")
            ->leftJoin('user','user.user_id','order.user_id')->where('user.is_fictitious','!=',1)
            ->select($select_arr)
            ->whereDate("lottery_time",">=",date("Y-m-d 00:00:00",time()))
            ->whereDate("lottery_time","<=",date("Y-m-d 23:59:59",time()))
            ->where('delete_time',0)
            ->groupBy("gameId")
            ->get()
            ->toArray();


        return view('manager.main',[
            "today_data"                => $today_data,
            "yseterday_data"            => $yseterday_data,
            "all_data"                  => $all_data,
            "user_count"                => $user_count,
            "agent_number"              => $agent_number,
            "huiyuan_number"            => $huiyuan_number,
            "yesterdat_people_number"   => $yesterdat_people_number,
            "toady_people_number"       => $toady_people_number,
            "yuer"                      => $yuer,
            "zaixian_people"            => $zaixian_people,
            "game_data"                => $game_data,
            'user_today_data'          => $user_today_data
        ]);
    }



    public function agentMain(Request $request)
    {
        $agent_id = auth('agent')->user()->user_id;
        $agent_user = auth('agent')->user()->username;
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
        ];
        $teamList = $this->getTeamUser($agent_id);
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
            "all_a"         => $all_a
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
    public function game_period()
    {
        $this->middleware('auth.agent:agent');
        return strtotime(date('Y-m-d').' 21:15:00') - strtotime(date('Y-m-d'));
        $config_list = GameConfig::query()->get();
        foreach ($config_list as $config) {
            $this->dispatch(new GamePeriod($config));
        }
        return 1;
    }

    public function test()
    {
        $day = date('Y-m-d').' 00:00:00';
        //$backup_order_list = Order::query()->where('order_dateTime', '<', $day)->get()->chunk(100)->toArray();
        //$result = OrderBackup::query()->insert($backup_order_list[0]);
        //Order::query()->where('order_dateTime', '<', $day)->delete();
//        $result = $this->dispatch(new OrderDataBackupAndClear());
        //$result = dispatch(new ODBACchunk($backup_order_list[0]));
        return var_dump($day);
    }
    public function download(Request $request) {
        $code = base64_decode($request->input('code'));
        return view('download.Game_download',[
            'code'=>$code
        ]);
    }
}
