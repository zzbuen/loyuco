<?php
/**
 * Created by 信.
 * Date: 2018/4/12
 */
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Bank;
use App\Models\User;
use App\Models\UserBank;
use App\Models\BonusRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserInfo;

class BonusrecordController extends Controller{


    /**
     * 作用：分红数据统计
     * 作者：信
     * 时间：2018/4/17
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $user_id    = $request->user_id;
        $date_begin = $request->date_begin;
        $date_end   = $request->date_end;
        $status     = $request->status?$request->status:1;
        $time       = $request->time;

        $data = BonusRecord::query()
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with("bonus");

        $sousuo_fenhon  = BonusRecord::query()->where("status",$status);
        $sum_fafang     = BonusRecord::query()->where("status",1)->sum("bonus_amount");

        /*用户名查询*/
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data               = $data->where("user_id",$user_id_select);
            $sousuo_fenhon      = $sousuo_fenhon->where("user_id",$user_id_select);
        }
        /*日期范围查询*/
        if($date_begin){
            $date_begin_res     = $date_begin." 00:00:00";
            $data               = $data->whereDate("create_time",">=",$date_begin_res);
            $sousuo_fenhon      = $sousuo_fenhon->whereDate("create_time",">=",$date_begin_res);
        }
        /*日期范围查询*/
        if($date_end){
            $date_end_res       = $date_end." 23:59:59";
            $data               = $data->whereDate("create_time","<=",$date_end_res);
            $sousuo_fenhon      = $sousuo_fenhon->whereDate("create_time","<=",$date_end_res);
        }
        /*快捷选时*/
        if($time){
            $near_time = $this->near_time($time);
            $start_time = substr($near_time[0],0,10);
            $end_time = substr($near_time[1],0,10);
            $data               = $data->whereDate("create_time","<=",$end_time)
                ->whereDate("create_time",">=",$start_time);
            $sousuo_fenhon      = $sousuo_fenhon->whereDate("create_time","<=",$end_time)
                ->whereDate("create_time",">=",$start_time);
        }
        /*状态查询*/
        if($status){
            $data   = $data->where("status",$status);
        }

        $data = $data->orderByDesc('create_time')->paginate(20);
        $sousuo_fenhon = $sousuo_fenhon->sum("bonus_amount");

        return view("manager.statManager.fenhon",[
            "data"          => $data,
            "sum_fafang"    => $sum_fafang,
            "sousuo_fenhon" => $sousuo_fenhon,
            "user_id"       => $user_id,
            "date_begin"    => $date_begin,
            "date_end"      => $date_end,
            "status"        => $status,
            "time"          => $time,
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



}