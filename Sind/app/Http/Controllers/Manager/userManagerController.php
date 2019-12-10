<?php

namespace App\Http\Controllers\Manager;
use App\Classes\goole;
use App\Models\newrecharge;
use App\Models\offline_recharge;
use App\Classes\Scc;
use App\Classes\UpdateAccount;
use App\Jobs\OpenPrize;
use App\Models\Account;
use App\Models\Accountstatus;
use App\Models\Active;
use App\Models\Bank;
use App\Models\Bonus;
use App\Models\BonusRecord;
use App\Models\Fandianset;
use App\Models\Game;
use App\Models\Journalaccount;
use App\Models\Loginlog;
use App\Models\Odds;
use App\Models\Order;
use App\Models\level;
use App\Models\SecurityAnswer;
use App\Models\System;
use App\Models\UserBank;
use App\Models\Wage;
use App\Models\WageRecord;
use App\Models\Session as Sessionuser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\Relation;
use App\Models\UserDailySettle;
use App\Models\TradeRecord;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\DrawResultAnhui;
use App\Models\DrawResultBeijingkl8;
use App\Models\DrawResultBeijingpk;
use App\Models\DrawResultFenfen3d;
use App\Models\DrawResultFenfenpk;
use App\Models\DrawResultFucai3d;
use App\Models\DrawResultGuangdong;
use App\Models\DrawResultJiangsu;
use App\Models\DrawResultJiangxi;
use App\Models\DrawResultLiuhecai;
use App\Models\DrawResultPailie3;
use App\Models\DrawResultPcdd;
use App\Models\DrawResultShandong;
use App\Models\DrawResultShanghai;
use App\Models\DrawResultChongqing;
use App\Models\DrawResultTianjin;
use App\Models\DrawResultXinjiang;
use App\Models\DrawResultTengxun;
use App\Models\DrawResultOuzhou;
use App\Models\DrawResultBeijing;
use App\Models\DrawResultHanguo;
use App\Models\DrawResultXinjiapo;
use App\Models\DrawResultHenei;
use Illuminate\Support\Facades\Log;


