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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserInfo;

class BankController extends Controller{

    /**
     * 作用：所有用户的银行卡信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bank(Request $request){
        $user_id    = $request->user_id;
        $name       = $request->name;
        $zhuangtai       = $request->zhuangtai;
        $data = User::query()
            ->leftJoin("userinfo","userinfo.user_id","=","user.user_id")
            ->where('userinfo.bank_name','!=',null)
            ->where('userinfo.bank_account_name','!=',null)
            ->where('userinfo.bank_account','!=',null);
        /*用户名搜索*/
        if($user_id){
            $data = $data->where("user.username",$user_id);
        }
        /*真实姓名*/
        if($name){
            $data = $data->where("userinfo.name",$name);
        }

        /*真实姓名是否填写*/
//        if($zhuangtai){
//            if($zhuangtai=="1"){
//                $data = $data->where("userinfo.name","!=","");
//            }else{
//                $data = $data->where("userinfo.name",null);
//            }
//        }

        $data = $data->orderBy("user.id","desc")->take(10)->paginate(10);
        return view("bank.index",[
            "data"      => $data,
            "user_id"   => $user_id,
            "name"      => $name,
            "zhuangtai" => $zhuangtai
        ]);
    }



    /**
     * 作用：查看单个银行卡详细信息
     * 作者：信
     * 时间：2018/6/15
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_bank(Request $request){
        $data       = $request->all();
        $user_id    = $data["user_id"];
        $bank_id    = $data["bank_id"];
        $num        = $data["num"];
        $number     = ["一","二","三","四","五"];
        $select     = [
            "user.username",
            "userinfo.name",
            "user_bank.id",
            "user_bank.bank_id",
            "user_bank.user_id",
            "user_bank.account",
            "user_bank.status",
            "user_bank.bank_branch",
            "bank.bank_name",
        ];
        $where      = [
            "user_bank.user_id" =>  $user_id,
            "user_bank.id"      =>  $bank_id,
        ];

        $data       = UserBank::query()
            ->select($select)
            ->leftJoin("userinfo","userinfo.user_id","=","user_bank.user_id")
            ->leftJoin("user","user.user_id","=","user_bank.user_id")
            ->leftJoin("bank","bank.id","=","user_bank.bank_id")
            ->where($where)
            ->first();

        $bank = Bank::query()->get();

        return view("bank.look_bank",[
            "data"      => $data,
            "num"       => $num,
            "number"    => $number,
            "bank"      => $bank
        ]);
    }




    /**
     * 作用：修改银行卡号信息
     * 作者：信
     * 时间：2018/6/15
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function midification_account(Request $request){
        $data       = $request->all();
        $bank_id    = $data["bank_id"];
        $user_id    = $data["user_id"];
        $kahao      = $data["kahao"];

        /*用户是否绑定过*/
        $isset =  UserBank::query()->where(["account"=>$kahao,"user_id"=>$user_id,"deleted_at"=>"0"])->first();
        if($isset){
            return ["code"=>0,"msg"=>"该用户已经添加过此银行卡"];
        }

        /*银行卡号信息修改*/
        $res = UserBank::query()->where(["id"=>$bank_id,"user_id"=>$user_id])->update(["account"=>$kahao]);
        if($res){
            return ["code"=>1,"msg"=>"恭喜您，银行卡号信息修改成功！"];
        }
        return ["code"=>0,"msg"=>"系统异常，银行卡号信息修改失败！"];
    }



    /**
     * 作用：修改银行类型信息
     * 作者：信
     * 时间：2018/6/15
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function midification_leixing(Request $request){
        $data       = $request->all();
        $bank_id    = $data["bank_id"];
        $user_id    = $data["user_id"];
        $leixing    = $data["leixing"];
        /*银行卡类型信息修改*/
        $res = UserBank::query()->where(["id"=>$bank_id,"user_id"=>$user_id])->update(["bank_id"=>$leixing]);
        if($res){
            return ["code"=>1,"msg"=>"恭喜您，银行类型信息修改成功！"];
        }
        return ["code"=>0,"msg"=>"系统异常，银行类型信息修改失败！"];
    }



    /**
     * 作用：修改银行卡信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function change_bank(Request $request){
        $userId         = $request->user_id;
        $bank       = Bank::all()->toArray();
        $user_bank  = UserInfo::query()->where("user_id",$userId)->first();

        return view("bank.change_bank",[
            "bank"      => $bank,
            "user_bank" => $user_bank,
        ]);
    }


    /**
     * 作用：ajax修改银行卡信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_bank_ajax(Request $request){
        $data = $request->all();
        $arr  = [
            "bank_name"      => $data["bank_name"],
            "bank_account_name"   => $data["bank_account_name"],
            "bank_account"   => $data["bank_account"],
            "bank_details" => $data["bank_details"]
        ];
        $where["user_id"] = $data["user_id"];

        /*未填写真实姓名*/
