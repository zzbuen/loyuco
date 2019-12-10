<?php

namespace App\Http\Controllers\Manager;
use App\Models\Account;
use App\Models\Agent;
use App\Models\Agentdownload;
use App\Models\AgentInfo;
use App\Models\Bonus;
use App\Models\Fandianset;
use App\Models\Game;
use App\Models\Order;
use App\Models\Loginlog;
use App\Models\Payoff;
use App\Models\System;
use App\Models\UserDailySettle;
use App\Models\Wage;
use App\Models\Session as UserSession;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\Profit;
use App\Models\Relation;
use App\Models\Admin;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Withdraw;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class agentManagerController  extends Controller
{
    /*代理列表*/
    public function agentCenter(Request $request) {
        set_time_limit(0);
        $time = $request->time?$request->time:1;
        $val  = $request->val;
        $select1 = [
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
            "user.dl_level",
            "user.username"
        ];
        /*快捷选时*/
//        if($time && !$val){
//            $near_time = $this->near_time($time);
//            $start_time = substr($near_time[0],0,10);
//            $end_time = substr($near_time[1],0,10);
//            $val = $start_time.' - '.$end_time;
//        }
        $reqq= UserDailySettle::query()
            ->select($select1)
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')
            ->leftJoin('account','account.user_id','user_daily_settle.user_id')
            ->where('user.is_fictitious','!=',1)
            ->where('user.parent_user_id','!=','admin');
        /*日期范围*/
        if(1){
//            $start_time = substr($val,0,10);
//            $end_time = substr($val,13);
            $start_time = Carbon::now()->startOfMonth();
            $end_time = Carbon::now()->endOfMonth();
            $reqq = $reqq->whereDate("user_daily_settle.create_time",">=",$start_time)
                ->whereDate("user_daily_settle.create_time","<=",$end_time);

        }


        $reqq=$reqq->first()->toArray();

        $user_id = $request->user_id;
        $data = User::query()
            ->select(["user_id","username","user_state","parent_user_id","dl_level","agent_url"])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","parent_user_id","create_time")->with("shangji");
            }])
            ->where("role_id",2)
            ->where('username','!=','admin');
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data = $data->where("user_id",$user_id_select);
        }
        $data = $data->orderBy("id","desc")->paginate(10);
        foreach ($data as $key=>$value){
            $teamList = $this->getTeamUser($value["user_id"]);
            $data[$key]["teamNum"] = count($teamList);
            $data[$key]["money"] =  Account::query()->whereIn("user_id",$teamList)->sum('remaining_money');
        }


        return view('manager.agentManager.agentCenter',["data"=>$data,"user_id"=>$user_id,'reqq'=>$reqq,
            'time'=>$time,"val"=> $val]);
    }
    public function agentCenter2(Request $request) {
        set_time_limit(0);
        $time = $request->time?$request->time:1;
        $val  = $request->val;
        $select1 = [
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
            "user.dl_level",
            "user.username"
        ];
        /*快捷选时*/
//        if($time && !$val){
//            $near_time = $this->near_time($time);
//            $start_time = substr($near_time[0],0,10);
//            $end_time = substr($near_time[1],0,10);
//            $val = $start_time.' - '.$end_time;
//        }
        $reqq= UserDailySettle::query()
            ->select($select1)
            ->leftJoin('user','user.user_id','user_daily_settle.user_id')
            ->leftJoin('account','account.user_id','user_daily_settle.user_id')
            ->where('user.is_fictitious','!=',1);
//            ->where('user.parent_user_id','!=','admin');
        /*日期范围*/
        if($val){
            $start_time = substr($val,0,10);
            $end_time = substr($val,13);
//            $start_time = Carbon::now()->startOfMonth();
//            $end_time = Carbon::now()->endOfMonth();
            $reqq = $reqq->whereDate("user_daily_settle.create_time",">=",$start_time)
                ->whereDate("user_daily_settle.create_time","<=",$end_time);

        }


        $reqq=$reqq->first()->toArray();

        $user_id = $request->user_id;
        $data = User::query()
            ->select(["user_id","username","user_state","parent_user_id","dl_level"])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","parent_user_id","create_time")->with("shangji");
            }])
            ->where("role_id",2)
            ->where('parent_user_id','admin')
            ->where('dl_level',1)
            ->where('username','!=','admin');
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data = $data->where("user_id",$user_id_select);
        }
        $data = $data->orderBy("id","desc")->paginate(10);
        foreach ($data as $key=>$value){
            $start_time = Carbon::now()->startOfMonth();
            $end_time = Carbon::now()->endOfMonth();
            $teamList = $this->getTeamUser($value["user_id"]);
            $data[$key]["teamNum"] = count($teamList);
            $data[$key]["money"] =  Account::query()->whereIn("user_id",$teamList)->sum('remaining_money');
            $user_res= UserDailySettle::query()
                ->select($select1)
                ->leftJoin('user','user.user_id','user_daily_settle.user_id')
                ->leftJoin('account','account.user_id','user_daily_settle.user_id')
                ->where('user.is_fictitious','!=',1)
                ->where('user.parent_user_id','!=','admin')
                ->whereDate("user_daily_settle.create_time",">=",$start_time)
                ->whereDate("user_daily_settle.create_time","<=",$end_time)
                ->whereIn("user.user_id",$teamList)->first()->toArray();



            $data[$key]["user_res"]=$user_res;
        }


        return view('manager.agentManager.agentCenter2',["data"=>$data,"user_id"=>$user_id,'reqq'=>$reqq,
            'time'=>$time,"val"=> $val]);
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
    /**
     * 作用：团队人数
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @param $user_id
     * @return string
     */
    public function getChildrenIds ($user_id){
        $ids = '';
        $result = UserInfo::query()->where("parent_user_id",$user_id)->get(["user_id"])->toArray();
        if ($result){
            foreach ($result as $key=>$val) {
                $ids .= ','.$val["user_id"];
                $ids .= $this->getChildrenIds ($val["user_id"]);
            }
        }
        return $ids;
    }


    /**
     * 作用：所有下级名称
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @param $user_id
     * @return string
     */
    public function getChildrenName ($user_id){
        $names = '';
        $result = UserInfo::query()->where("parent_user_id",$user_id)->get(["user_id"])->toArray();
        if ($result){
            foreach ($result as $key=>$val) {
                $select_name = User::query()->where("user_id",$val["user_id"])->first(["username"]);
                if($select_name){
                    $names .= ','.$select_name["username"];
                }else{
                    $names .="";
                }
                $names .= $this->getChildrenName ($val["user_id"]);
            }
        }
        return $names;
    }



    /**
     * 作用：团队金额
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @param $user_id
     * @return string
     */
