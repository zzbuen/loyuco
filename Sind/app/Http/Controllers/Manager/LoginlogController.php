<?php
/**
 * Created by 信.
 * Date: 2018/4/16
 */
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Loginlog;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;


class LoginlogController extends Controller{

    /**
     * 作用：日志信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log(Request $request){
        $user_id = $request->user_id;
        $data = Loginlog::query()->with(["user"=>function($query){
               $query->select("user_id","username");
            }])
            ->with(["userinfo"=>function($query){
               $query->select("user_id","name");
            }]);

        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data = $data->where("login_log.user_id",$user_id_select);
        }
        $data = $data->latest('login_log.id')->paginate(20);
        return view("login_log.index",["data"=>$data,"user_id"=>$user_id]);
    }


    /**
     * 作用：踢下线
     * 作者：信
     * 时间：2018/4/29
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function tixiaxian(Request $request){
        if($request->isMethod("POST") && !$request->ajax()){
            return ["code"=>0,"msg"=>"请求异常,请稍后再试"];
        }
        $id = $request->id;
        $res = Session::query()->where("id",$id)->update(["is_online"=>0]);
        if($res){
            return ["code"=>1,"msg"=>"踢下线成功"];
        }
        return ["code"=>0,"msg"=>"踢下线失败，请稍后再试"];
    }

}