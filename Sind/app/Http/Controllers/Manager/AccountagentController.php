<?php
/**
 * Created by 信.
 * Date: 2018/4/12
 */
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\AccountAgent;
use App\Models\Accountstatus;
use App\Models\Bank;
use App\Models\Journalaccount;
use App\Models\User;
use App\Models\UserBank;
use App\Models\Zhuanzhang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserInfo;

class AccountagentController extends Controller{


    /**
     * 作用：转账信息
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function account_agent(Request $request){
       $time = $request->time;
       $user_id = $request->user_id;
       $near_time  = $request->input("val");

       $data = Zhuanzhang::query()->with(["user"=>function($query){
           $query->select("user_id","username");
       }])->with(["account"=>function($query){
           $query->select("user_id","remaining_money");
       }])->with(["account_agent"=>function($query){
           $query->select("user_id","remaining_money");
       }]);


       if($time){
           $start_time = $this->near_time($time)[0];
           $end_time   = $this->near_time($time)[1];
           $data = $data->where("created_at",">=",$start_time)
                                 ->where("created_at","<=",$end_time);
       }

       if($user_id){
           $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
           if($user_id_select){
               $user_id_select = $user_id_select["user_id"];
           }else{
               $user_id_select = "";
           }
           $data       = $data->where("user_id",$user_id_select);
       }

       if($near_time){
           $start_time =  strtotime(date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10))));
           $end_time   =  strtotime(date("Y-m-d 23:59:59",strtotime(substr($near_time,13))));
           $data       = $data->where("created_at",">=",$start_time)
               ->where("created_at","<=",$end_time);
       }



       $data = $data->orderBy("created_at","desc")->get()->toArray();
       return view("zhangbian.zhuanzhang",[
           "data"   => $data,
           "time"   => $time,
           "user_id"   => $user_id,
           "near_time"  => $near_time,
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