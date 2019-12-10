<?php
/**
 * Created by 信.
 * Date: 2018/5/3
 */

namespace App\Http\Controllers;
use App\Models\ActiveNew;
use Illuminate\Http\Request;
use App\Classes;
class Pk10Controller extends Controller{



    public function index(Request $request){
        $pk10_model = new Classes\Fucai3d();
        $res = $pk10_model->bn_050205();
        dump($res);
    }


    /**
     * 作用：
     * 作者：信
     * 时间：2018/5/9
     * 修改：暂无
     * @param Request $request
     * @return array|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function user_active_msg(Request $request){
        $user_id = $request->user_id;
        $user_id = 7031520;
        $where["user_id"]       = $user_id;
        $where["deleted_at"]    = 0;
        $data = ActiveNew::query()
            ->where($where)
            ->where("end_time",">",time())
            ->with(["user"=>function ($query){
                $query->select("user_id","username");
            }])
            ->with(["parent_user"=>function ($query){
                $query->select("user_id","username");
            }])
            ->first();
        $data = $data?$data->toArray():[];
        return $data;
    }


    /**
     * 作用：添加活动
     * 作者：信
     * 时间：2018/5/9
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function add_new_acctive(Request $request){
        $data           = $request->all();
        $user_id        = $data["user_id"];
        $parent_user_id = $data["parent_user_id"];
        $chongzhi_money = $data["chongzhi_money"];
        $jiangli_money  = $data["jiangli_money"];
        $days           = $data["days"];

        $arr = [
            "parent_user_id"    => $parent_user_id,
            "user_id"           => $user_id,
            "chongzhi_money"    => $chongzhi_money,
            "jiangli_money"     => $jiangli_money,
            "status"            => 1,
            "total_chongzhi"    => 0,
            "days"              => $days,
            "start_time"        => time(),
            "end_time"          => time()+$days*24*60*60,
            "created_at"        => time(),
        ];
        $res = ActiveNew::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"添加活动成功"];
        }
        return ["code"=>0,"msg"=>"添加活动失败"];
    }
}