//        $user_nickname = UserInfo::query()->where("user_id",$data["user_id"])->first(["name"])->toArray();
//        if($user_nickname["name"]==""){
//            return ["code"=>0,"msg"=>"该用户尚未完善资料，无法修改银行卡"];
//        }

        $res  = UserInfo::query()->where("user_id",$data["user_id"])->update($arr);
        if($res){
            return ["code"=>1,"msg"=>"修改成功"];
        }
        return ["code"=>0,"msg"=>"修改失败"];
    }


    /**
     * 作用：冻结银行卡
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function donjie_bank_ajax(Request $request){
        $id     = $request->id;
        $res    = UserBank::query()->where("id",$id)->update(["status"=>1]);
        if($res){
            return ["code"=>1,"msg"=>"冻结成功"];
        }
        return ["code"=>0,"msg"=>"冻结失败"];
    }


    /**
     * 作用：解冻银行卡
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function jiedon_bank_ajax(Request $request){
        $id     = $request->id;
        $res    = UserBank::query()->where("id",$id)->update(["status"=>0]);
        if($res){
            return ["code"=>1,"msg"=>"解除冻结成功"];
        }
        return ["code"=>0,"msg"=>"解除冻结失败"];
    }




    /**
     * 作用：解绑银行卡
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function jiebang_bank_ajax(Request $request){
        $id     = $request->id;
        $res    = UserInfo::query()->where("user_id",$id)->update([
            "bank_name"=>null,
            "bank_account_name"=>null,
            "bank_account"=>null,
            "bank_details"=>null
        ]);
        if($res){
            return ["code"=>1,"msg"=>"解绑成功"];
        }
        return ["code"=>0,"msg"=>"解绑失败"];
    }



    /**
     * 作用：添加银行卡
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_bank(Request $request){
        $user_id    = $request->user_id;
        $user       = User::query()->where("user_id",$user_id)->first();
        $bank       = Bank::all()->toArray();
        return view("bank.add_bank",[
            "bank"      => $bank,
            "user_id"   => $user_id,
            "user"      => $user
        ]);
    }


    /**
     * 作用：ajax添加银行卡
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function add_bank_ajax(Request $request){
        if(!$request->isMethod("POST") || !$request->ajax()){
            return ["code"=>0,"msg"=>"很抱歉，请求异常"];
        }

        $data   = $request->all();
        $where["user_id"] = $data["user_id"];
        $where["account"] = $data["account"];
        $where["deleted_at"] = "0";

        /*未填写真实姓名*/
//        $user_nickname = UserInfo::query()->where("user_id",$data["user_id"])->first(["name"]);
//        if($user_nickname["name"]==""){
//            return ["code"=>0,"msg"=>"该用户尚未完善资料，无法绑定银行卡"];
//        }
        /*用户是否绑定过*/
        $isset =  UserBank::query()->where($where)->first();
        if($isset){
            return ["code"=>0,"msg"=>"该用户已经添加过此银行卡"];
        }
        /*是否被别人绑定过*/
        $isset =  UserBank::query()
            ->where("account",$data["account"])
            ->where("user_id","!=",$data["user_id"])
            ->where("deleted_at","0")
            ->first();
        if($isset){
            return ["code"=>0,"msg"=>"此卡号已被别的用户绑定"];
        }
        /*添加银行卡*/
        $name = $user_nickname["name"];
        $data["name"]   = $name;
        $res    = UserBank::query()->insertGetId($data);
        if($res){
            return ["code"=>1,"msg"=>"添加银行卡成功"];
        }
        return ["code"=>0,"msg"=>"添加银行卡失败"];
    }

}