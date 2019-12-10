<?php
/**
 * Created by 信.
 * Date: 2018/4/2
 */
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Jobs\OpenPrize;
use App\Models\DrawResultJisupk;
use App\Models\DrawResultJisussc;
use App\Models\DrawResultJnd;
use App\Models\DrawResultTenxun;
use App\Models\DrawResultXyft;
use App\Models\Game;
use Illuminate\Http\Request;
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
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DrawResultfucai;

class DrawresultController extends Controller{
      /*
       * 开奖结果页面
       * */
    public function index(Request $request){
        $game_list  =   Game::query()->get()->keyBy('id')->toArray();
        $game_id    =   $request->input('game_id') ? $request->input('game_id') : "1";
        $date_range =   $request->input("date_range")? $request->input("date_range") : false;
        $time       =   $request->input("time")?$request->input("time"):"1";
        $status       =   $request->input("status")?$request->input("status"):"0";
        if($date_range){
            /*日期范围*/
            $startTime  =   date("Y-m-d 00:00:00",strtotime(substr($date_range,0,10)));
            $endTime    =   date("Y-m-d 23:59:59",strtotime(substr($date_range,13)));
        }else{
            /*快捷选时*/
            $timeData   =   $this->near_time($time);
            $startTime  =   date("Y-m-d H:i:s",$timeData[0]);
            $endTime    =   date("Y-m-d H:i:s",$timeData[1]);
            $date_range = substr($startTime,0,10).' - '.substr($endTime,0,10);
        }
        $data   =   $this->lottery_result($game_id,$startTime,$endTime,$status);
        $return = [
            "game_id"       =>  $game_id,
            'game_list'     =>  $game_list,
            "data"          =>  $data,
            "time"          =>  $time,
            "date_range"    =>  $date_range,
            "status"        =>  $status
        ];
        return view("manager.drawresult",$return);
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
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
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
     * 作用：获取23个游戏开奖结果
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $game_id      游戏ID
     * @param $startTime    开始时间
     * @param $endTime      结束时间
     * @return array|string
     */
    public function lottery_result($game_id,$startTime,$endTime,$status){
        switch ($game_id){
            case 1:
                $model = new DrawResultChongqing();
                $type_str   = "draw_result_chongqing.type";
                $str_all    = "draw_result_chongqing.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 2:
                $model = new DrawResultBeijingpk();
                $type_str   = "draw_result_beijingpk.type";
                $str_all    = "draw_result_beijingpk.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 3:
                $model = new DrawResultXyft();
                $type_str   = "draw_result_xyft.type";
                $str_all    = "draw_result_xyft.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 4:
                $model = new DrawResultPcdd();
                $type_str   = "draw_result_pcdd.type";
                $str_all    = "draw_result_pcdd.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 5:
                $model = new DrawResultJnd();
                $type_str   = "draw_result_jnd.type";
                $str_all    = "draw_result_jnd.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 6:
                $model = new DrawResultTenxun();
                $type_str   = "draw_result_tenxun.type";
                $str_all    = "draw_result_tenxun.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 7:
                $model = new DrawResultJisupk();
                $type_str   = "draw_result_jisupk.type";
                $str_all    = "draw_result_jisupk.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 8:
                $model = new DrawResultJisussc();
                $type_str   = "draw_result_jisussc.type";
                $str_all    = "draw_result_jisussc.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
            case 8:
                $model = new DrawResultJisussc();
                $type_str   = "draw_result_jisussc.type";
                $str_all    = "draw_result_jisussc.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;

            case 9:
                $model = new DrawResultfucai();
                $type_str   = "draw_result_fucai.type";
                $str_all    = "draw_result_fucai.*";
                $data = $this->shishicai($model,$startTime,$endTime,$type_str,$str_all,$status);
                break;
        }
        return $data;
    }


    /**
     * 作用：每个彩票得开奖数据
     * 作者：信
     * 时间：2018/4/2
     * 修改：暂无
     * @return array|string
     */
    public function shishicai($model,$startTime,$endTime,$type_str,$str_all,$status){
        $data = $model ->leftJoin("game","game_id","=","game.id")
            ->leftJoin("game_type",$type_str,"=","game_type.id")
            ->select($str_all,"game_type.typeName","game.name")
            ->whereBetween("kaijiang_time",[$startTime,$endTime])
            ->orderBy("id","desc");
        if($status){
            $data = $data->where('res_status',$status);
        }
        $data = $data->paginate(20);

         if(!empty($data)){
             return $data;
         }
         return false;
    }


    /**
     * 作用：重庆时时彩-手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     */
    public function draw_chongqing(Request $request){
        $id         =   $request->input("id");
        $game_id    =   $request->input("game_id");
        switch ($game_id){
            case 1:
                $data   =   DrawResultChongqing::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_chongqing.type","=","game_type.id")
                    ->where(["draw_result_chongqing.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_chongqing.*","game_type.typeName","game.name")
                    ->first();
                break;
            case 2:
                $data   =   DrawResultBeijingpk::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_beijingpk.type","=","game_type.id")
                    ->where(["draw_result_beijingpk.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_beijingpk.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                return view("hand_draw.pk10",["data"=>$data]);
                break;
            case 3:
                $data   =   DrawResultXyft::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_xyft.type","=","game_type.id")
                    ->where(["draw_result_xyft.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_xyft.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                return view("hand_draw.pk10",["data"=>$data]);
                break;
            case 4:
                $data   =   DrawResultPcdd::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_pcdd.type","=","game_type.id")
                    ->where(["draw_result_pcdd.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_pcdd.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                return view("hand_draw.pcdd",["data"=>$data]);
                break;
            case 5:
                $data   =   DrawResultJnd::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_jnd.type","=","game_type.id")
                    ->where(["draw_result_jnd.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_jnd.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                return view("hand_draw.pcdd",["data"=>$data]);
                break;
            case 6:
                $data   =   DrawResultTenxun::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_tenxun.type","=","game_type.id")
                    ->where(["draw_result_tenxun.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_tenxun.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                //return view("hand_draw.pcdd",["data"=>$data]);
                break;
            case 7:
                $data   =   DrawResultJisupk::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_jisupk.type","=","game_type.id")
                    ->where(["draw_result_jisupk.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_jisupk.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                return view("hand_draw.pk10",["data"=>$data]);
                break;
            case 8:
                $data   =   DrawResultJisussc::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_jisussc.type","=","game_type.id")
                    ->where(["draw_result_jisussc.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_jisussc.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
               // return view("hand_draw.pcdd",["data"=>$data]);
                break;
            case 9:
                $data   =   DrawResultfucai::query()
                    ->leftJoin("game","game_id","=","game.id")
                    ->leftJoin("game_type","draw_result_fucai.type","=","game_type.id")
                    ->where(["draw_result_fucai.id"=>$id,"game_id"=>$game_id])
                    ->select("draw_result_fucai.*","game_type.typeName","game.name")
                    ->first()
                    ->toArray();
                return view("hand_draw.pcdd",["data"=>$data]);
                break;
        }
        return view("hand_draw.chongqing",["data"=>$data]);
    }



    /******************
     *
     * 手动开奖一系列操作
     *
     *********************/


    /**
     * 作用：23个游戏手动开奖数据库操作第一层
     * 作者：信
     * 时间：2018/4/4
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function chongqing_ajax(Request $request){
        $data   =   $request->all();
        $result =   $data['result'];
        $id     =   $data['id'];
        $time   =   $data["time"]?$data["time"]:time();
        $game_id    =   $data["game_id"];
        $res_arr    = $this->draw_msg($game_id,$result,$id,$time);
        return $res_arr;
    }


    /**
     * 作用：23个游戏手动开奖数据库操作第二层
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $game_id
     * @return array
     */
    public function draw_msg($game_id,$result,$id,$time){
        $res_arr = [];
        switch ($game_id){
            case 1:
                $model = new DrawResultChongqing();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 2:
                $model = new DrawResultBeijingpk();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 3:
                $model = new DrawResultXyft();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 4:
                $model = new DrawResultPcdd();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 5:
                $model = new DrawResultJnd();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 6:
                $model = new DrawResultTenxun();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 7:
                $model = new DrawResultJisupk();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 8:
                $model = new DrawResultJisussc();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
            case 9:
                $model = new DrawResultfucai();
                $res_arr = $this->set_result_shishicai($model,$id,$result,$time);
                break;
        }
        return $res_arr;
    }



    /**
     * 作用：时时彩手动开奖数据库操作第三层更改数据
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_shishicai($model,$id,$result,$time){
        if(time()>strtotime($time)){
            try {
                /*开始事物*/
                DB::begintransaction();
                $draw_number = $result;
                $list = $model->where("id",$id)->first();
                if($list['res_status']==2){
                    return  ["code"=>0,"msg"=>"此期已开奖"];

                }
                $where["game_id"] = $list['game_id'];
                $where["periods"] = $list['periods'];
                $data = [
                    "result" => $draw_number,
                    "res_status" => 2,
                ];
                //$tableName = $this->tableName($good->game_id);
                if (!$model->where($where)->update($data)) {
                    throw new \Exception('开奖结果记录失败');
                }
                $order_list = Order::query()
                    ->where('status', 0)
                    ->where('bet_period',$where["periods"])
                    ->where('gameId',$where["game_id"])
                    ->where('delete_time',0)
                    ->get()->toArray();
                foreach ($order_list as $item) {
                    Log::info('进入order-订单id为'.$item['id'].'开奖结果为:'.$draw_number);
                    dispatch(new OpenPrize($item,$draw_number));
                }
                DB::commit();
                } catch (\Exception $e) {
                    /*事物回滚*/
                    DB::rollBack();
                    return ["code"=>0, 'msg' => $e->getMessage()];
                }
                return  ["code"=>1,"msg"=>"开奖派奖成功"];
        }
        /*时间未到开奖时间，只更新数值*/
        $list = $model->where("id",$id)->first();
        if($list['res_status']==2){
            return  ["code"=>0,"msg"=>"此期已开奖"];

        }
        $res = $model->where("id",$id)->update(["result"=>$result]); //,"is_resup"=>1
        if($res){
            return  ["code"=>1,"msg"=>"开奖号码提前设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖号码提前设置失败"];
    }


    /**
     * 作用：查看参与人数
     * 作者：信
     * 时间：2018/4/4
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_people(Request $request){
        $gamne_id = $request->input("game_id");
        $periods  = $request->input("periods");

        $where["order.gameId"] = $gamne_id;
        $where["order.bet_period"]= $periods;

        $game_name = Game::query()->where("id",$gamne_id)->select("name")->first()->toArray();

        $data = Order::query()->where($where)->leftJoin("game","order.gameId","=","game.id")
            ->leftJoin("game_type","order.typeId","=","game_type.id")
            ->leftJoin("userinfo","order.user_id","=","userinfo.user_id")
            ->leftJoin("user","order.user_id","=","user.user_id")
            ->leftJoin("odds","order.serial_num","=","odds.serial_num")
            ->select("order.*","game_type.typeName","game.name as gameName","user.username as userName","odds.category","odds.ruleName")
            ->get()
            ->toArray();

        return view("manager.look_draw.shishicai",["data"=>$data,"game_name"=>$game_name,"periods"=>$periods]);
    }


}