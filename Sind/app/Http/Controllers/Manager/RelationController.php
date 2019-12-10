<?php
/**
 * Created by 信.
 * Date: 2018/4/12
 */
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Bank;
use App\Models\Relation;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserInfo;

class RelationController extends Controller{

    /**
     * 作用：所有链接信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $user_id = $request->user_id;
        $data = Relation::query()->with("user")
            ->where("deleted_at",0);
        if($user_id){
            $user_id_select = User::query()->where("username",$user_id)->first(["user_id"]);
            if($user_id_select){
                $user_id_select = $user_id_select["user_id"];
            }else{
                $user_id_select = "";
            }
            $data = $data->where("user_id",$user_id_select);

        }

        $data = $data->orderByDesc('create_time')->paginate(10);

        return view("relation.index",[
            "data"=>$data,
            "user_id"=>$user_id,
        ]);
    }


    /**
     * 作用：删除链接
     * 作者：信
     * 时间：2018/4/29
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function delete_relation(Request $request){
        $id = $request->id;
        $res = Relation::query()->where("id",$id)->update(["deleted_at"=>time()]);
        if($res){
            return ["code"=>1,"msg"=>"链接删除成功"];
        }
        return ["code"=>0,"msg"=>"链接删除失败，请稍后再试"];
    }


    /**
     * 作用：失效/恢复
     * 作者：信
     * 时间：2018/4/29
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_relation_status(Request $request){
        $id     = $request->id;
        $status = $request->status;
        $res    = Relation::query()->where("id",$id)->update(["status"=>$status]);
        if($res){
            return ["code"=>1,"msg"=>"操作成功"];
        }
        return ["code"=>0,"msg"=>"操作失败，请稍后再试"];
    }


}