//    public function getMoney ($user_id){
//        $money = 0;
//        $result = UserInfo::query()->where("parent_user_id",$user_id)->get(["user_id"])->toArray();
//        if ($result){
//            foreach ($result as $key=>$val) {
//                $mo = Account::query()->where("user_id",$val["user_id"])->pluck("remaining_money")->first();
//                $money += $mo;
//                $money += $this->getMoney($val["user_id"]);
//            }
//        }
//        return $money;
//    }


    /**
     * 作用：更改代理登陆状态
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function agent_state(Request $request){
        $all = $request->all();
        $user_id = $all["user_id"];
        $state = $all["state"]==1?2:1;
        $res = Agent::query()->where("user_id",$user_id)->update(["login_state"=>$state]);
        if($res){
            return ["code"=>1,"msg"=>"操作成功"];
        }
        return ["code"=>0,"msg"=>"操作失败"];
    }


    /**
     * 作用：跳到添加代理页面
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_agents(){
        $data = System::all()->toArray();
        return view("manager.agentManager.add_agent",["data"=>$data]);
    }


    /**
     * 作用：跳到修改代理信息页面
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function change_agent(Request $request){
        $user_id = $request->user_id;
        $user_name = User::query()->where("user_id",$user_id)->first(["username","parent_user_id"])->toArray();
        $data = System::all()->toArray();
        if($user_name["parent_user_id"]){
            $user_data = Fandianset::query()->where("user_id",$user_name["parent_user_id"])->first()->toArray();
            $data[6]["value"] =$user_data["fanDian"];
            $data[7]["value"] =$user_data["bFanDian"];
        }
//        $wage = Wage::query()->where("user_id",$user_id)->first();
//        $bonus = Bonus::query()->where("user_id",$user_id)->first();

        $fandian_set = Fandianset::query()->where("user_id",$user_id)->first();

        return view("manager.agentManager.change_agent",[
            "data"      => $data,
//            "wage"      => $wage,
//            "bonus"     => $bonus,
            "fandian_set"=> $fandian_set,
            "user_name"    => $user_name,
        ]);
    }


    /**
     * 作用：添加代理ajax
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function add_agent_ajax(Request $request){
        $user_id = $this->getUserRand();
        $data = $request->all();
        $username_isset = User::query()->where("username",$data["username"])->first();
        if($username_isset){
            return ["code"=>0,"msg"=>"添加失败，该用户名已存在"];
        }
        DB::beginTransaction();
        try{
            $data['is_fictitious'] = 0;
            $data['dl_level'] = 2;
            $data['agent_url'] = "";
            if($data['type']==2){
                $data['agent_url'] = "https://api.mayi8.me/H5/html/agent_res.html?type=2&dl_level=2&dl_userid=".$user_id;

                $data['is_fictitious'] = 1;//虚拟

            }
            if($data['type']==3){
                $data['is_fictitious'] = 1;//虚拟
                $data['type'] = 1;//会员
            }elseif ($data['type']==4){
                $data['is_fictitious'] = 1;//虚拟
                $data['dl_level'] = 1;//1级代理
                $data['type'] = 2;//代理
                $data['agent_url'] = "https://api.mayi8.me/H5/html/agent_res.html?type=2&dl_level=1&dl_userid=".$user_id;

            }
            if(!$this->add_user($user_id,$data)){
                throw new \Exception("user添加失败");
            }
            if(!$this->add_userinfo($user_id,$data)){
                throw new \Exception("userinfo添加失败");
            }
            if(!$this->add_account($user_id,$data)){
                throw new \Exception("account用户余额添加失败");
            }
            if(!$this->add_user_daily_settle($user_id)){
                throw new \Exception("user_daily_settle添加失败");
            }
            if(!$this->add_session($user_id)){
                throw new \Exception("user_session添加失败");
            }

            DB::commit();
            return ["code"=>1,"msg"=>"添加成功，点击确定完成账号密码复制"];
        }catch (\Exception $exception){
            DB::rollBack();
            return ["code"=>$exception->getCode(),"msg"=>$exception->getMessage()];
        }
    }


    /**
     * 作用：修改代理信息
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_agent_ajax(Request $request){
        $user_id = $request->user_id;
        $data = $request->all();

        DB::beginTransaction();
        try{
            if(!$this->change_fandain_set($user_id,$data)){
                throw new \Exception("反点修改失败");
            }
            DB::commit();
            return ["code"=>1,"msg"=>"修改成功"];
        }catch (\Exception $exception){
            DB::rollBack();
            return ["code"=>$exception->getCode(),"msg"=>$exception->getMessage()];
        }

    }


    /**
     * 作用：生成user_id
     * 作者：信
     * 时间：2018/4/19
     * 修改：暂无
     * @return int|string
     */
    public function getUserRand(){
        $rand_number = "";
        for ($j=0;$j<7;$j++) {
            if($j==0){
                $rand_number = rand(1, 9);
            }else{
                $rand_number = $rand_number . rand(0, 9);
            }
        }
        $res = User::query()->where("user_id",$rand_number)->first();
        if($res){
            $rand_number = $this->getUserRand();
        }
        return $rand_number;
    }


    /**
     * 作用：User添加
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_user($user_id,$data){
        $user_data = [
            "username"      => $data["username"],
            "password"      => bcrypt($data["password"]),//Hash::make($data["password"]) ,
            "user_id"       => $user_id,
            "parent_user_id"=> 'admin',
            "role_id"       => $data["type"],
            "agent_url"       => $data["agent_url"],
            "user_state"    => 1,
            "is_fictitious"    => $data['is_fictitious'],
            "update_time"   => date("Y-m-d H:i:s",time()),
            "dl_level"=> $data['dl_level']
        ];

        $user_res = User::query()->insert($user_data);
        if($user_res){
            return true;
        }
        return false;
    }


    /**
     * 作用：Userinfo添加
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_userinfo($user_id,$data){
        $user_data = [
            "nickname"      => $data["username"],
            "user_id"       => $user_id,
            "parent_user_id"=> 0,
            "update_time"   => date("Y-m-d H:i:s",time()),
            "create_time"   => date("Y-m-d H:i:s",time()),
            "last_login_time"   => date("Y-m-d H:i:s",time()),
            "last_login_ip"     => $_SERVER["REMOTE_ADDR"],
            'register_ip'=>$_SERVER["REMOTE_ADDR"],
            'head_img'=>'https://api.mayi8.me/storage/head1.png'
        ];

        $res = UserInfo::query()->insert($user_data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：Account用户账户余额添加
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_account($user_id,$data){
        $user_data = [
            "user_id"               => $user_id,
            "unliquidated_money"    => 0,
           /* "withdraw_pwd"          => Hash::make($data["password"]) ,*/
            "update_time"           => date("Y-m-d H:i:s",time()),
        ];

        $res = Account::query()->insert($user_data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：fandain_set添加
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_fandain_set($user_id,$data){
        $user_data = [
            "user_id"    => $user_id,
            "fanDian"    => $data["fandian"],
            "bFanDian"   => $data["befandain"] ,
            "created_at" => date("Y-m-d H:i:s",time()),
            "updated_at" => date("Y-m-d H:i:s",time()),
        ];

        $res = Fandianset::query()->insert($user_data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：用户每日盈亏结算添加一个当天的
     * 作者：信
     * 时间：2018/5/14
     * 修改：暂无
     * @param $user_id
     */
    public function add_user_daily_settle($user_id){
        $data["user_id"]    = $user_id;
        $data["create_time"]    = date("Y-m-d",time());
        $res = UserDailySettle::query()->insert($data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：添加session表
     * 作者：信
     * 时间：2018/5/15
     * 修改：暂无
     * @param $user_id
     * @return bool
     */
    public function add_session($user_id){
        $data["user_id"]        = $user_id;
        $data["access_time"]    = 0;
        $res = UserSession::query()->insert($data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：fandain_set修改
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function change_fandain_set($user_id,$data){
        $arr = [
            "fanDian"   => $data["fandian"],
            "bFanDian"  => $data["befandain"],
            "updated_at"=> date("Y-m-d H:i:s",time())
        ];
        $res = Fandianset::query()->where("user_id",$user_id)
            ->update($arr);

        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：wage添加日工资契约
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_wage($user_id,$data){
        $user_data = [
            "user_id"       => $user_id,
            "agent_id"      => 0,
            "amount_day"    => $data["amount_day"] ,
            "amount_num"    => $data["amount_num"],
            "amount_money"  => $data["amount_money"],
            "wage_ratio"    => $data["wage_ratio"],
            "status"        => 2,
            "sort"          => time(),
            "create_time"   => date("Y-m-d H:i:s",time()),
        ];

        $res = Wage::query()->insert($user_data);
        if($res){
            return true;
        }
        return false;
    }



    /**
     * 作用：wage修改日工资契约
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function change_wage($user_id,$data){
        $user_data = [
            "amount_day"    => $data["amount_day"] ,
            "amount_num"    => $data["amount_num"],
            "amount_money"  => $data["amount_money"],
            "wage_ratio"    => $data["wage_ratio"],
            "update_time"   => date("Y-m-d H:i:s",time()),
        ];

        $res = Wage::query()->where("user_id",$user_id)->update($user_data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：bonus添加分红契约
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_bonus($user_id,$data){
        $user_data = [
            "user_id"       => $user_id,
            "agent_id"      => 0,
            "total"         => $data["total"] ,
            "amount_num"    => $data["amount_num_fenhon"],
            "amount_money"  => $data["amount_money_fenhon"],
            "bonus_ratio"   => $data["bonus_ratio"],
            "bonus_type"    => $data["bonus_type"],
            "status"        => 2,
            "create_time"   => date("Y-m-d H:i:s",time()),
        ];

        $res = Bonus::query()->insert($user_data);
        if($res){
            return true;
        }
        return false;
    }


    /**
     * 作用：bonus修改分红契约
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function change_bonus($user_id,$data){
        $user_data = [
            "total"         => $data["total"] ,
            "amount_num"    => $data["amount_num_fenhon"],
            "amount_money"  => $data["amount_money_fenhon"],
            "bonus_ratio"   => $data["bonus_ratio"],
            "bonus_type"    => $data["bonus_type"],
            "update_time"   => date("Y-m-d H:i:s",time()),
        ];

        $res = Bonus::query()->where("user_id",$user_id)->update($user_data);
        if($res){
            return true;
        }
        return false;
    }

    
    /**
     * 作用：agent添加代理表
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_agent($user_id,$data){
        $user_data = [
            "user_id"       => $user_id,
            "username"      => $data["username"],
            "password"      => Hash::make($data["password"]) ,
            "parent_id"     => 0,
            "created_at"   => date("Y-m-d H:i:s",time()),
        ];

        $res = Agent::query()->insert($user_data);
        if($res){
            return true;
        }
        return false;
    }



    public function agent_loginState(Request $request)
    {
        $user_id = $request->input('user_id');
        $login_state = $request->input('login_state');
        $agent =Agent::query()->where('user_id',$user_id);
        $up_arr = ['login_state'=>$login_state];
        if(!$agent->update($up_arr)){
            $retrun_arr = ['flag' => false, 'msg' => '状态修改失败，服务器出错'];
            return $retrun_arr;
        }
        $retrun_arr = ['flag' => true, 'msg' => '状态修改成功'];
        return $retrun_arr;
    }
    public function editAgent(Request $request)
    {
        $user_id = $request->input('user_id');
        $agent_list = AgentInfo::query()->where('user_id' ,$user_id)->with('relation')->get()->toArray();
        return view('manager.agentManager.editAgent',[
            'agent_list' => $agent_list
        ]);
    }
    public function modifyAgent_ajax(Request $request)
    {
        $share = $request->input('share');
        $user_id = $request->input('user_id');
        $invitation_num = $request->input('invitation_num');
        $old_invitation = Relation::query()->where('user_id', $request->input('user_id'))->first();
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
        if (!is_numeric($invitation_num)) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，邀请码请输入整数'];
            return $retrun_arr;
        }
        if ($invitation_num < 0) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，邀请码不能低于0'];
            return $retrun_arr;
        }
        if ($invitation_num > 99999) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，邀请码不能大于99999'];
            return $retrun_arr;
        }
        $relation_exist = Relation::query()->where('invitation_num', $invitation_num)->where('user_id','<>', $user_id)->first();
        if (!empty($relation_exist)) {
            $retrun_arr = ['flag' => false, 'msg' => '邀请码已存在，请重新输入'];
            return $retrun_arr;
        }

        try {
            DB::begintransaction();
            $up_arr = ['share_percent'=>$share];
            $agent = AgentInfo::query()->where('user_id',$user_id)->update($up_arr);
            if (!$agent) {
                throw new \Exception('分润修改失败');
            }
            $relation_update = Relation::query()->where('user_id', $user_id)->update(['invitation_num'=>$invitation_num]);
            if ($relation_update === false) {
                throw new \Exception('邀请码修改失败');
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return ['flag' => false,'msg'=>$e->getMessage()];
        }
        if ($old_invitation['invitation_num']!=$invitation_num) {
            $agent = Agentdownload::query()->where('user_id', $request->input('user_id'));
            if (!$agent->delete()) {
                $retrun_arr = ['flag' => false, 'msg' => '删除二维码信息失败'];
                return $retrun_arr;
            }
        }
        $retrun_arr = ['flag' => true, 'msg' => '修改成功'];
        return $retrun_arr;
    }

    public function agent_children(Request $request)
    {
        $leader_id = $request->input('leader_id');
        $user_list = BuyUser::query()
            ->with('agent')
            ->select(['userinfo.create_time','userinfo.mobile_number',
                'account.remaining_money','account.unliquidated_money',
                'user.username', 'user.user_id', 'user.role_id',
                'userinfo.parent_user_id', 'user.id',DB::raw('sum(t_caimi_order.bet_money) as order_bet'),
                DB::raw('sum(result) as order_resule')])
            ->leftJoin('userinfo', 'user.user_id', '=', 'userinfo.user_id')
            ->leftJoin('role', 'user.role_id', '=', 'role.id')
            ->leftJoin('order', 'user.user_id', '=', 'order.user_id')
            ->leftJoin('account','user.user_id','=','account.user_id')
            ->where('userinfo.parent_user_id',$leader_id);
        $login_list = Loginlog::query()->groupBy('user_id')
                    ->orderBy('login_date','desc')->get()->keyBy('user_id')->toArray();
        if ($request->input('user_id')) {
            $user_list = $user_list->where('userinfo.user_id', $request->input('user_id'));
        }
        switch ($request->input('column')) {
            case 'order_resule':
                $order = 'order_resule';
                break;
            case 'unliquidated_money':
                $order = 'unliquidated_money';
                break;
            case 'remaining_money':
                $order = 'remaining_money';
                break;
            case 'order_bet':
                $order = 'order_bet';
                break;
            default:
                $order = 'user.id';
                break;
        }

        $user_list = $user_list ->groupBy('user.user_id')
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);
        $parent_userid_array = array_filter(array_column($user_list->toArray()['data'], 'parent_user_id'));

        $parent_userinfo = BuyUser::query()
            ->leftJoin('userinfo', 'user.user_id', '=', 'userinfo.user_id')
            ->whereIn('user.user_id', $parent_userid_array)
            ->get()->keyBy('user_id')->toArray();
        $parent_userinfo[235689] = ['name' => '系统'];

        return view('manager.agentManager.agent_children', [
            'user_list' => $user_list,
            'parent_userinfo' => $parent_userinfo,
            'login_list' => $login_list
        ]);
    }
    public function profit_detail(Request $request) {
        $agent_user_id = $request->input('leader_id');

        $profit_list = Profit::query()->with('info')
                     ->leftJoin('user','user.user_id','profit.user_id')
                     ->leftJoin('userinfo','userinfo.user_id','profit.user_id')
                     ->where('agent_user_id',$agent_user_id);

        if($request->input('order_sn')) {
            $profit_list->where('order.order_sn',$request->input('order_sn'));
        }
        if($request->input('user_id')) {
            $profit_list->where('profit.user_id',$request->input('user_id'));
        }
        if($request->input('date_begin')) {
            $profit_list->whereDate('profit.created_at','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $profit_list->whereDate('profit.created_at','<=' ,$request->input('date_end'));
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
            case 'created_at':
                $order = 'profit.created_at';
                break;
            default:
                $order = 'profit.id';
                break;
        }
        $profit_list = $profit_list
                     ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
                     ->paginate(10);
        return view('manager.agentManager.profit_detail', [
            'profit_list' => $profit_list
        ]);
    }

    public function agent_profit(Request $request) {
        $leader_id = 235689;
        $today = Carbon::now()->toDateString();
        $day_start = date('Y-m-d') . ' 07:00:00';
        $profit_list = Profit::query()
                    ->select(['order_sn','agent_user_id','profit.user_id','userinfo.name','profit.order_amount','profit_percent','profit.created_at','login_date','profit_amount'])
                    ->leftJoin('user','user.user_id','=','profit.user_id')
                    ->leftJoin('userinfo','user.user_id','=','userinfo.user_id')
                    ->leftJoin('order','profit.order_id','=','order.id')
                    ->leftJoin('login_log','login_log.user_id','=','order.user_id')
                    ->where('leader_user_id',$leader_id)
                    ->groupBy('profit.id');

        if($request->input('agent_user_id')) {
            $profit_list->where('agent_user_id',$request->input('agent_user_id'));
        }
        if($request->input('order_sn')) {
            $profit_list->where('order.order_sn',$request->input('order_sn'));
        }
        if($request->input('user_id')) {
            $profit_list->where('profit.user_id',$request->input('user_id'));
        }
        if($request->input('date_begin')) {
            $profit_list->whereDate('profit.created_at','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $profit_list->whereDate('profit.created_at','<=' ,$request->input('date_end'));
        }
        if (empty($request->input('date_begin')) && empty($request->input('date_end'))) {
            $profit_list->where('profit.created_at', '>=',$day_start);
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
            case 'created_at':
                $order = 'profit.created_at';
                break;
            case 'login_date':
                $order = 'login_date';
                break;
            default:
                $order = 'profit.id';
                break;
        }
        $profit_list = $profit_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);
        $agent_info = BuyUser::query()->with('info')->get()->keyBy('user_id')->toArray();

        return view('manager.agentManager.agent_profit', [
            'profit_list' => $profit_list,
            'agent_list' => $agent_info
        ]);
    }

    public function agent_profit_history(Request $request) {
        $leader_id = 235689;
        $today = Carbon::now()->toDateString();
        $day_start = date('Y-m-d') . ' 07:00:00';
        $profit_list = Profit::query()
            ->select(['profit.id','order_sn','agent_user_id','profit.user_id','userinfo.name','profit.order_amount','profit_percent','profit.created_at','login_date','profit_amount'])
            ->leftJoin('user','user.user_id','=','profit.user_id')
            ->leftJoin('userinfo','user.user_id','=','userinfo.user_id')
            ->leftJoin('order_backup','profit.order_id','=','order_backup.id')
            ->leftJoin('login_log','login_log.user_id','=','order_backup.user_id')
            ->where('leader_user_id',$leader_id)
            ->groupBy('profit.id');

        if($request->input('agent_user_id')) {
            $profit_list->where('agent_user_id',$request->input('agent_user_id'));
        }
        if($request->input('order_sn')) {
            $profit_list->where('order_backup.order_sn',$request->input('order_sn'));
        }
        if($request->input('user_id')) {
            $profit_list->where('profit.user_id',$request->input('user_id'));
        }
        if($request->input('date_begin')) {
            $profit_list->whereDate('profit.created_at','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $profit_list->whereDate('profit.created_at','<=' ,$request->input('date_end'));
        }
        if (empty($request->input('date_begin')) && empty($request->input('date_end'))) {
            $profit_list->where('profit.created_at', '<',$day_start);
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
            case 'created_at':
                $order = 'profit.created_at';
                break;
            case 'login_date':
                $order = 'login_date';
                break;
            default:
                $order = 'profit.id';
                break;
        }
        $profit_list = $profit_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);
        $agent_info = BuyUser::query()->with('info')->get()->keyBy('user_id')->toArray();

        return view('manager.agentManager.agent_profit_history', [
            'profit_list' => $profit_list,
            'agent_list' => $agent_info
        ]);
    }
    public function withdraw_verify(Request $request) {
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->with('info')->where('leader_id',235689)->where('status',0)->paginate(10);
        return view('manager.userManager.withdraw_verify',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }
    public function withdraw_paying() {
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->with('info')->where('leader_id',235689)->where('status',1)->paginate(10);
        return view('manager.userManager.withdraw_paying',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }
    public function withdraw_payed() {
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->orderBy('id', 'desc')
                        ->with('info')->where('leader_id',235689)->where('status',2)->paginate(10);
        return view('manager.userManager.withdraw_payed',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }
    public function withdraw_back() {
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->orderBy('id', 'desc')
                        ->with('info')->where('leader_id',235689)->where('status',3)->paginate(10);
        return view('manager.userManager.withdraw_back',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }


    public function verify_excel(Request $request) {
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->with('info')->where('leader_id',235689)->where('status',0)->get()->toArray();
        $up_arr = ['status'=>1];
        $up_sql = Withdraw::query()->where('leader_id',235689)->where('status',0)->update($up_arr);
        $data_list = [];
        $data_list[] = [
            '用户ID',
            '用户名',
            '提现订单号',
            '提现金额',
            '申请时间',
            '汇款银行',
            '汇款账号'
        ];
        foreach ($withdraw_list as $item){
            $data_list[] = [
                $item['user_id'],
                $item['info']['name'],
                $item['withdraw_sn'],
                $item['amount'],
                $item['created_at'],
                $item['userbank']['bankname']['bank_name'].$item['userbank']['bank_branch'].'支行',
                $item['userbank']['account'],
            ];
        }

        $title = '提现审核'.Carbon::now()->toDateString();
        $final_title = iconv('UTF-8', 'GBK', $title.'|'.date('Ymd'));

        Excel::create($final_title, function ($excel) use ($data_list) {
            $excel->sheet('提现审核', function($sheet) use($data_list) {
                $sheet->fromArray($data_list);
            });
        })->export('xls');
    }
    public function changeStatus_ajax(Request $request) {
        $withdraw_sn = $request->input('withdraw_sn');
        $change_sql = Withdraw::query()->where('withdraw_sn',$withdraw_sn)->update(['status'=>1]);
        $retrun_arr = ['flag' => true, 'msg' => '转处理成功'];
        return $retrun_arr;
    }
    public function backStatus_ajax(Request $request) {
        $withdraw_sn = $request->input('withdraw_sn');
        $withdraw_sql = Withdraw::query()->where('withdraw_sn',$withdraw_sn)->get()->keyBy('leader_id')->toArray();

        $amount_money = $withdraw_sql['235689']['amount'];
        $u_id = $withdraw_sql['235689']['user_id'];

        $agent_iist = AgentInfo::query()->where('user_id',$u_id)->get()->toArray();
        $valid_profit = $agent_iist[0]['valid_profit']+$amount_money;
        $expend_profit = $agent_iist[0]['expend_profit']-$amount_money;
        $up_arr = ['valid_profit' => $valid_profit,'expend_profit'=>$expend_profit];
        $withdraw_up = ['status'=>3];

        try {
            DB::begintransaction();
            $agent_info = AgentInfo::query()->where('user_id',$u_id);
            $withdraw_info = Withdraw::query()->where('withdraw_sn',$withdraw_sn);
            if(!$agent_info->update($up_arr)) {
                throw new \Exception("用户信息变更失败，请重试");
            }
            if(!$withdraw_info->update($withdraw_up)) {
                throw new \Exception("用户信息变更失败，请重试");
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['flag' => false, 'msg' => $e->getMessage()];
        }
        $retrun_arr = ['flag' => true, 'msg' => '撤销成功'];
        return $retrun_arr;
    }
    public function get_excel(Request $request) {
        $withdraw_status = $request->input('withdraw_type');
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->with('info')->where('leader_id',235689)->where('status',$withdraw_status)->get()->toArray();
        $data_list = [];
        $data_list[] = [
            '用户ID',
            '用户名',
            '提现订单号',
            '提现金额',
            '申请时间',
            '汇款银行',
            '汇款账号'
        ];
        foreach ($withdraw_list as $item){
            $data_list[] = [
                $item['user_id'],
                $item['info']['name'],
                $item['withdraw_sn'],
                $item['amount'],
                $item['created_at'],
                $item['userbank']['bankname']['bank_name'].$item['userbank']['bank_branch'].'支行',
                $item['userbank']['account'],
            ];
        }
        if($withdraw_status==2) {
            $ti = '已提现';
        } elseif($withdraw_status==1) {
            $ti = '付款中';
        } else {
            $ti = '已撤销';
        }
        $title = $ti .Carbon::now()->toDateString();
        $final_title = iconv('UTF-8', 'GBK', $title.'|'.date('Ymd'));
        Excel::create($final_title, function ($excel) use ($data_list) {
            $excel->sheet('提现审核', function($sheet) use($data_list) {
                $sheet->fromArray($data_list);
            });
        })->export('xls');
    }
    public function payChange_status_ajax(Request $request) {
        $withdraw_sn = $request->input('withdraw_sn');
        $status = $request->input('status');
        $change_sql = Withdraw::query()->where('withdraw_sn',$withdraw_sn)->update(['status'=>$status]);
        if($status==2) {
            $retrun_arr = ['flag' => true, 'msg' => '转提现成功'];
            return $retrun_arr;
        } else {
            $retrun_arr = ['flag' => true, 'msg' => '转审核成功'];
            return $retrun_arr;
        }

    }
    public function submit_excel(Request $request){
        if (!$request->file('over_excel')->isValid()) {
            return ['success' => false, 'msg'=>'文件上传失败'];
        }

        if ($request->file('over_excel')->getClientOriginalExtension() != 'xls') {
            return ['success' => false, 'msg'=>'请上传后缀为.xls的excel文件'];
        }

        $path = $request->file('over_excel')->storeAs('inport', date('YmdHis').'-'.uniqid().'.xls');
        $result = Excel::load(Storage::url('app/'.$path))->get();
        $withdraw_sn_list = [];
        foreach ($result as $key=>$v) {
            if($key){
                $withdraw_sn_list[] = $v[2];
            }
        }
        if (Withdraw::query()->whereIn('withdraw_sn', $withdraw_sn_list)->where('status',1)->update(['status'=>2])) {
            return ['success' => true, 'msg'=>'导入成功'];
        }
        return ['success' => false, 'msg'=>'服务器错误，请重试'];
    }
    public function getAgent_detail(Request $request)
    {

        $agent_id= $request->input('agent_id');
        $agent_list = Agent::query()->with('info')->where('user_id',$agent_id)->get()->toArray();
        $user_info_list = UserInfo::query()->where('user_id',$agent_id)->get()->toArray();
        $relation_list = Relation::query()->where('user_id',$agent_id)->get()->toArray();
        $team_acount = UserInfo::query()
                     ->select([DB::raw('count(t_caimi_userinfo.parent_user_id) as team_acount'),])
                     ->where('parent_user_id',$agent_id)->get()->toArray();
        $agent_count = Agent::query() ->select([DB::raw('count(t_caimi_agent.leader_id) as agent_acount')])
                     ->where('leader_id',$agent_id)
                     ->get()->toArray();

        return view('manager.agentManager.getAgent_detail',[
            'agent_list'=> $agent_list,
            'user_info_list' => $user_info_list,
            'relation_list' => $relation_list,
             'team_acount' => $team_acount,
             'agent_acount' => $agent_count
        ]);
    }
    public function second_agent(Request $request) {
        $user_id = $request->input('agent_id');
        $agent_list = Agent::query()
            ->select(['userinfo.create_time as re_time','relation.invitation_num','userinfo.parent_user_id',DB::raw('count(t_caimi_userinfo.parent_user_id) as children_user'),
                'agent.id', 'agent.user_id','agent.username','agent.created_at','agent_info.share_percent',
                'agent_info.valid_profit','agent_info.totle_profit'])
            ->where('agent.leader_id' ,$user_id)
            ->leftJoin('agent_info','agent.user_id','=','agent_info.user_id')
            ->leftJoin('userinfo','agent.user_id','=','userinfo.parent_user_id')
            ->leftJoin('relation','relation.user_id','=','agent.user_id')
            ->groupBy('agent.user_id');
        if($request->input('user_id')) {
            $agent_list = $agent_list->where('agent.user_id' ,$request->input('user_id'));
        }
        if($request->input('username')) {
            $agent_list = $agent_list->where('agent.username' ,$request->input('username'));
        }
        switch ($request->input('column')) {
            case 'share_percent':
                $order = 'share_percent';
                break;
            case 'children_user':
                $order = 'children_user';
                break;
            case 'totle_profit':
                $order = 'totle_profit';
                break;
            case 'valid_profit':
                $order = 'valid_profit';
                break;
            case 'children_user':
                $order = 'children_user';
                break;
            default:
                $order = 'created_at';
                break;
        }

        $agent_list =  $agent_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(15);

        $children_userid_array = array_filter(array_column($agent_list->toArray()['data'], 'user_id'));
        $children_list = Agent::query()->select(['leader_id',DB::raw('count(t_caimi_agent.leader_id) as people')])
            ->whereIn('leader_id',$children_userid_array)
            ->groupBy('leader_id')
            ->get()->keyBy('leader_id')
            ->toArray();

        foreach ($agent_list as $k=>$agent) {
            $children_users = UserInfo::query()->where('parent_user_id', $agent['user_id'])->get(['user_id'])->toArray();
            $history_settle = Payoff::query()->whereIn('user_id', $children_users)->sum('result');
            $today_settle = Order::query()->whereIn('user_id', $children_users)->sum('result');
            $agent_list[$k]['group_settle'] = $history_settle + $today_settle;
        }

        return view('manager.agentManager.second_agent',[
            'agent_list' => $agent_list,
            'children_list' => $children_list,
        ]);
    }


    /**
     * 作用：签订工资契约
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function qianding_gongzi(Request $request){
        $user_id        = $request->user_id;
        $username       = User::query()->where("user_id",$user_id)->first(["username"]);
        $parent_user_id = User::query()->where("user_id",$user_id)->with("shangji")->first(["parent_user_id"]);

        if(!$parent_user_id["parent_user_id"]){
            $data   = System::query()->get()->toArray();
            $gongzi = $data[8]["value"];
            $parent_user_id = 0;
        }else{
            $data   = Wage::query()->where(["user_id"=>$parent_user_id["parent_user_id"],"status"=>1])->first();
            $gongzi = $data["wage_ratio"];
            $parent_user_id = $parent_user_id["shangji"]["username"];
        }

        return view("manager.agentManager.qianding_gongzi",[
            "user_id"          => $user_id,
            "username"         => $username,
            "parent_user_id"   => $parent_user_id,
            "gongzi"           => $gongzi,
        ]);
    }



    /**
     * 作用：ajax_签订契约
     * 作者：信
     * 时间：2018/5/2
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_qianding_gongzi(Request $request){
        $data = $request->all();
        $arr = [
            "user_id"       => $data["user_id"],
            "agent_id"      => $data["parent_user_id"],
            "amount_day"    => $data["riliang"],
            "amount_num"    => $data["renshu"],
            "amount_money"  => $data["jiner"],
            "wage_ratio"    => $data["bili"],
            "status"        => 2,
            "create_time"   => date("Y-m-d H:i:s",time()),
        ];
        $res = Wage::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"签订日工资请求已发出,等待对方确定"];
        }
        return ["code"=>0,"msg"=>"签订日工资请求异常，请稍后再试"];
    }




    /**
     * 作用：签订分红契约
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function qianding_fenhon(Request $request){
        $user_id        = $request->user_id;
        $username       = User::query()->where("user_id",$user_id)->first(["username"]);
        $parent_user_id = User::query()->where("user_id",$user_id)->with("shangji")->first(["parent_user_id"]);

        if(!$parent_user_id["parent_user_id"]){
            $data   = System::query()->get()->toArray();
            $fenhon = $data[9]["value"];
            $parent_user_id = 0;
        }else{
            $data   = Bonus::query()->where(["user_id"=>$parent_user_id["parent_user_id"],"status"=>1])->first();
            $fenhon = $data["bonus_ratio"];
            $parent_user_id = $parent_user_id["shangji"]["username"];
        }

        return view("manager.agentManager.qianding_fenhon",[
            "user_id"          => $user_id,
            "username"         => $username,
            "parent_user_id"   => $parent_user_id,
            "fenhon"           => $fenhon,
        ]);
    }





    /**
     * 作用：ajax_签订分红契约
     * 作者：信
     * 时间：2018/5/2
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_qianding_fenhon(Request $request){
        $data = $request->all();
        $arr = [
            "user_id"       => $data["user_id"],
            "agent_id"      => $data["parent_user_id"],
            "total"    => $data["riliang"],
            "amount_num"    => $data["renshu"],
            "amount_money"  => $data["jiner"],
            "bonus_ratio"    => $data["bili"],
            "bonus_type"    => $data["celue"],
            "status"        => 2,
            "create_time"   => date("Y-m-d H:i:s",time()),
        ];
        $res = Bonus::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"签订分红契约请求已发出,等待对方确定"];
        }
        return ["code"=>0,"msg"=>"签订分红契约请求异常，请稍后再试"];
    }


    /**
     * 作用：ajax_签订分红契约
     * 作者：信
     * 时间：2018/5/2
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_change_fenhon(Request $request){
        $data = $request->all();
        $arr = [
            "user_id"       => $data["user_id"],
            "agent_id"      => $data["parent_user_id"],
            "total"         => $data["riliang"],
            "amount_num"    => $data["renshu"],
            "amount_money"  => $data["jiner"],
            "bonus_ratio"   => $data["bili"],
            "bonus_type"    => $data["celue"],
            "status"        => 1,
        ];
        $res = Bonus::query()->where($arr)->first();
        if($res){
            return ["code"=>0,"msg"=>"分红契约要求不变，重新签订失败"];
        }
        $arr = [
            "user_id"       => $data["user_id"],
            "agent_id"      => $data["parent_user_id"],
            "total"         => $data["riliang"],
            "amount_num"    => $data["renshu"],
            "amount_money"  => $data["jiner"],
            "bonus_ratio"    => $data["bili"],
            "bonus_type"    => $data["celue"],
            "status"        => 4,
            "create_time"   => date("Y-m-d H:i:s",time()),
        ];
        $res = Bonus::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"修改分红契约请求已发出,等待对方确定"];
        }
        return ["code"=>0,"msg"=>"修改分红契约请求异常，请稍后再试"];
    }




    /**
     * 作用：修改分红契约
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function change_fenhon(Request $request){
        $user_id        = $request->user_id;
        $username       = User::query()->where("user_id",$user_id)->first(["username"]);
        $parent_user_id = User::query()->where("user_id",$user_id)->with("shangji")->first(["parent_user_id"]);
        $old_fenhon     = Bonus::query()->where(["user_id"=>$user_id,"status"=>1])->first();

        if(!$parent_user_id["parent_user_id"]){
            $data   = System::query()->get()->toArray();
            $fenhon = $data[9]["value"];
            $parent_user_id = 0;
        }else{
            $data   = Bonus::query()->where(["user_id"=>$parent_user_id["parent_user_id"],"status"=>1])->first();
            $fenhon = $data["bonus_ratio"];
            $parent_user_id = $parent_user_id["shangji"]["username"];
        }

        return view("manager.agentManager.change_fenhon",[
            "user_id"          => $user_id,
            "username"         => $username,
            "parent_user_id"   => $parent_user_id,
            "fenhon"           => $fenhon,
            "old_fenhon"       => $old_fenhon
        ]);
    }



    /**
     * 作用：修改工资契约
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function change_gongzi(Request $request){
        $user_id        = $request->user_id;
        $username       = User::query()->where("user_id",$user_id)->first(["username"]);
        $parent_user_id = User::query()->where("user_id",$user_id)->with("shangji")->first(["parent_user_id"]);
        $old_gongzi     = Wage::query()->where(["user_id"=>$user_id,"status"=>1])->first();

        if(!$parent_user_id["parent_user_id"]){
            $data   = System::query()->get()->toArray();
            $gongzi = $data[8]["value"];
            $parent_user_id = 0;
        }else{
            $data   = Wage::query()->where(["user_id"=>$parent_user_id["parent_user_id"],"status"=>1])->first();
            $gongzi = $data["wage_ratio"];
            $parent_user_id = $parent_user_id["shangji"]["username"];
        }

        return view("manager.agentManager.change_gongzi",[
            "user_id"          => $user_id,
            "username"         => $username,
            "parent_user_id"   => $parent_user_id,
            "gongzi"           => $gongzi,
            "old_gongzi"       => $old_gongzi,
        ]);
    }



    /**
     * 作用：查看工资契约
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_gongzi(Request $request){
        $user_id        = $request->user_id;
        $parent_user_id = UserInfo::query()->where("user_id",$user_id)->first(["parent_user_id"])->toArray();
        $parent_user_name = User::query()->where("user_id",$parent_user_id["parent_user_id"])->first(["username"])->toArray();
        $username       = User::query()->where("user_id",$user_id)->first(["username"]);
        $old_gongzi     = Wage::query()->where(["user_id"=>$user_id,"status"=>1])->first();

        return view("manager.agentManager.look_gongzi",[
            "user_id"          => $user_id,
            "username"         => $username,
            "parent_user_name"   => $parent_user_name,
            "old_gongzi"       => $old_gongzi,
        ]);
    }



    /**
     * 作用：查看分红契约
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_fenhon(Request $request){
        $user_id        = $request->user_id;
        $parent_user_id = UserInfo::query()->where("user_id",$user_id)->first(["parent_user_id"])->toArray();
        $parent_user_name = User::query()->where("user_id",$parent_user_id["parent_user_id"])->first(["username"])->toArray();
        $username       = User::query()->where("user_id",$user_id)->first(["username"]);
        $old_fenhon     = Bonus::query()->where(["user_id"=>$user_id,"status"=>1])->first();

        return view("manager.agentManager.look_fenhon",[
            "user_id"          => $user_id,
            "username"         => $username,
            "parent_user_name"   => $parent_user_name,
            "old_fenhon"       => $old_fenhon
        ]);
    }



    /**
     * 作用：ajax_修改契约
     * 作者：信
     * 时间：2018/5/2
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_change_gongzi(Request $request){
        $data = $request->all();
        $arr = [
            "user_id"       => $data["user_id"],
            "agent_id"      => $data["parent_user_id"],
            "amount_day"    => $data["riliang"],
            "amount_num"    => $data["renshu"],
            "amount_money"  => $data["jiner"],
            "wage_ratio"    => $data["bili"],
            "status"        => 1,
        ];
        $res = Wage::query()->where($arr)->first();
        if($res){
            return ["code"=>0,"msg"=>"日工资契约要求不变，重新签订失败"];
        }
        $res = Wage::query()->where(["user_id"=>$data["user_id"],"status"=>1])->first();
        if($res){
            $sort = $res["sort"];
        }else{
            $sort = time();
        }
        $arr = [
            "user_id"       => $data["user_id"],
            "agent_id"      => $data["parent_user_id"],
            "amount_day"    => $data["riliang"],
            "amount_num"    => $data["renshu"],
            "amount_money"  => $data["jiner"],
            "wage_ratio"    => $data["bili"],
            "status"        => 4,
            "sort"          => $sort,
            "create_time"   => date("Y-m-d H:i:s",time()),
        ];
        $res = Wage::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"修改日工资请求已发出,等待对方确定"];
        }
        return ["code"=>0,"msg"=>"修改日工资请求异常，请稍后再试"];
    }
    /**
     * 作用：添加虚拟账户
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function add_fictitious_ajax(Request $request){
        $user_id = $this->getUserRand();
        $data = $request->all();
        $username_isset = User::query()->where("username",$data["username"])->first();
        if($username_isset){
            return ["code"=>0,"msg"=>"添加失败，该用户名已存在"];
        }
        DB::beginTransaction();
        try{
            if(!$this->add_user_xvni($user_id,$data)){
                throw new \Exception("user添加失败");
            }
            if(!$this->add_userinfo($user_id,$data)){
                throw new \Exception("userinfo添加失败");
            }
            if(!$this->add_account($user_id,$data)){
                throw new \Exception("account用户余额添加失败");
            }
            if(!$this->add_fandain_set($user_id,$data)){
                throw new \Exception("fandain_set反点添加失败");
            }
            if(!$this->add_user_daily_settle($user_id)){
                throw new \Exception("user_daily_settle添加失败");
            }
            if(!$this->add_session($user_id)){
                throw new \Exception("user_session添加失败");
            }

            DB::commit();
            return ["code"=>1,"msg"=>"添加成功，点击确定完成账号密码复制"];
        }catch (\Exception $exception){
            DB::rollBack();
            return ["code"=>$exception->getCode(),"msg"=>$exception->getMessage()];
        }
    }
    /**
     * 作用：User添加
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param $user_id
     * @param $data
     * @return bool
     */
    public function add_user_xvni($user_id,$data){
        $user_data = [
            "username"      => $data["username"],
            "password"      => Hash::make($data["password"]) ,
            "user_id"       => $user_id,
            "parent_user_id"=> 0,
            "role_id"       => $data["type"],
            "user_state"    => 1,
            "update_time"   => date("Y-m-d H:i:s",time()),
            'is_fictitious' => 1
        ];

        $user_res = User::query()->insert($user_data);
        if($user_res){
            return true;
        }
        return false;
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
                $startTime  =   strtotime(date('Y-m-d 00:00:00', time()));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*昨天*/
            case 2:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-1 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', strtotime("-1 day",time())));
                break;
            /*近三天 */
            case 3:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-3 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*近七天 */
            case 7:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-7 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*近半月 */
            case 15:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-15 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*近一月 */
            case 30:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-30 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
        }
        return [$startTime,$endTime];
    }
}