class userManagerController  extends Controller
{
    public function asd(Request $request){

        $ga = new goole();

//创建一个新的"安全密匙SecretKey"
//把本次的"安全密匙SecretKey" 入库,和账户关系绑定,客户端也是绑定这同一个"安全密匙SecretKey"
        $secret = "54W2TDUURZBEXUJI";
        echo "安全密匙SecretKey: ".$secret."\n\n";

        $qrCodeUrl = $ga->getQRCodeGoogleUrl('www.2asp.cn', $secret); //第一个参数是"标识",第二个参数为"安全密匙SecretKey" 生成二维码信息
        echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n"; //Google Charts接口 生成的二维码图片,方便手机端扫描绑定安全密匙SecretKey

        $oneCode = $ga->getCode($secret); //服务端计算"一次性验证码"
        echo "服务端计算的验证码是:".$oneCode."\n\n";

//把提交的验证码和服务端上生成的验证码做对比
// $secret 服务端的 "安全密匙SecretKey"
// $oneCode 手机上看到的 "一次性验证码"
// 最后一个参数 为容差时间,这里是2 那么就是 2* 30 sec 一分钟.
// 这里改成自己的业务逻辑
        $code=123;
        $checkResult = $ga->verifyCode($secret, $code, 1);
        if ($checkResult) {
            echo '匹配! OK';
        } else {
            echo '不匹配! FAILED';
        }
        exit;
    }
    public function goole(Request $request){
//        if($request->isMethod("POST") || $request->ajax()){
//            return ["code"=>0,"msg"=>"请求异常，请稍后再试"];
//        }
        $goole=$request->goole;
        return view('manager.login', [
            'goole'   => $goole

        ]);
    }
    public function cs(Request $request){
        $order_list = Order::query()->where('order_id',$request->id)->first();
        $data = $order_list;
        if($data['status']!=0){
            return ['success' => false, 'msg' => '该订单已派奖过'];
        }
        $model = getTheGameTable($data['gameId']);
        $modelResult = $model->where('periods',$data['bet_period'])->value('result');
        if(!$modelResult){
            return ['success' => false, 'msg' => '当期还未开奖'];
        }
        $lotteryNo = $request->lo;
        $Scc = new Scc();
        $fun_kj = 'kj_'.substr($data['serial_num'],2);
        $winNum =  $Scc->$fun_kj($lotteryNo,$data['position']-1,$data['bet_value']);
        $Odds = Odds::query()->where('serial_num',$data['serial_num']);
        $odds = $Odds->value('odds'.$data['room_type']);
        if($winNum == '0' ){
            $winBonus = 0;
        }
        elseif ($winNum == 2){
            $winBonus = 0;
        }
        else{
            /*等级等于投注金额 * 赔率*/
            /*这里要判断一下开奖结果是不是13 或者 14*/
            if($data['gameId'] == 4||$data['gameId'] == 5){
                $kj = explode(',',$lotteryNo);
                sort($kj);
                $kjNum = $kj[0]+$kj[1]+$kj[2];
                if($kjNum==13||$kjNum==14){
                    $newSerial = substr($data['serial_num'],2);
                    if($newSerial=='030101'||$newSerial=='030102'||$newSerial=='030103'||$newSerial=='030104'
                        ||$newSerial=='030105'||$newSerial=='030106'||$newSerial=='030107'||$newSerial=='030108'||$newSerial=='030109'||$newSerial=='030110'){
                        if($data['room_type']==4){
                            $odds = 1;
                        }else{
                            $odds = 0;
                            $winNum = 0;
                        }
                    }
                }
                if($kj[0]==$kj[2]||$kj[0]==$kj[1]||$kj[1]==$kj[2]||$kj[0]+2==$kj[2]){
                    $newSerial = substr($data['serial_num'],2);
                    if($newSerial=='030101'||$newSerial=='030102'||$newSerial=='030103'||$newSerial=='030104'
                        ||$newSerial=='030105'||$newSerial=='030106'||$newSerial=='030107'||$newSerial=='030108'||$newSerial=='030109'||$newSerial=='030110'){
                        if($data['room_type']==4){
                            $odds = 1;
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
        try {
            DB::beginTransaction();
            /*修改order表 开奖状态 中奖金额 中奖注数  开奖时间*/
            $result = Order::query()->where('order_id', $data['order_id'])->update([
                'status' => 1,
                'result' => $orderResult,
                'lotteryNo' => $lotteryNo,
                'zjCount' => $winNum,
                'bonus' => $winBonus,
                'update_time' => now()->toDateTimeString(),
                'lottery_time' => now()->toDateTimeString()
            ]);
            if (!$result) {
                throw new \Exception('更新订单表失败');
            }

            if ($winNum > 0) {
                /*中奖的话 给用户打钱*/
                $old_money = Account::query()->where('user_id', $data['user_id'])->value('remaining_money');
                $result = Account::query()->where('user_id', $data['user_id'])->increment('remaining_money', $winBonus);
                if (!$result) {
                    throw new \Exception('更新余额表失败,添加这么多钱:' . $winBonus . '注数:' . $winNum);
                }
                /*获取用户原金额*/
                $result = $UpdateStatus->updateStatus($data['user_id'], '中奖', $winBonus, $old_money, $data['order_id']);
                if (!$result) {
                    throw new \Exception('更新账变表失败');
                }
            }
            /*
             * 需要判断是否停止追号
             * 所有剩下的订单 都变成撤单状态
             * 计算需退款的总金额 记录账变和今日盈利记录
            */
            if ($data['zhuiHaoMode'] != 0 && $winNum > 0) {
                $oldUserMoney = Account::query()->where('user_id', $data['user_id'])->value('remaining_money');
                $sumBetMoney = Order::query()
                    ->where('status', 0)
                    ->where('user_id', $data['user_id'])
                    ->where('zhuiHaoMode', $data['zhuiHaoMode'])
                    ->sum('bet_money');
                Order::query()
                    ->where('status', 0)
                    ->where('user_id', $data['user_id'])
                    ->where('zhuiHaoMode', $data['zhuiHaoMode'])
                    ->update(['delete_time' => Carbon::now()->toDateTimeString()]);
                Account::query()->where('user_id', $data['user_id'])->increment('remaining_money', $sumBetMoney);
                $UpdateStatus->updateStatus($data['user_id'], '撤单', $sumBetMoney, $oldUserMoney);
            }
            Log::info('开奖处理:开奖成功');
            DB::commit();
            return ['success' => true, 'msg' => '派奖成功,奖金为'.$winBonus];
        }  catch(\Exception $e)
        {
            Log::info('开奖处理:开奖失败'.$e->getMessage());
            DB::rollBack();
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    public function xiufu(){
        $a = exec("dir");
        echo "<br>-----------------------------------------------------<br>";
        echo "<pre>";
        print_r($a);
        echo "</pre>";
        echo "<br>-----------------------------------------------------<br>";
    }

    public function getUser(Request $request){
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

        $data =  User::query()
            ->leftJoin("account","user.user_id","=","account.user_id")
            ->leftJoin("userinfo","userinfo.user_id","=","user.user_id")
            ->where("user.username","!=","admin");
//            ->where("user.role_id","1");
        $pId = $request->pId;
        if($pId){
            $team = BuyUser::query()->where('parent_user_id',$pId)->pluck('username');
            $data = $data->whereIn("user.username",$team);
        }
        /*用户名*/
        $user_name = $request->user_name;
        if($user_name){
            $data = $data->where("user.username",$user_name)->orWhere('user.user_id',$user_name);
        }


        /*用户状态*/
        $user_state = $request->user_state;
        if($user_state=="2"){
            //$data = $data->whereBetween("user.user_state",[0,1]);
        }
        if($user_state=="0" || $user_state=="1") {
            $data = $data->where("user.is_fictitious",$user_state);
        }

        /*用户状态*/
        $is_fictitious = $request->is_fictitious;
        if($is_fictitious=="2"){
        }
        if($is_fictitious=="0" || $is_fictitious=="1") {
            $data = $data->where("user.is_fictitious",$is_fictitious);
        }
        if($is_fictitious=="3"){
            $data = $data->where("user.role_id",2);

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

        return view('manager.userManager.index',[
            "data"      => $data,
            "user_id"   => $user_name,
            "user_state"    => $user_state,
            "is_fictitious" => $is_fictitious,
            "order"         => $order,
            "name"          => $name,
        ]);
    }



    /*更换代理*/
    public function modAgent(Request $request){
        $agent = $request->agentId;
        $user_id = $request->userId;
        $username = BuyUser::query()->where('username',$agent)->first();
        if(!$username){
            return ["code"=>0,"msg"=>"该用户名不存在"];
        }
        $username = BuyUser::query()->where('username',$agent)->where('role_id',2)->first();
        if(!$username){
            return ["code"=>0,"msg"=>"该用户不是代理"];
        }
        $username = BuyUser::query()->where('username',$agent)->where('dl_level',2)->first();
        if(!$username){
            return ["code"=>0,"msg"=>"该用户不是二级代理"];
        }
        $agentUserId = BuyUser::query()->where('username',$agent)->value('user_id');

        DB::beginTransaction();
        try{
            $res =   BuyUser::query()->where('user_id',$user_id)->update([
                'parent_user_id'=>$agent,
                'update_time'=>Carbon::now()->toDateTimeString()
            ]);
            if (!$res) {
                throw new \Exception("修改用户表失败");
            }
            $res =     Userinfo::query()->where('user_id',$user_id)->update([
                'parent_user_id'=>$agentUserId,
                'update_time'=>Carbon::now()->toDateTimeString()
            ]);
            if (!$res) {
                throw new \Exception("修改用户详情表失败");
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollback();
            return ["code" => 0,"msg"=>$e->getMessage()];
        }
        return ["code"=>1,"msg"=>"修改代理成功"];
    }

    /*更换代理*/
    public function modAgent_one(Request $request){
        $agent = $request->agentId;
        $user_id = $request->userId;
        $username = BuyUser::query()->where('username',$agent)->first();
        if(!$username){
            return ["code"=>0,"msg"=>"该用户名不存在"];
        }
        $username = BuyUser::query()->where('username',$agent)->where('role_id',2)->first();
        if(!$username){
            return ["code"=>0,"msg"=>"该用户不是代理"];
        }
        $username = BuyUser::query()->where('username',$agent)->where('dl_level',1)->first();
        if(!$username){
            return ["code"=>0,"msg"=>"该用户不是一级代理"];
        }
        $agentUserId = BuyUser::query()->where('username',$agent)->value('user_id');

        DB::beginTransaction();
        try{
            $res =   BuyUser::query()->where('user_id',$user_id)->update([
                'parent_user_id'=>$agent,
                'update_time'=>Carbon::now()->toDateTimeString()
            ]);
            if (!$res) {
                throw new \Exception("修改用户表失败");
            }
            $res =     Userinfo::query()->where('user_id',$user_id)->update([
                'parent_user_id'=>$agentUserId,
                'update_time'=>Carbon::now()->toDateTimeString()
            ]);
            if (!$res) {
                throw new \Exception("修改用户详情表失败");
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollback();
            return ["code" => 0,"msg"=>$e->getMessage()];
        }
        return ["code"=>1,"msg"=>"修改代理成功"];
    }
    /**
     * 作用：用户详情
     * 作者：信
     * 时间：2018/4/9
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUser_detail(Request $request){
        $user_id    = $request->input('user_id');
        /*用户基本信息*/
        $user_detail = BuyUser::query()
            ->with("account")
            ->with('info')
            ->where('user_id', $user_id)
            ->get()
            ->toArray();

        /*密保*/
        $question = SecurityAnswer::query()
            ->with("question")
            ->where("user_id",$user_id)
            ->get()
            ->toArray();

        $parent_user_id = $user_detail[0]["parent_user_id"];
        if($parent_user_id == 0){
            $parent_fandain = System::query()->get()->toArray();
            $parent_fandain_msg["gao"]  =   $parent_fandain[6]["value"];
            $parent_fandain_msg["di"]  =    $parent_fandain[7]["value"];
        }else{
            $parent_fandain = Fandianset::query()->where("user_id",$parent_user_id)->first();
            $parent_fandain_msg["gao"]  =   $parent_fandain["fanDian"];
            $parent_fandain_msg["di"]  =    $parent_fandain["bFanDian"];
        }

        $self_fandain = Fandianset::query()->where("user_id",$user_id)->first();
        $bank       = Bank::all()->toArray();
        return view('manager.userManager.getUser_detail', [
            'user_detail'   => $user_detail,
            'user_id'       => $user_id,
            "fandain"       => $self_fandain,
            "parent_fandain_msg" => $parent_fandain_msg,
            "question"      => $question,
            "bank"         => $bank
        ]);
    }


    /**
     * 作用：修改真实姓名
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_name(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $name       = $data["name"];
        $res = UserInfo::query()->where("user_id",$user_id)->update(["name"=>$name]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，真实姓名修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，真实姓名修改成功！"];
    }


    /**
     * 作用：修改邮箱
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_email(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $email      = $data["email"];
        $res = UserInfo::query()->where("user_id",$user_id)->update(["email"=>$email]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，邮箱信息修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，邮箱信息修改成功！"];
    }




    /**
     * 作用：修改手机号
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_mobile_number(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $mobile_number      = $data["phone"];
        $res = UserInfo::query()->where("user_id",$user_id)->update(["mobile_number"=>$mobile_number]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，手机号修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，手机号修改成功！"];
    }



    /**
     * 作用：修改高频彩返点
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function fandain_gao(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $fandain_gao      = $data["fandain_gao"];
        $res = Fandianset::query()->where("user_id",$user_id)->update(["fanDian"=>$fandain_gao]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，高频彩返点修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，高频彩返点修改成功！"];
    }



    /**
     * 作用：修改六合彩返点
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function fandain_di(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $fandain_di      = $data["fandain_di"];
        $res = Fandianset::query()->where("user_id",$user_id)->update(["bFanDian"=>$fandain_di]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，六合彩返点修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，六合彩返点修改成功！"];
    }


    /**
     * 作用：修改用户密码
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_pwd(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $user_pwd      = $data["user_pwd"];
        $user_pwd   = Hash::make($user_pwd);
        $res = User::query()->where("user_id",$user_id)->update(["password"=>$user_pwd]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，密码修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，密码修改成功！"];
    }



    /**
     * 作用：修改用户资金密码
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function zijin_pwd(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $user_pwd   = $data["zijin_pwd"];
        $user_pwd   = Hash::make($user_pwd);
        $res = Account::query()->where("user_id",$user_id)->update(["withdraw_pwd"=>$user_pwd]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，资金密码修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，资金密码修改成功！"];
    }

    /*修改用户银行卡信息*/
    public function mod_bankinfo(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $res = UserInfo::query()->where("user_id",$user_id)->update([
            "bank_name"=>$data['bank_name'],
            "bank_account_name"=>$data['bank_account_name'],
            "bank_account"=>$data['bank_account'],
            'update_time'=>Carbon::now()->toDateTimeString()
        ]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，银行卡信息修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，银行卡信息修改成功！"];
    }



    /**
     * 作用：升级代理
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function shengji(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $res = User::query()->where("user_id",$user_id)->update(["role_id"=>2]);
        if(!$res){
            return ["code"=>0,"msg"=>"升级失败，请稍后再试"];
        }
        return ["code"=>1,"msg"=>"恭喜您，升级代理操作成功"];
    }


    /**
     * 作用：修改密保问题
     * 作者：信
     * 时间：2018/6/29
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_mibao(Request $request){
        $data       = $request->all();
        $id         = $data["id"];
        $answer     = $data["answer"];
        $res = SecurityAnswer::query()->where("id",$id)->update(["answer"=>$answer]);
        if(!$res){
            return ["code"=>0,"msg"=>"未做修改"];
        }
        return ["code"=>1,"msg"=>"恭喜你，修改成功"];
    }


    /**
     * 作用：用户统计数据
     * 作者：信
     * 时间：2018/4/26
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tongji(Request $request){
        $user_id = $request->id;
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
        $userDailySettle = UserDailySettle::query()->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->first();
        if(!$userDailySettle) {
            $result = UserDailySettle::query()->insert([
                'user_id' =>$user_id,
                'create_time'=>Carbon::now()->toDateString(),
                'update_time'=>Carbon::now()->toDateString()
            ]);
            if(!$result){
                return api_response(false,'','该用户无法插入盈利记录');//错误
            }
        }
        $data = UserDailySettle::query()
            ->select($select)
            ->where("user_id",$user_id)
            ->first()
            ->toArray();

        return view("manager.tongji",[
            "user_data"     => $user_data,
            "data"          => $data,
        ]);
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


    /**
     * 作用：投注管理[今日投注]
     * 作者：信
     * 时间：2018/4/10
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOrder(Request $request) {
        $data = Order::query()->with("user")
            ->with("info")
            ->with("odds")
            ->with("account")
            ->where("zhuiHao",0)
            ->where("delete_time",0)
            ->whereDate("order_dateTime",">=",date("Y-m-d 00:00:00",time()))
            ->whereDate("order_dateTime","<=",date("Y-m-d 23:59:59",time()));

        /*订单号*/
        if($request->input("order_id")){
            $data = $data->where("order.order_id",$request->input("order_id"));
        }
        /*用户名*/
        if($request->input("user_id")){
            $user_id = User::query()->where("username",$request->input("user_id"))->first(["user_id"]);
            if($user_id){
                $user_id = $user_id["user_id"];
            }else{
                $user_id = "";
            }
            $data = $data->where("order.user_id",$user_id);
        }
        /*期号*/
        if($request->input("bet_period")){
            $data = $data->where("order.bet_period",$request->input("bet_period"));
        }
        /*游戏类型*/
        if($request->input('game_id')) {
            $data = $data->where('order.gameId',$request->input('game_id'));
        }
        /*投注金额排序*/
        $order = $request->input('order');
        if($order){
            $data = $data->orderBy("order.bet_money",$order);
        }
        $datasum       = $data->leftJoin('user','user.user_id','order.user_id')->where('user.is_fictitious',0)->orderBy("order.order_dateTime","desc")->get()->toArray();
        $data       = $data->orderBy("order.order_dateTime","desc")->paginate(10);
        $orderBetMoney = 0;
        $orderBetZ = 0;
        foreach ($datasum as $item){
            $orderBetMoney += $item['bonus'];
            $orderBetZ += $item['bet_money'];
        }

        $game_list  = Game::query()->get()->toArray();
        $return_arr = [
            'data'      => $data,
            'game_list' => $game_list,
            "game_id"   => $request->input('game_id'),
            "user_id"   => $request->input("user_id"),
            "order"     => $order,
            "orderBetMoney"     => $orderBetMoney,
            "orderBetZ"     => $orderBetZ,
        ];
        return view('manager.userManager.getOrder',$return_arr);
    }


    /**
     * 作用：查看投注内容
     * 作者：信
     * 时间：2018/6/14
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_neiron(Request $request){
        $id = $request->id;
        $data = Order::query()->with("user")
            ->with("info")
            ->with("odds")
            ->where("id",$id)
            ->first();
        $file = '';
        $isFile = Order::query()->where('order.id',$id)->value('isFile');
        if($isFile){
            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Content-type: application/x-www-form-urlencoded\r\n".
                        "Content-length:".strlen($data)."\r\n" .
                        "Cookie: foo=bar\r\n" .
                        "\r\n",
                    'content' => $data,
                )
            );
            $cxContext = stream_context_create($opts);
            $file = file_get_contents('http://www.fl10010.com/'.$isFile,false,$cxContext);
        }
        return view("manager.userManager.look_neiron",[
            "data"  => $data,'isFile'=>$isFile,'file'=>$file
        ]);
    }




    /**
     * 作用：撤单
     * 作者：信
     * 时间：2018/4/10
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function backout_order(Request $request){
        $id     = $request->input("id")?$request->input("id"):0;
        $order = Order::query()->where("id",$id)->first();
        $user_id    = $order["user_id"];
        $bet_money  = $order["bet_money"];
        $tran_num   = $order["tran_num"];
        $user_money = Account::query()->where("user_id",$user_id)->first();
        $user_money = $user_money["remaining_money"];

        if($order->lotteryNo){
            return ["code"=>0,"msg"=>"撤单失败,该注单已开奖"];
        }
        DB::beginTransaction();
        try{
            $res = Account::query()->where("user_id",$user_id)->increment("remaining_money",$bet_money);
            if (!$res) {
                throw new \Exception("系统异常，退还用户金额失败");
            }
            $arr = [
                "user_id"=>$user_id,
                "tran_num"=>$tran_num,
                "old_money"=>$user_money,
                "change_status"=>8,
                "change_money"=>$bet_money,
                "bet_money"=>$user_money+$bet_money,
                "remarks"=>"系统撤单",
                "create_time"=>date("Y-m-d H:i:s",time())
            ];
            $res = Journalaccount::query()->insertGetId($arr);
            if(!$res){
                throw new \Exception("系统异常，用户账变信息更改失败");
            }
            $userDailySettle = UserDailySettle::query()->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->first();
            if(!$userDailySettle) {
                $result = UserDailySettle::query()->insert([
                    'user_id' =>$user_id,
                    'create_time'=>Carbon::now()->toDateString(),
                    'update_time'=>Carbon::now()->toDateString()
                ]);
                if(!$result){
                    return api_response(false,'','该用户无法插入盈利记录');//错误
                }
            }
            $res = UserDailySettle::query()->where(["user_id"=>$user_id,"create_time"=>date("Y-m-d",time())])->decrement("betting",$bet_money);
            if (!$res) {
                throw new \Exception("系统异常，用户盈亏信息更改失败");
            }
            $res = UserDailySettle::query()->where(["user_id"=>$user_id,"create_time"=>date("Y-m-d",time())])->increment("total",$bet_money);
            if (!$res) {
                throw new \Exception("系统异常，用户盈亏信息更改失败");
            }

            $res = Order::query()->where("id",$id)->update(["delete_time"=>time()]);
            if(!$res){
                throw new \Exception("系统异常，撤单失败");
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollback();
            return ["code" => 0,"msg"=>$e->getMessage()];
        }
        return ["code"=>1,"msg"=>"撤单成功"];
    }


    /**
     * 作用：修改订单显示信息
     * 作者：信
     * 时间：2018/4/10
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modification_order(Request $request){
        $id = $request->input("id")?$request->input("id"):0;
        $data = Order::query()
            ->with("user")
            ->with("info")
            ->with("odds")
            ->where("order.id",$id)
            ->first()
            ->toArray();
        $fandian = System::query()->where("key","fandian_limit")->first()->toArray()["value"];
        return view("change_order.modification_order",["data"=>$data,"fandian"=>$fandian]);
    }


    /**
     * 作用：修改订单投注内容
     * 作者：信
     * 时间：2018/6/14
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_order_betvalue(Request $request){
        $data   = $request->all();
        $id     = $data["id"];
        $bet_value = $data["bet_value"];
        $res    = Order::query()->where("id",$id)->update(["bet_value"=>$bet_value]);
        if($res){
            return ["code"=>1,"msg"=>"恭喜您，订单投注内容信息修改成功！"];
        }
        return ["code"=>0,"msg"=>"投注内容信息未作修改！"];
    }














    /**
     * 作用：修改订单信息
     * 作者：信
     * 时间：2018/4/11
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function do_modification_order(Request $request){
        if(!$request->isMethod("POST") || !$request->ajax()){
            return ["code"=>0,"msg"=>"请求异常，请稍后再试"];
        }
        $data   = $request->all();
        $id     = $data["id"];
        unset($data["id"]);
        $res     = Order::query()->where("id",$id)->update($data);
        if($res){
            return ["code"=>1,"msg"=>"修改成功"];
        }
        return ["code"=>0,"msg"=>"修改失败，请检查信息是否正确"];
    }


    /**
     * 作用：投注管理[历史投注]
     * 作者：信
     * 时间：2018/4/11
     * 修改：暂无
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOrder_back(Request $request) {
        $data = Order::query()
            ->select('*','order.id as id')
            ->with("user")
            ->with("info")
            ->with("odds")
            ->leftJoin('user','user.user_id','=','order.user_id')
            ->where('user.is_fictitious',0);
        //->with("account")
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

        //$datasum    = $data->orderBy("order_dateTime","desc")->get()->toArray();
        $datasum = [];
        $orderBetMoney = 0;
        $orderBetZ = 0;
        foreach ($datasum as $item){
            $orderBetMoney += $item['bonus'];
            $orderBetZ += $item['bet_money'];
        }
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
        return view('manager.userManager.getOrder_back',$return_arr);
    }






    /**
     * 作用：投注管理[撤单记录]
     * 作者：信
     * 时间：2018/4/11
     * 修改：暂无
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOrder_chedan(Request $request) {
        $data = Order::query()
            ->with("user")
            ->with("info")
            ->with("odds")
            /*  ->where("zhuiHao",0)*/
            ->where("delete_time","!=","0");

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

        $data       = $data->orderBy("delete_time","desc")->paginate(20);
        $game_list  = Game::query()->get()->toArray();
        $return_arr = [
            'data'      => $data,
            'game_list' => $game_list,
            "game_id"   => $request->input('game_id'),
            "user_id"   => $request->input("user_id"),
            "date_begin"=> $request->input('date_begin'),
            "date_end"  => $request->input('date_end'),
            "order"     => $order
        ];
        return view('manager.userManager.getOrder_chedan',$return_arr);
    }












    /**
     * 作用：订单详情
     * 作者：信
     * 时间：2018/4/11
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOrder_detail(Request $request) {
        $id   = $request->input('id');
        $data = Order::query()->with("info")
            ->with("odds")
            ->where("order.id",$id)
            ->first();

        $game_id = $data["gameId"];
        $periods = $data["bet_period"];
        $model   = $this->game_model($game_id);
        $draw_res = $model->where(["game_id"=>$game_id,"periods"=>$periods])->first();

        return view('manager.userManager.getOrder_detail',[
            'data' => $data ,
            "draw_res"  => $draw_res
        ]);
    }




    /**
     * 作用：23个游戏手动开奖数据库操作第二层
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $game_id
     * @return array
     */
    public function game_model($game_id){
        switch ($game_id){
            case 1:
                $model = new DrawResultChongqing();
                break;
            case 2:
                $model = new DrawResultTianjin();
                break;
            case 3:
                $model = new DrawResultXinjiang();
                break;
            case 4:
                $model = new DrawResultTengxun();
                break;
            case 5:
                $model = new DrawResultOuzhou();
                break;
            case 6:
                $model = new DrawResultBeijing();
                break;
            case 7:
                $model = new DrawResultHanguo();
                break;
            case 8:
                $model = new DrawResultXinjiapo();
                break;
            case 9:
                $model = new DrawResultHenei();
                break;
            case 10:
                $model = new DrawResultGuangdong();
                break;
            case 11:
                $model = new DrawResultShandong();
                break;
            case 12:
                $model = new DrawResultShanghai();
                break;
            case 13:
                $model = new DrawResultJiangxi();
                break;
            case 14:
                $model = new DrawResultJiangsu();
                break;
            case 15:
                $model = new DrawResultAnhui();
                break;
            case 16:
                $model = new DrawResultBeijingpk();
                break;
            case 17:
                $model = new DrawResultFenfenpk();
                break;
            case 18:
                $model = new DrawResultFucai3d();
                break;
            case 19:
                $model = new DrawResultFenfen3d();
                break;
            case 20:
                $model = new DrawResultPailie3();
                break;
            case 21:
                $model = new DrawResultLiuhecai();
                break;
            case 22:
                $model = new DrawResultPcdd();
                break;
            case 23:
                $model = new DrawResultBeijingkl8();
                break;
        }
        return $model;
    }





    /**
     * 作用：用户提现
     * 作者：
     * 时间：2018/4/11
     * 修改：信
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_withdraw_verify(Request $request){
        $user_name  = $request->user_id;
        $time       = $request->time?$request->time:1;
        $val        = $request->val;
        $trade_state = $request->zhuangtai;

        $user_withdraw_list = TradeRecord::query()
            ->select('*','trade_record.id as id','trade_record.updated_at as updated_at',"trade_record.bank_details as bank_details","trade_record.bank_name as bank_name","trade_record.bank_account_name as bank_account_name","trade_record.bank_account as bank_account")
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->leftJoin('userinfo','userinfo.user_id','=','trade_record.user_id')
            ->with(["info"=>function($query){
                $query->with("shangji");
            }])
            ->where('trade_record.trade_type',2);

        /*用户名查找*/
        if($user_name){
            $user_id_select = User::query()->where("username",$user_name)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $user_withdraw_list = $user_withdraw_list->where("trade_record.user_id",$user_id_select);
        }
        /*快捷选时*/
        if($time && !$val){
            $kj_time = $this->near_time($time);
            $start_time = date("Y-m-d H:i:s",$kj_time[0]);
            $end_time = date("Y-m-d H:i:s",$kj_time[1]);
//            $user_withdraw_list = $user_withdraw_list->whereDate("created_at",">=",$start_time)
//                ->whereDate("created_at","<=",$end_time);
            $val = substr($start_time,0,10).' - '.substr($end_time,0,10);
        }
        /*日期范围*/
        if($val){
            $start_time = substr($val,0,10)." 00:00:00";
            $end_time = substr($val,13)." 23:59:59";
            $user_withdraw_list = $user_withdraw_list->whereDate("trade_record.created_at",">=",$start_time)
                ->whereDate("trade_record.created_at","<=",$end_time);
        }
        /*用户状态查询*/
        if($trade_state || $trade_state=="0"){
            $user_withdraw_list = $user_withdraw_list->where("trade_record.trade_state",$trade_state);
        }

        $user_withdraw_list = $user_withdraw_list->orderBy("trade_record.id","desc")->paginate(15);
        foreach ($user_withdraw_list as $item){
            $item['ip'] = Loginlog::query()->where('user_id',$item['user_id'])->orderByDesc('create_time')->value('remote_address') ;
        }
        return view('manager.userManager.tixian',[
            'user_withdraw_list'=>  $user_withdraw_list,
            "user_name"         => $user_name,
            "time"              => $time,
            "val"               => $val,
            "zhuangtai"         => $trade_state
        ]);
    }
    /**
     * 作用：审核用户提现记录
     * 作者：
     * 时间：2018/4/11
     * 修改：信
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_shenghe_withdraw_verify(Request $request){
        $user_name  = $request->username;
        $user_withdraw_list = TradeRecord::query()
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with('userbank.bankname')
            ->with(["info"=>function($query){
                $query->with("shangji");
            }])
            ->where('trade_type',2);

        /*用户名查找*/
        if($user_name){
            $user_id_select = User::query()->where("username",$user_name)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $user_withdraw_list = $user_withdraw_list->where("user_id",$user_id_select);
        }

        $user_withdraw_list = $user_withdraw_list->where("trade_state",2);


        $user_withdraw_list = $user_withdraw_list->orderBy("id","desc")->get()->toArray();
        return view('manager.userManager.tixian_user',[
            'user_withdraw_list'=>  $user_withdraw_list,
            "user_name"         => $user_name,
        ]);
    }
    public function user_betOrder_shenghe(Request $request) {
        $data = Order::query()->with("user")
            ->with("info")
            ->with("odds")
            ->where("zhuiHao",0)
            ->where("delete_time",0)
            ->whereDate("order_dateTime",">=",date("Y-m-d 00:00:00",time()))
            ->whereDate("order_dateTime","<=",date("Y-m-d 23:59:59",time()));

        /*用户名*/
        if($request->input("user_id")){
            $user_id = User::query()->where("username",$request->input("user_id"))->first(["user_id"]);
            if($user_id){
                $user_id = $user_id["user_id"];
            }else{
                $user_id = "";
            }
            $data = $data->where("order.user_id",$user_id);
        }

        $datasum       = $data->orderBy("order.order_dateTime","desc")->get()->toArray();
        $data       = $data->orderBy("order.order_dateTime","desc")->get()->toArray();
        $orderBetMoney = 0;
        $orderBetZ = 0;
        foreach ($datasum as $item){
            $orderBetMoney += $item['bonus'];
            $orderBetZ += $item['bet_money'];
        }

        $game_list  = Game::query()->get()->toArray();
        $return_arr = [
            'data'      => $data,
            "username"   => $request->input("user_id"),
            "orderBetMoney"     => $orderBetMoney,
            "orderBetZ"     => $orderBetZ,
        ];
        return view('manager.userManager.getOrder_shenghe',$return_arr);
    }
//审核 用户流水
    public function user_moneyList_shenghe(Request $request){
        $user_id    = $request->user_id;
        $data = Journalaccount::query()->with(["info"=>function($query){
            $query->select("user_id","name","nickname");
        }])->with(["user"=>function($query){
            $query->select("user_id","username");
        }])
            ->with("status");
        if($user_id){
            $user_id2 = User::query()->where("username",$request->input("user_id"))->first(["user_id"]);
            if($user_id2){
                $user_id2 = $user_id2["user_id"];
            }else{
                $user_id2 = "";
            }

            $data = $data->where("user_id",$user_id2);
        }

        $data = $data->orderBy("create_time","desc")->get()->toArray();
        $status_data = Accountstatus::query()->get()->toArray();

        return view("manager.userManager.moneyList",[
            "data"      => $data,

            "username"   => $user_id,

            "status_data" => $status_data,

        ]);
    }
    /**
     * 作用：处理提现信息
     * 作者：信
     * 时间：2018/4/29
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chuli(Request $request){
        $id = $request->id;
        $data = TradeRecord::query()
            ->select('*','trade_record.id as id',"trade_record.bank_details as bank_details","trade_record.bank_name as bank_name","trade_record.bank_account_name as bank_account_name","trade_record.bank_account as bank_account")
            ->leftJoin('userinfo','trade_record.user_id','=','userinfo.user_id')
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->where('trade_record.id',$id)
            ->first()
            ->toArray();
        return view("manager.chuli",["data"=>$data]);
    }




    /**
     * 作用：处理提现处理ajax
     * 作者：信
     * 时间：2018/6/14
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function chuli_ajax(Request $request){
        $data       = $request->all();
        $radio      = $data["radio"];
        $money      = $data["money"];
        $chuli_id   = $data["chuli_id"];
        $user_id    = $data["user_id"];
        $trade_sn    = $data["trade_sn"];
        $beizhu     = $data["beizhu"];
        if($radio == 1){
            try {
                DB::begintransaction();
                $res = Account::query()->where("user_id",$user_id)->decrement('unliquidated_money', $money);
                if(!$res)return ["code"=>0,"msg"=>"扣除冻结款失败"];
                $res = TradeRecord::query()->where("id",$chuli_id)->update(["trade_state"=>2]);
                if(!$res)return ["code"=>0,"msg"=>"订单表状态更改失败"];
                $resList = TradeRecord::query()->where("user_id",$user_id)
                    ->where('trade_type',1)
                    ->where('is_bonus','!=',3)
                    ->get()->toArray();
                foreach ($resList as $key => $value){
                    $result = TradeRecord::query()->where('id',$value['id'])->update(['is_bonus'=>3]);
                    if(!$result){
                        throw new \Exception('更新充值订单状态失败,请反馈技术');
                    }
                }
                $result = Journalaccount::query()->where("tran_num",$trade_sn)->update(["is_handle"=>2]);
                if(!$result){
                    throw new \Exception('账变记录失败');
                }
                $userDailySettle = UserDailySettle::query()->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->first();
                if(!$userDailySettle) {
                    $result = UserDailySettle::query()->insert([
                        'user_id' =>$user_id,
                        'create_time'=>Carbon::now()->toDateString(),
                        'update_time'=>Carbon::now()->toDateString()
                    ]);
                    if(!$result){
                        return api_response(false,'','该用户无法插入盈利记录');//错误
                    }
                }
                //用户每日盈亏结算统计
                $userDailySettle = UserDailySettle::query()
                    ->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->increment('withdrawals',$money);
                if (!$userDailySettle){
                    throw new \Exception('用户每日盈亏结算统计失败');
                }
                //if(!$res)return ["code"=>0,"msg"=>"账变表"];
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['code' => 0,'msg'=>$e->getMessage()];
            }
        }
        if($radio == 2){
            try {
                DB::begintransaction();
                $value = TradeRecord::query()->where("id",$chuli_id)->value('trade_state');
                if($value>1) return ["code"=>0,"msg"=>"该订单已处理,请刷新页面"];
                $res = Account::query()->where("user_id",$user_id)->decrement('unliquidated_money', $money);
                if(!$res)return ["code"=>0,"msg"=>"返还冻结款失败"];
                $res = Account::query()->where("user_id",$user_id)->increment('remaining_money', $money);
                if(!$res)return ["code"=>0,"msg"=>"账户余额添加失败"];
                $res = TradeRecord::query()->where("id",$chuli_id)->update(["trade_state"=>4,"trade_remarks"=>$beizhu]);
                if(!$res)return ["code"=>0,"msg"=>"订单表状态更改失败"];
                //帐变表
                $remaining_money = Account::query()->where('user_id',$user_id)->value('remaining_money');
                $result = Journalaccount::query()->insert([
                    'user_id'=>$user_id,
                    'tran_num'=>$trade_sn,
                    'old_money'=>$remaining_money,/*old money*/
                    'change_status'=>7,
                    'change_money'=>$money,
                    'bet_money'=>$remaining_money,
                    'remarks'=>$beizhu,
                    'create_time'=>Carbon::now()->toDateTimeString()
                ]);
                if (!$result){
                    throw new \Exception('生成帐变记录失败');
                }

                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['code' => 0,'msg'=>$e->getMessage()];
            }
        }
        return ['code' => 1,'msg'=>"处理成功"];

    }
    public function deletOrder(Request $request)
    {
        $id = $request->id;
        $list =  TradeRecord::query()->where('id',$id)->first();
        $result = Journalaccount::query()->where('tran_num',$list['trade_code'])->where('user_id',$list['user_id'])->delete();
        if(!$result){
            return ['code' => 0,'msg'=>"删除失败"];
        }
        $re = TradeRecord::query()->where('id',$id)->update(['trade_state'=>4]);
        if($re){
            return ['code' => 1,'msg'=>"删除成功"];
        }else{
            return ['code' => 0,'msg'=>"删除失败"];
        }
    }
    /**
     * 作用：修改提现倍数
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function withdrawal_update(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $withdrawal      = $data["withdrawal"];
        $res = User::query()->where("user_id",$user_id)->update(["ration"=>$withdrawal]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，修改成功！"];
    }
    /**
     * 作用：修改等级
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function level_update(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $level      = $data["level"];
        $level_img="";

        if($level==1){
            $level_img= "https://api.mayi8.me/vip/1.png";
        }
        elseif($level==2){
            $level_img= "https://api.mayi8.me/vip/2.png";
        }
        elseif($level==3){
            $level_img= "https://api.mayi8.me/vip/3.png";
        }
        elseif($level==4){
            $level_img= "https://api.mayi8.me/vip/4.png";
        }
        elseif($level==5){
            $level_img= "https://api.mayi8.me/vip/5.png";
        }
        elseif($level==6){
            $level_img= "https://api.mayi8.me/vip/6.png";
        }
        $res = User::query()->where("user_id",$user_id)->update(["levels"=>$level,"levels_img"=>$level_img]);
        if(!$res){
            return ["code"=>0,"msg"=>"未作修改，修改失败！"];
        }
        return ["code"=>1,"msg"=>"恭喜您，修改成功！"];
    }
    /**
     * 作用：充值处理
     * 作者：信
     * 时间：2018/5/22
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chongzhichuli(Request $request){
        $id = $request->id;
        $data = TradeRecord::query()
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->where('id',$id)
            ->first();

        return view("manager.chongzhichuli",["data"=>$data]);
    }


    /**
     * 作用：充值处理ajax
     * 作者：信
     * 时间：2018/6/14
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function chongzhichuli_ajax(Request $request){
        $data = $request->all();
        try {
            DB::begintransaction();
            /*充值*/
            $cpAmount = Account::query()->where('user_id',$data["user_id"])->value('remaining_money');
            $res = Account::query()
                ->where("user_id",$data["user_id"])
                ->increment("remaining_money",$data["money"]);
            if (!$res){
                throw new \Exception('充值失败');
            }

            /*充值记录*/
            $res2 = TradeRecord::query()->where("id",$data["chuli_id"])->update(["trade_state"=>2]);
            $TradeRecord = TradeRecord::query()->where("id",$data["chuli_id"])->first()->toArray();
            if(!$res2){
                throw new \Exception('充值记录表改变状态失败');
            }
//            $re = updateStatus($data["user_id"],'充值',$TradeRecord['trade_amount'],$cpAmount,'dd'.date('mdHi').rand(1000,9999));
//            if(!$re){
//                throw new \Exception($re['message']);
//            }
            $result = UserDailySettle::query()
                ->where('user_id',$TradeRecord['user_id'])
                ->where('create_time',Carbon::now()->toDateString())
                ->increment('recharge',$TradeRecord['trade_amount']);
            if(!$result){
                throw new \Exception('盈亏记录失败');
            }
            $result = Journalaccount::query()->where('tran_num',$TradeRecord['trade_code'])->where('user_id',$TradeRecord['user_id'])->update([
                'is_handle'=>2,
            ]);
            if(!$result){
                throw new \Exception('账变记录修改失败');
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return ['code' => 0,'msg'=>$e->getMessage()];
        }
        return ['code' => 1,'msg'=>"充值成功"];
    }


