<?php
/**
 * Created by 信.
 * Date: 2018/4/12
 */
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Active;
use App\Models\ActiveUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserInfo;


class ActiveController extends Controller{

    /**
     * 作用：优惠活动
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function huodon(Request $request){
        $data = Active::query()->with(["user"=>function($query){
            $query->select("user_id","username");
        }])->with(["parent"=>function($query){
            $query->select("user_id","username");
        }])->orderByDesc('id')
        ->paginate(20);

        return view("manager.active.index",["data"=>$data]);
    }


    /**
     * 作用：查看优惠活动参与人数
     * 作者：信
     * 时间：2018/4/13
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function active_user(Request $request){
         $id = $request->input("id");
         $data = ActiveUser::query()->with(["user"=>function($query){
            $query->select("user_id","username");
            }])
            ->with("active")
            ->where("active_user.active_id","$id")
            ->get()
            ->toArray();

        return view("manager.active.active_user",["data"=>$data]);
    }
}