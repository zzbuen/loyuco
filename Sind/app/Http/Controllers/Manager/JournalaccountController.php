<?php
/**
 * Created by 信.
 * Date: 2018/4/12
 */
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Accountstatus;
use App\Models\Bank;
use App\Models\Journalaccount;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserInfo;

class JournalaccountController extends Controller{


    /**
     * 作用：账变信息
     * 作者：信
     * 时间：2018/4/20
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function index(Request $request){
       $time = $request->time?$request->time:1;
        $user_id    = $request->user_id;
        $val        = $request->val;
        $status     = $request->select_status;

       $data = Journalaccount::query()->with(["info"=>function($query){
           $query->select("user_id","name","nickname");
       }])->with(["user"=>function($query){
           $query->select("user_id","username");
       }])
           ->with("status");

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

       return view("zhangbian.index",[
           "data"      => $data,
           "time"      => $time,
           "user_id"   => $user_id,
           "val"       => $val,
           "status_data" => $status_data,
           "status"    => $status,
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
                $endTime    =   date('Y-m-d 23:59:59', time());
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