    /**
     * 作用：用户提现[处理中]
     * 作者：
     * 时间：2018/4/11
     * 修改：信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_withdraw_paying(){
        $user_withdraw_list = TradeRecord::query()->with('info')
            ->with('userbank.bankname')
            ->where('trade_type',2)
            ->where('trade_state',1)
            ->paginate(10);

        return view('manager.userManager.user_withdraw_paying',[
            'user_withdraw_list'=>  $user_withdraw_list,
        ]);
    }


    /**
     * 作用：用户提现[已提现]
     * 作者：
     * 时间：2018/4/11
     * 修改：信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_withdraw_payed() {
        $user_withdraw_list = TradeRecord::query()->with('info')
            ->with('userbank.bankname')
            ->where('trade_type',2)
            ->where('trade_state',2)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('manager.userManager.user_withdraw_payed',[
            'user_withdraw_list'=>  $user_withdraw_list,
        ]);
    }


    /**
     * 作用：用户提现[已撤销]
     * 作者：
     * 时间：2018/4/11
     * 修改：信
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_withdraw_back(){
        $user_withdraw_list = TradeRecord::query()->with('info')
            ->with('userbank.bankname')
            ->where('trade_type', 2)
            ->where('trade_state', 4)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('manager.userManager.user_withdraw_back', [
            'user_withdraw_list' => $user_withdraw_list,
        ]);
    }


    /**
     * 作用：用户充值记录
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_chongzhi(Request $request){
        $time       = $request->input("time");
        $user_id    = $request->input("user_id");
        $near_time  = $request->input("val");
        $leixing    = $request->input("leixing");

        //查所有通道
        $newrecharge=newrecharge::query()->get()->toArray();
        $data = TradeRecord::query()->with('info')
            ->with("user")
            ->with("account");

        if($leixing){
            $data = $data->where("trade_record.trade_type",$leixing);
        }else{
            $data = $data->whereIn('trade_record.trade_type',[1,3]);
        }


        /*快捷选时*/
        if($time){
            $start_time = $this->near_time($time)[0];
            $end_time   = $this->near_time($time)[1];
            $data       = $data->whereDate("trade_record.created_at",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("trade_record.created_at","<=",date("Y-m-d H:i:s",$end_time));
        }

        /*用户名搜索*/
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }

