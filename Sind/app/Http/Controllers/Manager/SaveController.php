<?php
/**
 * Created by 信.
 * Date: 2018/4/16
 */
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SaveController extends Controller{

    /**
     * 作用：IP限制列表
     * 作者：信
     * 时间：2018/4/17
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $ip =   $request->ip;
        if($ip){
            $data = Save::where("ip",$ip)->paginate(20);
        }else{
            $data = Save::paginate(20);
        }
        return view("save.index",["data"=>$data,"ip"=>$ip]);
    }


    /**
     * 作用：删除IP限制
     * 作者：信
     * 时间：2018/4/17
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function delete_ip(Request $request){
        $id     = $request->id;
        $res    = Save::query()->where("id",$id)->update(["delete_time"=>time()]);
        if($res){
            return ["code"=>1,"msg"=>"删除成功"];
        }
        return ["code"=>0,"msg"=>"删除失败"];
    }


    /**
     * 作用：恢复IP限制
     * 作者：信
     * 时间：2018/4/17
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function huifu_ip(Request $request){
        $id     = $request->id;
        $res    = Save::query()->where("id",$id)->update(["delete_time"=>0]);
        if($res){
            return ["code"=>1,"msg"=>"恢复IP限制成功"];
        }
        return ["code"=>0,"msg"=>"恢复IP限制失败"];
    }


    /**
     * 作用：添加IP限制
     * 作者：信
     * 时间：2018/4/17
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_ip(){
        return view("save.add_ip");
    }



    /**
     * 作用：ajax添加IP限制
     * 作者：信
     * 时间：2018/4/17
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function add_ip_ajax(Request $request){
        $ip = $request->ip;
        $isset = Save::query()->where("ip",$ip)->first();
        if($isset){
            return ["code"=>0,"msg"=>"此IP已在限制列表中"];
        }

        $arr = [
            "ip" =>  $ip,
            "create_time" =>  time()
        ];
        $res = Save::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"新增成功"];
        }
        return ["code"=>0,"msg"=>"新增失败"];
    }
}