            $data = $data->where("trade_record.user_id",$user_id_select);

        }


        /*日期范围*/
        if($near_time){
            $start_time =  date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10)));
            $end_time   =  date("Y-m-d 23:59:59",strtotime(substr($near_time,13)));
            $data       = $data->whereDate("trade_record.created_at",">=",$start_time)
                ->whereDate("trade_record.created_at","<=",$end_time);
        }

        $data = $data->orderBy("id","desc")->paginate(20);
        foreach($data as $k=>$v)
        {
            if($data[$k]['recharge_id']){
                foreach($newrecharge as $kk=>$vv){
                    if($newrecharge[$kk]['id']==$data[$k]['recharge_id']){
                        $data[$k]['recharge_name']=$newrecharge[$kk]['name'];
                    }
                }
            }

        }

        $return_data = [
            "data"      => $data,
            "time"      => $time,
            "user_id"   => $user_id,
            "near_time" => $near_time,
            "leixing"   => $leixing
        ];
        return view("manager.userManager.chongzhi",$return_data);
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





    /**
     * 作用：编辑用户信息
     * 作者：信
     * 时间：2018/4/27
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function modify_user_ajax(Request $request) {
        $phone_number   = $request->input('phone_number');
        $wx_number      = $request->input('wx_number');
        $email_number   = $request->input('email_number');
        $user_id        = $request->input('user_id');
        $true_name      = $request->input('uname');
        $mima           = $request->mima;
        $zijimima       = $request->zijimima;
        $fandain_gao    = $request->fandain_gao;
        $fandain_di     = $request->fandain_di;
        $yuer           = $request->yuer;
        $dongjie        = $request->dongjie;


        if($mima){
            $res = User::query()->where("user_id",$user_id)->update(["password"=>Hash::make($mima)]);
            if($res===false){
                return ['flag' => false, 'msg'=>'登录密码修改失败'];
            }
        }
        if($zijimima){
            $res = Account::query()->where("user_id",$user_id)->update(["withdraw_pwd"=>Hash::make($zijimima)]);
            if($res===false){
                return ['flag' => false, 'msg'=>'资金密码修改失败'];
            }
        }
        $moneyres = Account::query()->where("user_id",$user_id)->update(["remaining_money"=>$yuer,"unliquidated_money"=>$dongjie]);
        if($moneyres===false){
            return ['flag' => false, 'msg'=>'account修改失败'];
        }

        $fandianres = Fandianset::query()->where("user_id",$user_id)->update(["fanDian"=>$fandain_gao,"bFanDian"=>$fandain_di]);
        if($fandianres===false){
            return ['flag' => false, 'msg'=>'返点修改失败'];
        }


        $user_sql       = UserInfo::query()->where('user_id',$user_id);

        $up_arr = [
            'mobile_number' => $phone_number,
            'weixin_number' => $wx_number,
            'email'         => $email_number,
            'profit_status' => $request->input('profit_status'),
            'name'          => $true_name,
        ];

        if ($user_sql->update($up_arr) === false) {
            return ['flag' => false, 'msg'=>'修改信息失败'];
        }
        return ['flag' => true, 'msg'=>'修改信息成功'];
    }


    /**
     * 作用：禁用/启用
     * 作者：信
     * 时间：2018/6/13
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function user_state_ajax(Request $request) {
        $user_id = $request->input('user_id');
        $state = $request->input('state');
        $up_arr = ['user_state'=>$state];

        $user = BuyUser::query()->where('user_id',$user_id);
        if($user->update($up_arr)) {
            return ['flag' => true, 'msg'=> '修改用户状态成功'];
        } else {
            return ['flag' => true, 'msg' => '修改用户状态失败'];
        }
    }



    /**
     * 作用：充值
     * 作者：
     * 时间：
     * 修改：信
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recharge(Request $request) {
        /*执行充值*/
        if ($request->isMethod('post')) {
            if($request->input('recharge_type')==1){
                if (!is_numeric($request->input('recharge'))) {
                    return ['success' => false, 'msg' => '请输入正确数字'];
                }
                $data       = $request->all();
                $yuer       = $data["yuer"];
                $remarks    = $data["remarks"];
                $recharge   = $data["recharge"];
                $user_id    = $data["user_id"];
                if($yuer+$recharge<0){
                    return ['success' => false, 'msg' => '扣钱失败,钱不够扣啦'];
                }
                $remaining_money = Account::query()
                    ->where('user_id',$user_id)->value('remaining_money');
                $trade_sn = $this->get_trade_sn();
                try{
                    DB::beginTransaction();

                    $res = TradeRecord::query()->insert([
                        "user_id"       => $user_id,
                        "trade_time"    => date("Y-m-d H:i:s",time()),
                        "trade_type"    => 1,
                        "trade_amount"  => $recharge,
                        "real_money"    => $recharge,
                        "trade_describe"    => "充值",
                        "trade_info"    => "系统充值",
                        "trade_remarks" => $remarks,
                        "trade_state"   => 2,
                        "trade_sn"      => $trade_sn,
                        "created_at"    => date("Y-m-d H:i:s",time()),
                        "bet_money"     => $remaining_money+$recharge,
                        'old_money'=>$remaining_money
                    ]);
                    if(!$res){
                        throw new \Exception("订单数据插入失败，请重试");
                    }

                    $account_res = Account::query()
                        ->where('user_id',$user_id)
                        ->increment('remaining_money',$recharge);
                    if(!$account_res){
                        throw new \Exception("余额数据插入失败，请重试");
                    }

                    $res_last = Journalaccount::query()->insert([
                        "user_id"    => $user_id,
                        "tran_num"  => $trade_sn,
                        "old_money" => $remaining_money,
                        "change_status" => 1,
                        "change_money"  => $recharge,
                        "bet_money" => $recharge+$yuer,
                        "remarks"   => $remarks,
                        "create_time"   => date("Y-m-d H:i:s",time()),
                        "is_handle"=>2
                    ]);
                    if(!$res_last){
                        throw new \Exception("账变数据插入失败，请重试");
                    }
                    /*判断该用户是否有今日盈亏记录*/
                    $userDailySettle = UserDailySettle::query()->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->first();
                    if(!$userDailySettle) {
                        $result = UserDailySettle::query()->insert([
                            'user_id' =>$user_id,
                            'create_time'=>Carbon::now()->toDateString(),
                            'update_time'=>Carbon::now()->toDateString()
                        ]);
                        if(!$result){
                            return api_response(false,'','该用户无法插入盈利记录');//错误
                        }
                    }
                    //用户每日盈亏结算统计
                    $userDailySettle = UserDailySettle::query()
                        ->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->increment('recharge',$recharge);

                    if (!$userDailySettle){
                        throw new \Exception('用户每日盈亏结算统计失败');
                    }
                    DB::commit();
                }catch (\Exception $exception){
                    DB::rollBack();
                    return ['success' => false, 'msg' => $exception->getMessage()];
                }

                return ['success' => true, 'msg' => '充值成功'];
            }
            elseif($request->input('recharge_type')==3){
                if (!is_numeric($request->input('recharge'))) {
                    return ['success' => false, 'msg' => '请输入正确数字'];
                }
                $data       = $request->all();
                if($data["recharge"]<0){
                    return ['success' => false, 'msg' => '彩金无法扣除,请选择余额扣除'];
                }
                $yuer       = $data["yuer"];
                $remarks    = $data["remarks"];
                $recharge   = $data["recharge"];
                $user_id    = $data["user_id"];
                $remaining_money = Account::query()
                    ->where('user_id',$user_id)->value('remaining_money');
                $trade_sn = $this->get_trade_sn();
                try{
                    DB::beginTransaction();

                    $account_res = Account::query()
                        ->where('user_id',$user_id)
                        ->increment('remaining_money',$recharge);
                    if(!$account_res){
                        throw new \Exception("余额数据插入失败，请重试");
                    }

                    $res_last = Journalaccount::query()->insert([
                        "user_id"    => $user_id,
                        "tran_num"  => $trade_sn,
                        "old_money" => $remaining_money,
                        "change_status" => 9,
                        "change_money"  => $recharge,
                        "bet_money" => $recharge+$yuer,
                        "remarks"   => $remarks,
                        "create_time"   => date("Y-m-d H:i:s",time()),
                    ]);
                    if(!$res_last){
                        throw new \Exception("账变数据插入失败，请重试");
                    }
                    //用户每日盈亏结算统计
                    $userDailySettle = UserDailySettle::query()
                        ->where('user_id',$user_id)
                        ->where('create_time',Carbon::now()->toDateString())
                        ->increment('bonus',$recharge,['total'=>DB::raw('total + '.$recharge)]);

                    if (!$userDailySettle){
                        throw new \Exception('用户每日盈亏结算统计失败');
                    }

                    TradeRecord::query()->where('user_id',$user_id)
                        ->where('trade_type',1)
                        ->where('is_bonus',1)
                        ->update(['is_bonus'=>2]);
                    DB::commit();
                }catch (\Exception $exception){
                    DB::rollBack();
                    return ['success' => false, 'msg' => $exception->getMessage()];
                }
                return ['success' => true, 'msg' => '彩金发放成功'];
            }
            else{
                if (!is_numeric($request->input('recharge'))) {
                    return ['success' => false, 'msg' => '请输入正确数字'];
                }
                $data       = $request->all();
                if($data["recharge"]<0){
                    return ['success' => false, 'msg' => '彩金无法扣除,请选择余额扣除'];
                }
                $yuer       = $data["yuer"];
                $remarks    = $data["remarks"];
                $recharge   = $data["recharge"];
                $user_id    = $data["user_id"];
                $remaining_money = Account::query()
                    ->where('user_id',$user_id)->value('remaining_money');
                $trade_sn = $this->get_trade_sn();
                try{
                    DB::beginTransaction();

                    $account_res = Account::query()
                        ->where('user_id',$user_id)
                        ->increment('remaining_money',$recharge);
                    if(!$account_res){
                        throw new \Exception("余额数据插入失败，请重试");
                    }

                    $res_last = Journalaccount::query()->insert([
                        "user_id"    => $user_id,
                        "tran_num"  => $trade_sn,
                        "old_money" => $remaining_money,
                        "change_status" => 5,
                        "change_money"  => $recharge,
                        "bet_money" => $recharge+$yuer,
                        "remarks"   => $remarks,
                        "create_time"   => date("Y-m-d H:i:s",time()),
                    ]);
                    if(!$res_last){
                        throw new \Exception("账变数据插入失败，请重试");
                    }
                    //用户每日盈亏结算统计
                    $userDailySettle = UserDailySettle::query()
                        ->where('user_id',$user_id)
                        ->where('create_time',Carbon::now()->toDateString())
                        ->increment('bonus',$recharge,['total'=>DB::raw('total + '.$recharge)]);

                    if (!$userDailySettle){
                        throw new \Exception('用户每日盈亏结算统计失败');
                    }

                    TradeRecord::query()->where('user_id',$user_id)
                        ->where('trade_type',1)
                        ->where('is_bonus',1)
                        ->update(['is_bonus'=>2]);
                    DB::commit();
                }catch (\Exception $exception){
                    DB::rollBack();
                    return ['success' => false, 'msg' => $exception->getMessage()];
                }
                return ['success' => true, 'msg' => '彩金发放成功'];
            }
        }

        /*跳转到充值页面*/
        $userinfo = User::query()
            ->leftJoin('account', 'user.user_id', '=', 'account.user_id')
            ->where('user.user_id', $request->input('user_id'))
            ->first(["remaining_money","user.user_id","user.username"]);
        return view('manager.userManager.recharge', ['user' => $userinfo]);
    }

    /**
     * 作用：下分
     * 作者：
     * 时间：
     * 修改：信
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lowerScore(Request $request) {
        /*执行充值*/
        if ($request->isMethod('post')) {
            if (!is_numeric($request->input('recharge'))) {
                return ['success' => false, 'msg' => '请输入正确数字'];
            }
            $data       = $request->all();
            $yuer       = $data["yuer"];
            $remarks    = $data["remarks"];
            $recharge   = $data["recharge"];
            $user_id    = $data["user_id"];
            if($yuer+$recharge<0){
                return ['success' => false, 'msg' => '扣钱失败,钱不够扣啦'];
            }
            $remaining_money = Account::query()
                ->where('user_id',$user_id)->value('remaining_money');
            if($recharge>$remaining_money)
            {
                return ['success' => false, 'msg' => '扣钱失败'];

            }
            $trade_sn = $this->get_trade_sn();
            try{
                DB::beginTransaction();

//                $res = TradeRecord::query()->insert([
//                    "user_id"       => $user_id,
//                    "trade_time"    => date("Y-m-d H:i:s",time()),
//                    "trade_type"    => 1,
//                    "trade_amount"  => $recharge,
//                    "real_money"    => $recharge,
//                    "trade_describe"    => "下分",
//                    "trade_info"    => "系统下分",
//                    "trade_remarks" => $remarks,
//                    "trade_state"   => 2,
//                    "trade_sn"      => $trade_sn,
//                    "created_at"    => date("Y-m-d H:i:s",time()),
//                    "bet_money"     => $remaining_money-$recharge,
//                    'old_money'=>$remaining_money
//                ]);
//                if(!$res){
//                    throw new \Exception("订单数据插入失败，请重试");
//                }

                $account_res = Account::query()
                    ->where('user_id',$user_id)
                    ->decrement('remaining_money',$recharge);
                if(!$account_res){
                    throw new \Exception("余额数据插入失败，请重试");
                }

                $res_last = Journalaccount::query()->insert([
                    "user_id"    => $user_id,
                    "tran_num"  => $trade_sn,
                    "old_money" => $remaining_money,
                    "change_status" => 8,
                    "change_money"  => $recharge,
                    "bet_money" => $remaining_money-$recharge,
                    "remarks"   => $remarks,
                    "create_time"   => date("Y-m-d H:i:s",time()),
                ]);
                if(!$res_last){
                    throw new \Exception("账变数据插入失败，请重试");
                }
                //用户每日盈亏结算统计
//                $userDailySettle = UserDailySettle::query()
//                    ->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->decrement('recharge',$recharge);
//
//                if (!$userDailySettle){
//                    throw new \Exception('用户每日盈亏结算统计失败');
//                }

                DB::commit();
            }catch (\Exception $exception){
                DB::rollBack();
                return ['success' => false, 'msg' => $exception->getMessage()];
            }
            return ['success' => true, 'msg' => '下分成功'];
        }

        /*跳转到充值页面*/
        $userinfo = User::query()
            ->leftJoin('account', 'user.user_id', '=', 'account.user_id')
            ->where('user.user_id', $request->input('user_id'))
            ->first(["remaining_money","user.user_id","user.username"]);
        return view('manager.userManager.lowerScore', ['user' => $userinfo]);
    }
    /**
     * 作用：生成交易编号
     * 作者：信
     * 时间：2018/4/26
     * 修改：暂无
     * @return false|string
     */
    public function get_trade_sn(){
        $trade_sn = date("YmdHis",time());
        $trade_sn .= rand(100000,999999);
        $res = TradeRecord::query()->where("trade_sn",$trade_sn)->first();
        if($res){
            $trade_sn  = $this->get_trade_sn();
        }
        return $trade_sn;
    }





    /**
     * 作用：重置用户密码
     * 作者：信
     * 时间：2018/3/28
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function resetpwd(Request $request){
        $return = ["code"=>0,"msg"=>"请求异常，清稍后再试!"];
        if(!$request->ajax() && !$request->isMethod("POST")){
            return $return;
        }
        $userID     = $request->input("id")?$request->input("id"):"没有获取到ID";
        $password   = bcrypt("vip888");
        $res        = User::where("user_id",$userID)->update(["password"=>$password]);
        if($res){
            $return = ["code"=>1,"msg"=>"密码重置成功"];
            return $return;
        }
        $return     = ["code"=>0,"msg"=>"密码重置失败"];
        return $return;
    }


    /**
     * 作用：重置用户邮箱
     * 作者：信
     * 时间：2018/5/17
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function resetyouxiang(Request $request){
        $return = ["code"=>0,"msg"=>"请求异常，清稍后再试!"];
        if(!$request->ajax() && !$request->isMethod("POST")){
            return $return;
        }
        $userID     = $request->input("id")?$request->input("id"):"没有获取到ID";
        $res        = UserInfo::where("user_id",$userID)->update(["email"=>""]);
        if($res){
            $return = ["code"=>1,"msg"=>"邮箱重置成功"];
            return $return;
        }
        $return     = ["code"=>0,"msg"=>"邮箱重置失败"];
        return $return;
    }



    /**
     * 作用：重置用户资金密码
     * 作者：信
     * 时间：2018/3/28
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function resetzijinpwd(Request $request){
        $return = ["code"=>0,"msg"=>"请求异常，清稍后再试!"];
        if(!$request->ajax() && !$request->isMethod("POST")){
            return $return;
        }
        $userID     = $request->input("id")?$request->input("id"):"没有获取到ID";
        $password   = bcrypt("vip999");
        $res        = Account::where("user_id",$userID)->update(["withdraw_pwd"=>$password]);
        if($res){
            $return = ["code"=>1,"msg"=>"资金密码重置成功"];
            return $return;
        }
        $return     = ["code"=>0,"msg"=>"资金密码重置失败"];
        return $return;
    }






    /**
     * 作用：契约管理[工资]
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_qiyue(Request $request){
        $data = Wage::query()
            ->where("status",1)
            ->with(["userinfo"=>function($query){
                $query->select("user_id","username");
            }])->with(["shangji"=>function($query){
                $query->select("user_id","username");
            }]);

        $time       = $request->input("time");
        $user_id    = $request->input("user_id");
        $near_time  = $request->input("val");

        if($time){
            $start_time = $this->near_time($time)[0];
            $end_time   = $this->near_time($time)[1];
            $data       = $data->whereDate("update_time",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("update_time","<=",date("Y-m-d H:i:s",$end_time));
        }
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data = $data->where("user_id",$user_id_select);
        }
        if($near_time){
            $start_time =  date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10)));
            $end_time   =  date("Y-m-d 23:59:59",strtotime(substr($near_time,13)));
            $data       = $data->whereDate("update_time",">=",$start_time)
                ->whereDate("update_time","<=",$end_time);
        }

        $data = $data->orderBy("update_time","desc")->paginate(20);

        $order_start_time   = date("Y-m-d",time())." 00:00:00";
        $order_end_time     = date("Y-m-d",time())." 23:59:59";
        foreach ($data as $key=>$value){
            $res = $this->getChildrenIds($value["user_id"]);
            $res = substr($res,1);
            if(!$res){
                $data[$key]["today_touzhu"] = 0;
            }else{
                $res_arr = explode(",",$res);
                $today_touzhu = Order::query()
                    ->whereDate("order_dateTime",">=",$order_start_time)
                    ->whereDate("order_dateTime","<=",$order_end_time)
                    ->whereIn("user_id",$res_arr)
                    ->sum("bet_money");
                $data[$key]["today_touzhu"] = $today_touzhu;
            }
        }

        return view("manager.qiyue.gongzi",[
            "data"      => $data,
            "time"      => $time,
            "user_id"   => $user_id,
            "near_time" => $near_time
        ]);
    }



    /**
     * 作用：契约管理[分红]
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_qiyue_fenhon(Request $request){
        $data = Bonus::query()
            ->where("status",1)
            ->with(["userinfo"=>function($query){
                $query->select("user_id","username");
            }])->with(["shangji"=>function($query){
                $query->select("user_id","username");
            }]);

        $time       = $request->input("time");
        $user_id    = $request->input("user_id");
        $near_time  = $request->input("val");

        if($time){
            $start_time = $this->near_time($time)[0];
            $end_time   = $this->near_time($time)[1];
            $data       = $data->whereDate("update_time",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("update_time","<=",date("Y-m-d H:i:s",$end_time));
        }
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data = $data->where("user_id",$user_id_select);
        }
        if($near_time){
            $start_time =  date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10)));
            $end_time   =  date("Y-m-d 23:59:59",strtotime(substr($near_time,13)));
            $data       = $data->whereDate("update_time",">=",$start_time)
                ->whereDate("update_time","<=",$end_time);
        }

        $data = $data->orderBy("update_time","desc")->paginate(20);

        $order_start_time   = date("Y-m-d",time())." 00:00:00";
        $order_end_time     = date("Y-m-d",time())." 23:59:59";
        foreach ($data as $key=>$value){
            $res = $this->getChildrenIds($value["user_id"]);
            $res = substr($res,1);
            if(!$res){
                $data[$key]["today_touzhu"] = 0;
            }else{
                $res_arr = explode(",",$res);
                $today_touzhu = Order::query()
                    ->whereDate("order_dateTime",">=",$order_start_time)
                    ->whereDate("order_dateTime","<=",$order_end_time)
                    ->whereIn("user_id",$res_arr)
                    ->sum("bet_money");
                $data[$key]["today_touzhu"] = $today_touzhu;
            }
        }


        return view("manager.qiyue.fenhon",[
            "data"      => $data,
            "time"      => $time,
            "user_id"   => $user_id,
            "near_time" => $near_time
        ]);
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
     * 作用：日工资详情
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_gongzi_detail(Request $request){
        $id = $request->input("id");

        $data = WageRecord::query()
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with("wage")
            ->with(["daili"=>function($query){
                $query->select("user_id","username");
            }])
            ->where("wage_record.id",$id)
            ->first();

        return view("manager.qiyue.gongzi_detail",["data"=>$data]);
    }



    /**
     * 作用：分红详情
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user_fenhon_detail(Request $request){
        $id = $request->input("id");
        $data = BonusRecord::query()
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with("bonus")
            ->with(["daili"=>function($query){
                $query->select("user_id","username");
            }])
            ->where("bonus_record.id",$id)
            ->first()
            ->toArray();
        return view("manager.qiyue.fenhon_detail",["data"=>$data]);
    }


    /**
     * 作用：追号
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function zhuihao(Request $request){
        $data = Order::query()->with("info")
            ->with("user")
            ->with("odds")
            ->where("zhuiHao","!=",0)
            ->where("delete_time",0);

        /*订单号*/
        if($request->input("order_id")){
            $data = $data->where("order.order_id",$request->input("order_id"));
        }
        /*用户名*/
        if($request->input("user_id")){
            $user_id = User::query()->where("username",$request->input("user_id"))->first(["user_id"]);
            if($user_id){
                $user_id = $user_id["user_id"];
            }else{
                $user_id = "";
            }
            $data = $data->where("order.user_id",$user_id);
        }
        /*购买期数*/
        if($request->input("bet_period")){
            $data = $data->where("order.bet_period",$request->input("bet_period"));
        }
        /*游戏*/
        if($request->input('game_id')) {
            $data = $data->where('order.gameId',$request->input('game_id'));
        }

        $data       = $data->orderBy("order.order_dateTime","desc")->paginate(20);
        $game_list  = Game::query()->get()->toArray();

        $return_arr = [
            'data'      => $data,
            'game_list' => $game_list,
            "game_id"   => $request->input('game_id'),
            "user_id"   => $request->input("user_id"),
        ];
        return view('manager.userManager.getOrder_zhuihao',$return_arr);
    }


    /**
     * 作用：提现提示
     * 作者：信
     * 时间：2018/5/22
     * 修改：暂无
     * @return array
     */
    public function tixian_tishi(){
        $data = TradeRecord::query()
            ->where("trade_type","2")
            ->whereBetween("trade_state",[0,1])
            ->first();
        if($data){
            return ["code"=>1,"msg"=>"有人提现啦","id"=>$data["id"]];
        }
        return ["code"=>0,"msg"=>"没人提现"];
    }


    /**
     * 作用：充值提示
     * 作者：信
     * 时间：2018/5/22
     * 修改：暂无
     * @return array
     */
    public function chongzhi_tishi(){
        $data = offline_recharge::query()
            ->where("status",1)
//            ->whereBetween("trade_state",[0,1])
            ->first();
        if($data){
            return ["code"=>1,"msg"=>"有人充值啦","id"=>$data["id"]];
        }
        return ["code"=>0,"msg"=>"没人充值"];
    }

    public function fictitious(){
        $data = System::all()->toArray();
        return view("manager.agentManager.add_fictitious",["data"=>$data]);
    }

    public function modDailyPwd(Request $request){
        $data = BuyUser::query()->where('user_id',$request->userId)->update(['password'=>bcrypt($request->newPwd)]);
        if($data){
            return ["code"=>1,"msg"=>"重置密码成功"];
        }
        return ["code"=>0,"msg"=>"重置密码失败"];
    }
    //线下充值
    public function offline(Request $request)
    {
        $time       = $request->input("time");
        $user_id    = $request->input("user_id");
        $near_time  = $request->input("val");
        $leixing    = $request->input("leixing");
        $status    = $request->input("status");
        $rn    = $request->input("rn");

        $select = [
            DB::raw("sum(recharge_money) as recharge_money"),
        ];
        //所有通道

        $newrecharge=newrecharge::query()->get()->toArray();
        $data = offline_recharge::query();
        $weixin = offline_recharge::query();

        if($leixing){
            $data = $data->where("recharge_type",$leixing);
            $weixin = $weixin->where("recharge_type",$leixing);
        }

        if($status){
            $data = $data->where("status",$status);
            $weixin = $weixin->where("status",$status);
        }
        if($rn){
            $data = $data->where("recharge_id",$rn);
            $weixin = $weixin->where("recharge_id",$rn);
        }
        /*快捷选时*/
        if($time){
            $start_time = $this->near_time($time)[0];
            $end_time   = $this->near_time($time)[1];
            $data       = $data->whereDate("create_time",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("create_time","<=",date("Y-m-d H:i:s",$end_time));
            $weixin       = $weixin->whereDate("create_time",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("create_time","<=",date("Y-m-d H:i:s",$end_time));
        }

        /*用户名搜索*/
        if($user_id){

            $data = $data->where("username",$user_id);
            $weixin = $weixin->where("username",$user_id);

        }


        /*日期范围*/
        if($near_time){
            $start_time =  date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10)));
            $end_time   =  date("Y-m-d 23:59:59",strtotime(substr($near_time,13)));
            $data       = $data->whereDate("create_time",">=",$start_time)
                ->whereDate("create_time","<=",$end_time);
            $weixin       = $weixin->whereDate("create_time",">=",$start_time)
                ->whereDate("create_time","<=",$end_time);
        }


        $data = $data->orderBy("id","desc")->paginate(20);
        foreach($data as $k=>$v)
        {
            if($data[$k]['recharge_id']){
                foreach($newrecharge as $kk=>$vv){
                    if($newrecharge[$kk]['id']==$data[$k]['recharge_id']){
                        $data[$k]['bank_name']=$newrecharge[$kk]['bank_name'];
                        $data[$k]['bank_username']=$newrecharge[$kk]['bank_username'];
                        $data[$k]['bank_id']=$newrecharge[$kk]['bank_id'];
                        $data[$k]['recharge_name']=$newrecharge[$kk]['name'];



                    }
                }
                //查是否虚拟号
                $is_fictitious=User::query()->where('user_id',$data[$k]['user_id'])->value('is_fictitious');
                if(empty($is_fictitious)){
                    $data[$k]['is_fictitious']="会员";
                }
                else{
                    $data[$k]['is_fictitious']="虚拟账号";
                }
            }

        }
        $weixin=$weixin->select($select)->get()->toArray();
        $return_data = [
            "data"      => $data,
            "time"      => $time,
            "user_id"   => $user_id,
            "near_time" => $near_time,
            "status" => $status,
            "newrecharge" => $newrecharge,
            "rn" => $rn,
            "weixin" => $weixin,
            "leixing"   => $leixing
        ];
        return view("manager.userManager.offline_recharge",$return_data);
    }
    //充值处理
    public function offline_chuli(Request $request)
    {
        $id      = $request->input("id");
        $user_id    = $request->input("user_id");
        $money  = $request->input("money");
        $type = $request->input("type");
        $recharge_id = $request->input("recharge_id");


        if($type==1){  //同意
//            if (!is_numeric($request->input('recharge'))) {
//                return ['success' => false, 'msg' => '请输入正确数字'];
//            }

            $data       = $request->all();
            $user_id    = $request->input("user_id");
            $yuer       = Account::query()->where('user_id',$user_id)->value('remaining_money');
            $remarks    = '';
            $recharge   = $money;


            $remaining_money = Account::query()
                ->where('user_id',$user_id)->value('remaining_money');
            $trade_sn = $this->get_trade_sn();
            try{
                DB::beginTransaction();

                $res = TradeRecord::query()->insert([
                    "user_id"       => $user_id,
                    "trade_time"    => date("Y-m-d H:i:s",time()),
                    "trade_type"    => 1,
                    "trade_amount"  => $recharge,
                    "real_money"    => $recharge,
                    "trade_describe"    => "充值",
                    "trade_info"    => "线下充值",
                    "recharge_id"    => $recharge_id,
                    "trade_remarks" => $remarks,
                    "trade_state"   => 2,
                    "trade_sn"      => $trade_sn,
                    "created_at"    => date("Y-m-d H:i:s",time()),
                    "bet_money"     => $remaining_money+$recharge,
                    'old_money'=>$remaining_money
                ]);
                if(!$res){
                    throw new \Exception("订单数据插入失败，请重试");
                }

                $account_res = Account::query()
                    ->where('user_id',$user_id)
                    ->increment('remaining_money',$recharge);
                if(!$account_res){
                    throw new \Exception("余额数据插入失败，请重试");
                }

                $res_last = Journalaccount::query()->insert([
                    "user_id"    => $user_id,
                    "tran_num"  => $trade_sn,
                    "old_money" => $remaining_money,
                    "change_status" => 1,
                    "change_money"  => $recharge,
                    "bet_money" => $recharge+$yuer,
                    "remarks"   => $remarks,
                    "create_time"   => date("Y-m-d H:i:s",time()),
                    "is_handle"=>2
                ]);
                if(!$res_last){
                    throw new \Exception("账变数据插入失败，请重试");
                }
                $r=offline_recharge::query()
                    ->where('id',$id)
                    ->where('user_id',$user_id)
                    ->update([
                        'status' => 2,
                        'update_time' => now()->toDateTimeString(),
                    ]);
                /*判断该用户是否有今日盈亏记录*/
                $userDailySettle = UserDailySettle::query()->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->first();
                if(!$userDailySettle) {
                    $result = UserDailySettle::query()->insert([
                        'user_id' =>$user_id,
                        'create_time'=>Carbon::now()->toDateString(),
                        'update_time'=>Carbon::now()->toDateString()
                    ]);
                    if(!$result){
                        return api_response(false,'','该用户无法插入盈利记录');//错误
                    }
                }
                //用户每日盈亏结算统计
                $userDailySettle = UserDailySettle::query()
                    ->where('user_id',$user_id)->where('create_time',Carbon::now()->toDateString())->increment('recharge',$recharge);

                if (!$userDailySettle){
                    throw new \Exception('用户每日盈亏结算统计失败');
                }
                DB::commit();
            }catch (\Exception $exception){
                DB::rollBack();
                return ['success' => false, 'msg' => $exception->getMessage()];
            }
//            //查所有的充值金额
//            $sum_chagrge= TradeRecord::query()
//                ->where('user_id',$user_id)
//                ->where('trade_state',2)
//                ->whereDate('created_at',Carbon::today())
//                ->sum('trade_amount');
//            //查看等级表
//            $deleves= level::query()
//                ->orderBy('id','desc')->get()->toArray();
//
//            //查用户等级
//            $user_level=User::query()->where('user_id',$user_id)->value('levels');
////Log::info("会员等级判断:".$user_level);
//            foreach($deleves as $kk=>$vv){
////                    Log::info("$sum_chagrge:".$deleves[$kk]['condition']);
//                if($sum_chagrge >=$deleves[$kk]['condition']){
//                    if($user_level==$deleves[$kk]['level']){        //如果当前等级等于 现在达到的等级
//                        Log::info("退出:".$deleves[$kk]['level']);
//
//                        break;
//                    }
//                    elseif($user_level<$deleves[$kk]['level']){          //当前等级小于  现在达到的等级
//                        User::query()->where('user_id',$user_id)->update(['levels'=>$deleves[$kk]['level'],'levels_img'=>$deleves[$kk]['level_img']]);
//                        $TradeRecord = Account::query()->where('user_id',$user_id)->value('remaining_money');
//                        $result = Account::query()->where('user_id',$user_id)->increment('remaining_money',$deleves[$kk]['reward']);
//
//                        //生成帐变记录
//
//                        $trade_sn = $this->get_trade_sn();
//
//                        $res_last = Journalaccount::query()->insert([
//                            "user_id"    => $user_id,
//                            "tran_num"  => $trade_sn,
//                            "old_money" => $TradeRecord,
//                            "change_status" => 10,
//                            "change_money"  => $deleves[$kk]['reward'],
//                            "bet_money" => $TradeRecord+$deleves[$kk]['reward'],
//                            "remarks"   => "会员赠送",
//                            "create_time"   => date("Y-m-d H:i:s",time()),
//                            "is_handle"=>2
//                        ]);
//                        if(!$res_last){
//                            throw new \Exception("账变数据插入失败，请重试");
//                        }
//                        //用户每日盈亏结算统计
//                        $userDailySettle = UserDailySettle::query()
//                            ->where('user_id',$user_id)
//                            ->where('create_time',Carbon::now()->toDateString())
//                            ->increment('bonus',$deleves[$kk]['reward'],['total'=>DB::raw('total + '.$deleves[$kk]['reward'])]);
//
//                        if (!$userDailySettle){
//                            throw new \Exception('用户每日盈亏结算统计失败');
//                        }
//
//                        break;
//
//                    }
//                }
//            }
            return ['success' => true, 'msg' => '充值成功'];
        }
        if($type==2){ //拒绝
            $r=offline_recharge::query()
                ->where('id',$id)
                ->where('user_id',$user_id)
                ->update([
                    'status' => 3,
                    'update_time' => now()->toDateTimeString(),
                ]);
            return ['success' => false, 'msg' => '拒绝操作成功'];

        }
        if($type==3){ //删除
            $r=offline_recharge::query()
                ->where('id',$id)
                ->where('user_id',$user_id)
                ->delete();
            return ['success' => false, 'msg' => '删除操作成功'];

        }
//        return view("manager.userManager.offline_recharge",$return_data);
    }
    //新线下充值
    public function recharge_lis(Request $request){
        $time       = $request->input("time");
        $user_id    = $request->input("user_id");
        $near_time  = $request->input("val");
        $leixing    = $request->input("leixing");
        $status    = $request->input("status");

        $data = newrecharge::query();

        if($leixing){
            $data = $data->where("type",$leixing);
        }
        if($status){
            $data = $data->where("status",$status);
        }

        /*快捷选时*/
        if($time){
            $start_time = $this->near_time($time)[0];
            $end_time   = $this->near_time($time)[1];
            $data       = $data->whereDate("create_time",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("create_time","<=",date("Y-m-d H:i:s",$end_time));
        }

        /*用户名搜索*/
        if($user_id){

            $data = $data->where("username",$user_id);

        }


        /*日期范围*/
        if($near_time){
            $start_time =  date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10)));
            $end_time   =  date("Y-m-d 23:59:59",strtotime(substr($near_time,13)));
            $data       = $data->whereDate("create_time",">=",$start_time)
                ->whereDate("create_time","<=",$end_time);
        }

        $data = $data->orderBy("id","desc")->paginate(20);

        $return_data = [
            "data"      => $data,
            "time"      => $time,
            "user_id"   => $user_id,
            "near_time" => $near_time,
            "status" => $status,
            "leixing"   => $leixing
        ];
        return view("manager.userManager.new_recharge",$return_data);
    }
    public function recharge_edit(Request $request)
    {

        $newrecharge = newrecharge::query()->find($request->input('id'));
        if ($request->isMethod('post')) {

            if (empty($newrecharge)) {
                return ['success' => false,'msg'=>'参数错误1'];
            }
//            $validator = Validator::make($request->all(), [
//                'title' => 'required|max:150',
//                'content' => 'required|max:240',
//                "show_time" =>"required",
//            ], [
//                'title.required'  => '请输入标题',
//                'title.max'  => '标题最多150个字',
//                'content.required'  => '请输入公告内容',
//                'content.max'  => '公告内容最多240个字',
//                "show_time.required"=>"请选择时间",
//            ]);
//
//            if ($validator->fails()) {
//                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
//                return $errors;
//            }

            try {
                DB::begintransaction();
                $newrecharge->name = $request->input('title');
                $newrecharge->bank_name = $request->input('bank_name');
                $newrecharge->orgain_bank = $request->input('orgain_bank');
                $newrecharge->bank_id = $request->input('bank_id');
                $newrecharge->bank_username = $request->input('bank_username');
                $newrecharge->min = $request->input('min');
                $newrecharge->max = $request->input('max');
                $newrecharge->remary = $request->input('remary');
                $newrecharge->type = $request->input('leixing');
                $newrecharge->order = $request->input('order');
                $newrecharge->is_hot = $request->has('is_hot')?1:0;
                $newrecharge->status = $request->has('status')?1:0;
                if (!$newrecharge->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        if (empty($newrecharge)) {
            return '参数错误2';
        }
        return view('manager.userManager.new_recharge_edit',['newrecharge'=>$newrecharge]);
    }
    public function recharge_add(Request $request)
    {

        if ($request->isMethod('post')) {




            try {
                DB::begintransaction();
                $newrecharge= new newrecharge();
                $newrecharge->name = $request->input('title');
                $newrecharge->bank_name = $request->input('bank_name');
                $newrecharge->orgain_bank = $request->input('orgain_bank');
                $newrecharge->bank_id = $request->input('bank_id');
                $newrecharge->bank_username = $request->input('bank_username');
                $newrecharge->min = $request->input('min');
                $newrecharge->max = $request->input('max');
                $newrecharge->remary = $request->input('remary');
                $newrecharge->type = $request->input('leixing');
                $newrecharge->order = $request->input('order');
                $newrecharge->is_hot = $request->has('is_hot')?1:0;
                $newrecharge->status = $request->has('status')?1:0;
                if (!$newrecharge->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'添加成功'];
        }

        return view('manager.userManager.new_recharge_add',['newrecharge'=>'']);
    }
    public function recharge_icon(Request $request)
    {
        if($request->input('key')=="icon"){
            $res=newrecharge::query()->where('id',$request->input('id'))->update(['icon'=>$request->input('val')]);

        }
        elseif($request->input('key')=="img"){
            $res=newrecharge::query()->where('id',$request->input('id'))->update(['pay_img'=>$request->input('val')]);

        }


        if($res){
            return ['success' => true,'msg'=>'编辑成功'];

        }
        else{
            return ['success' => false,'msg'=>'参数错误'];

        }

    }
    //修改金额
    public function offline_edit(Request $request)
    {
        $newrecharge = offline_recharge::query()->find($request->input('id'));

        if ($request->isMethod('post')) {
            try {
                if (empty($newrecharge)) {
                    return ['success' => false,'msg'=>'参数错误1'];
                }
                DB::begintransaction();
                $newrecharge->recharge_money = $request->input('recharge_money');

                if (!$newrecharge->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'添加成功'];
        }

        return view('manager.userManager.offline_edit',['recharge_money'=>$request->input('recharge_money'),'id'=>$request->input('id')]);
    }
}





