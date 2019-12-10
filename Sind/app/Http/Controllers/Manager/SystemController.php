<?php

namespace App\Http\Controllers\Manager;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\Announcement;
use App\Models\AppSpecial;
use App\Models\AppSystem;
use App\Models\Banner;
use App\Models\Fenhong;
use App\Models\Fenxiang;
use App\Models\Horn;
use App\Models\Kefu;
use App\Models\Onoff;
use App\Models\OrderBackup;
use App\Models\Pro;
use App\Models\Question;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Red;
use App\Models\System;
use App\Models\SystemZf;
use App\User;
use GuzzleHttp\Psr7\AppendStream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\IncomeDailySettle;
use App\Models\AgentDailySettle;
use App\Models\TradeRecord;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class SystemController extends Controller
{

    /**
     * 作用：系统参数设置
     * 作者：
     * 时间：2018/4/30
     * 修改：信
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $system_list = System::query()->get()->toArray();
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
//                'recharge_service_charge' => 'required|numeric|min:0|max:100',
                'withdraw_service_charge' => 'required|numeric|min:0|max:100',
//                'invitation_code_prefix' => 'required|numeric|min:1|max:9',
                'withdraw_num'      =>'required|numeric|min:1|max:100',
                'money_limit'      =>'required|numeric|min:1',
                'fandian_limit'      =>'required|numeric|min:1',
                'gfandian'      =>'required|numeric|min:1',
//                'wage'      =>'required|numeric|min:1',
//                'bonus'      =>'required|numeric|min:1',
                'tx_limit'      =>'required|numeric|min:1',
                'tx_lowest'      =>'required|numeric|min:1',
            ], [
//                'recharge_service_charge.required' => '请填写充值手续费比例',
//                'recharge_service_charge.min' => '充值手续比例不能小于0',
//                'recharge_service_charge.max' => '充值手续比例不能大于100',
//                'recharge_service_charge.numeric' => '充值手续比例必须是数值',
                'withdraw_service_charge.required' => '请填写提现手续费比例',
                'withdraw_service_charge.min' => '提现手续比例不能小于0',
                'withdraw_service_charge.max' => '提现手续比例不能大于100',
                'withdraw_service_charge.numeric' => '提现手续比例必须是数值',

                'tx_limit.min' => '提现最高金额不能小于0',
                'tx_limit.numeric' => '提现最高金额必须是数值',
                'tx_limit.required' => '请填提现最高金额',

                'tx_lowest.min' => '提现最低金额不能小于0',
                'tx_lowest.numeric' => '提现最低金额必须是数值',
                'tx_lowest.required' => '请填提现最低金额',

                'withdraw_num.required' => '请填写用户每天提现次数',
                'withdraw_num.min' => '提现次数不能小于0',
                'withdraw_num.max' => '提现次数不能大于100',
                'withdraw_num.numeric' => '提现次数必须是数值',
             /*   'invitation_code_prefix.required' => '请填写邀请码开头',
                'invitation_code_prefix.min' => '邀请码开头小于1',
                'invitation_code_prefix.max' => '邀请码开头不能大于9',
                'invitation_code_prefix.numeric' => '邀请码开头必须是数值',*/

                'money_limit.required' => '请填写最高盈利',
                'money_limit.min' => '最高盈利不能小于1',
                'money_limit.numeric' => '最高盈利必须是数值',

                'fandian_limit.required' => '请填写高频彩返点比',
                'fandian_limit.min' => '高频彩返点比不能小于1',
                'fandian_limit.numeric' => '高频彩返点比必须是数值',

                'gfandian.required' => '请填写六合彩返点比',
                'gfandian.min' => '六合彩返点比不能小于1',
                'gfandian.numeric' => '六合彩返点比必须是数值',

//                'wage.required' => '请填写日工资比',
//                'wage.min' => '日工资比不能小于1',
//                'wage.numeric' => '日工资比必须是数值',
//
//                'bonus.required' => '请填写分红比',
//                'bonus.min' => '分红比不能小于1',
//                'bonus.numeric' => '分红比必须是数值',
            ]);
            if ($validator->fails()) {
                return ['flag'=>false,'msg'=>$validator->errors()->first()];
            }
            try {
                DB::begintransaction();
                foreach ($request->all() as $key => $val) {
                    if ($key != '_token') {
                        if($key!='withdraw_time') {
                            $up = ['value' => $val];
                        }
                        else {
                            $up = ['value' => serialize($val)];
                        }
                        $system_update = System::query()->where('key', $key);
                        if(!$system_update->update($up)){
                            throw new \Exception('参数配置失败');
                        }
                    }
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['flag' => false,'msg'=>$e->getMessage()];
            }
            return ['flag' => true,'msg'=>'参数配置成功'];
        }
        return view('manager.system.parmeter',[
            'system_list' => $system_list
        ]);
    }


    /**
     * 作用：支付方式
     * 作者：
     * 时间：2018/6/15
     * 修改：信
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paymentmethod(Request $request){
        $payment_list = SystemZf::query()->get();
        return view('manager.system.paymentmethod',[
            'payment_list' => $payment_list
        ]);
    }


    /**
     * 作用：更改支付状态
     * 作者：
     * 时间：2018/6/28
     * 修改：信
     * @param Request $request
     * @return array
     */
    public function change_payment_ajax(Request $request){
        $pay_id = $request->input('pay_id');
        $status = $request->input('status');
        $up_arr = ['value' => $status,'type3' => $status];
        $payment_sql =  SystemZf::query()->where('key',$pay_id);
        if(!$payment_sql->update($up_arr)) {
            return ['flag' => false,'msg'=>'变更支付状态失败'];
        }
        return ['flag' => true,'msg'=>'变更支付状态成功'];
    }


    /**
     * 作用：支支持银行
     * 作者：
     * 时间：2018/6/28
     * 修改：信 未做修改
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bank(Request $request){
        $bank = Bank::query()->get()->toArray();
        return view('manager.system.bank',['bank_list'=>$bank]);
    }


    /**
     * 作用：添加银行
     * 作者：
     * 时间：2018/6/28
     * 修改：信 未做修改只是做个注释
     * @param Request $request
     * @return array
     */
    public function add_bank(Request $request){
        $flag = false;
        $bank_list = Bank::query()->get()->toArray();
        $bank_arr = array_column($bank_list,'bank_name');
       if($request->input('bank_name')) {
           $bank_name = $request->input('bank_name');
           foreach ($bank_arr as $item) {
               if($bank_name==$item) {
                   $flag = true;
               }
           }
           if($flag) {
               return ['flag'=>false,'msg'=>'添加银行失败，您已添加了该银行'];
           } else {
               $bank_save = new Bank();
               $bank_save->bank_name = $bank_name;
               if(!$bank_save->save()) {
                   return ['flag'=>false,'msg'=>'添加银行失败，服务器出错'];
               }
               return ['flag'=>true,'msg'=>'添加银行成功'];
           }
       } else{
           return ['flag'=>false,'msg'=>'添加银行失败，银行名称不能为空'];
       }
    }


    /**
     * 作用：删除银行
     * 作者：
     * 时间：2018/6/28
     * 修改：信 未做修改只是做个注释
     * @param Request $request
     * @return array
     */
    public function del_bank_ajax(Request $request){
        $id = $request->input('id');
        $bank_sql = Bank::query()->where('id',$id);
        if(!$bank_sql->delete()){
            return ['flag'=>false,'msg'=>'删除银行失败，服务器出错'];
        }
        return ['flag'=>true,'msg'=>'删除银行成功'];
    }


    /**
     * 作用：安全问题
     * 作者：
     * 时间：2018/6/28
     * 修改：信 未做修改只是做个注释
     * @param Request $request
     * @return array
     */
    public function question(Request $request){
        $question = Question::query()->get()->toArray();
        return view('manager.system.question',['question_list'=>$question]);
    }


    /**
     * 作用：添加安全问题
     * 作者：
     * 时间：2018/6/28
     * 修改：信 未做修改只是做个注释
     * @param Request $request
     * @return array
     */
    public function add_question(Request $request){
        $flag = false;
        $question_list = Question::query()->get()->toArray();
        $question_arr = array_column($question_list,'question');
        if($request->input('question')) {
            $question_name = $request->input('question');
            foreach ($question_arr as $item) {
                if($question_name==$item) {
                    $flag = true;
                }
            }
            if($flag) {
                return ['flag'=>false,'msg'=>'添加安全问题失败，您已经添加了该安全问题'];
            } else {
                $question_save = new Question();
                $question_save->question = $question_name;
                if(!$question_save->save()) {
                    return ['flag'=>false,'msg'=>'添加安全问题失败，服务器出错'];
                }
                return ['flag'=>true,'msg'=>'添加安全问题成功'];
            }
        } else{
            return ['flag'=>false,'msg'=>'添加安全问题失败，安全问题不能为空'];
        }
    }





    /**
     * 作用：更改状态操作成功
     * 作者：信
     * 时间：2018/6/29
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function onoff_ajax(Request $request){
        $data = $request->all();

        $res  = Onoff::query()->where("key",$data["key"])->update(["status"=>$data["status"]]);

        if($res){
            return ["code"=>1,"msg"=>"恭喜您,操作成功！"];
        }
        return ["code"=>0,"msg"=>"系统异常，操作失败！"];
    }
    /*app设置*/
    public function app_system(Request $request){
        $system_list = AppSystem::query()->get()->toArray();
        if ($request->isMethod('post')) {
            try {
                DB::begintransaction();
                foreach ($request->all() as $key => $val) {
                        $up = ['value' => $val];
                        $system_update = AppSystem::query()->where('key', $key);
                        $system_update->update($up);
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['flag' => false,'msg'=>$e->getMessage()];
            }
            return ['flag' => true,'msg'=>'参数配置成功'];
        }
        return view('manager.system.appSystem',[
            'system_list' => $system_list
        ]);
    }

    /*轮播图*/
    public function banner(){
        $list = Banner::query()->orderBy("id","desc")->paginate(10);
        return view('manager.message.banner', [
            'list' => $list,
        ]);
    }

    /*弹框*/
    public function pro(){
        $list = Pro::query()->orderBy("id","desc")->paginate(10);
        return view('manager.message.pro', [
            'list' => $list,
        ]);
    }

    /*添加轮播图*/
    public function banner_add(Request $request){

        if ($request->isMethod('post')) {
         $file = $request->file('file_img');
            if($file->isValid()){

                $path = $file->store('/', 'public');
                $url = Storage::url($path);
                $img_name = url('/').$url;

            }


//
//                //原文件名
//                $originalName = $file->getClientOriginalName();
//
//                //扩展名
//                $ext = $file->getClientOriginalExtension();
//                //MimeType
//                $type = $file->getClientMimeType();
//                //临时绝对路径
//                $realPath = $file->getRealPath();
//                $filename = uniqid().'.'.$ext;
//
//                $bool = Storage::disk('local')->put($filename,file_get_contents($realPath));
//                //判断是否上传成功
//            $ur= url('/').'srotage'.$filename;




            //判断文件是否上传成功

            $validator = Validator::make($request->all(), [
                'title' => 'required|max:150',
                'content' => 'required',
                "show_time" =>"required",
//                "banner_url"=>"required"
            ], [
                'title.required'  => '请输入标题',
                'title.max'  => '标题最多150个字',
//                'banner_url.required'  => '请输入地址',
                'content.required'  => '请输入概要',
                "show_time.required"=>"请选择时间",
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $announcement = new Banner();
                $announcement->title = $request->input('title');
                $announcement->content = $request->input('content');
                $announcement->banner_url =$img_name;
                $announcement->create_time = $request->input('show_time');
                $announcement->create_user = auth('manager')->user()->username;
                $announcement->html = $request->input('html');
                if (!$announcement->save()) {
                    throw new \Exception('添加失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
//            return ['success' => true,'msg'=>'添加成功'];
            return view('manager.message.add_banner');
//            return view('manager.message.banner');

        }
        return view('manager.message.add_banner');
    }


    /*修改轮播图*/
    public function banner_edit(Request $request)
    {
        $list = Banner::query()->find($request->input('id'));
        if ($request->isMethod('post')) {
            if (empty($list)) {
                return ['success' => false,'msg'=>'参数错误'];
            }
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:150',
              //  'content' => 'required|max:240',
//                'banner_url' => "required",
                "show_time" =>"required"
            ], [
                'title.required'  => '请输入标题',
                'title.max'  => '标题最多150个字',
                'content.required'  => '请输入公告内容',
                'content.max'  => '概要最多240个字',
                "show_time.required"=>"请选择时间",
//                "banner_url.required"=>"轮播图地址不得为空"
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }
            //图片上传
            $file = $request->file('banner_url');
            if($file){
                if($file->isValid()){
                    $path = $file->store('/', 'public');
                    $url = Storage::url($path);
                    $img_name = url('/').$url;
                }
                else{
                    $img_name=$request->input('url');
                }
            }
            else{
                $img_name=$request->input('url');
            }
            try {
                DB::begintransaction();
                $list->title = $request->input('title');
                $list->content = $request->input('content');
                $list->banner_url = $img_name;
                $list->create_time = $request->input('show_time');
                $list->create_user = auth('manager')->user()->username;
                $list->html = $request->input('html');
                if (!$list->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];

            }
//            return ['success' => true,'msg'=>'编辑成功'];
            return view('manager.message.edit_banner',['list'=>$list]);

        }
        if (empty($list)) {
            return '参数错误';
        }
        return view('manager.message.edit_banner',['list'=>$list]);
    }

    /*新增弹框*/
    public function pro_add(Request $request){
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:150',
                'content' => 'required',
                "show_time" =>"required",
            ], [
                'title.required'  => '请输入标题',
                'title.max'  => '标题最多150个字',
                'content.required'  => '请输入公告内容',
                "show_time.required"=>"请选择时间",
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $announcement = new Pro();
                $announcement->pro_title = $request->input('title');
                $announcement->pro_content = $request->input('content');
                $announcement->create_time = $request->input('show_time');
                if (!$announcement->save()) {
                    throw new \Exception('添加失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'添加成功'];
        }
        return view('manager.message.add_pro');
    }


    /*修改弹框*/
    public function pro_edit(Request $request)
    {
        $list = Pro::query()->find($request->input('id'));
        if ($request->isMethod('post')) {
            if (empty($list)) {
                return ['success' => false,'msg'=>'参数错误'];
            }
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:150',
                'content' => 'required|max:240',
                "show_time" =>"required",
            ], [
                'title.required'  => '请输入标题',
                'title.max'  => '标题最多150个字',
                'content.required'  => '请输入公告内容',
                'content.max'  => '公告内容最多240个字',
                "show_time.required"=>"请选择时间",
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $list->pro_title = $request->input('title');
                $list->pro_content = $request->input('content');
                $list->create_time = $request->input('show_time');
                if (!$list->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        if (empty($list)) {
            return '参数错误';
        }
        return view('manager.message.edit_pro',['list'=>$list]);
    }

    public function banner_destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            DB::begintransaction();
            $result = Banner::query()->where('id', $id)->delete();
            if (!$result) {
                throw new \Exception('操作失败');
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return ['success' => false,'msg'=>$e->getMessage()];
        }
        return ['success' => true,'msg'=>'操作成功'];
    }

    public function pro_destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            DB::begintransaction();
            $result = Pro::query()->where('id', $id)->delete();
            if (!$result) {
                throw new \Exception('操作失败');
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return ['success' => false,'msg'=>$e->getMessage()];
        }
        return ['success' => true,'msg'=>'操作成功'];
    }

    public function red(){
        $list = Red::query()->get()->toArray();
        return view('manager.system.red',['list'=>$list]);
    }

    public function app_fenhong(){
        $list = Fenhong::query()->get()->toArray();
        return view('manager.system.fenhong',['list'=>$list]);
    }

    public function app_fenxiang(){
        $list = Fenxiang::query()->get()->toArray();
        return view('manager.system.fenxiang',['list'=>$list]);
    }

    public function red_result(Request $request){
        $data = $request->all();
        Red::query()->where('id', $data['id'])->update([
            'right_num'=>$data['value1'],
            'red_money'=>$data['value2']
        ]);
        return ['success' => true,'msg'=>'操作成功'];
    }
    public function horn(){
        $list = Horn::query()->where('id',1)->first();
        return view('manager.system.horn',['list'=>$list]);
    }
    public function horn_ajax(Request $request){
        $data = $request->all();
        Horn::query()->where('id', 1)->update([
            'value'=>$data['value'],
        ]);
        return ['success' => true,'msg'=>'操作成功'];
    }

    public function horn_page(){
        $list = Horn::query()->where('id',1)->first();
        return view('manager.system.editHorn',['list'=>$list]);
    }

    public function banner_mod_open(Request $request){
    $result = Banner::query()->where('id',$request->id)->update(['status'=>1]);
    if($result){
        return ['success' => true,'msg'=>'操作成功'];
    }
    return ['success' => false,'msg'=>'操作失败'];
}

    public function banner_mod_close(Request $request){
        $result = Banner::query()->where('id',$request->id)->update(['status'=>2]);
        if($result){
            return ['success' => true,'msg'=>'操作成功'];
        }
        return ['success' => false,'msg'=>'操作失败'];
    }

    public function limit_mod_open1(Request $request){
        $result = AppSpecial::query()->where('room_type',$request->id)->update(['value1'=>1]);
        if($result){
            return ['success' => true,'msg'=>'操作成功'];
        }
        return ['success' => false,'msg'=>'操作失败'];
    }

    public function limit_mod_close1(Request $request){
        $result = AppSpecial::query()->where('room_type',$request->id)->update(['value1'=>0]);
        if($result){
            return ['success' => true,'msg'=>'操作成功'];
        }
        return ['success' => false,'msg'=>'操作失败'];
    }

    public function limit_mod_open2(Request $request){
        $result = AppSpecial::query()->where('room_type',$request->id)->update(['value2'=>1]);
        if($result){
            return ['success' => true,'msg'=>'操作成功'];
        }
        return ['success' => false,'msg'=>'操作失败'];
    }

    public function limit_mod_close2(Request $request){
        $result = AppSpecial::query()->where('room_type',$request->id)->update(['value2'=>0]);
        if($result){
            return ['success' => true,'msg'=>'操作成功'];
        }
        return ['success' => false,'msg'=>'操作失败'];
    }
    public function limit_mod_value(Request $request){
        if(!is_numeric($request->pass))  return ['success' => false,'msg'=>'请输入数字!'];
        $result = AppSpecial::query()->where('room_type',$request->id)->update(['value3'=>$request->pass]);
        if($result){
            return ['success' => true,'msg'=>'操作成功'];
        }
        return ['success' => false,'msg'=>'操作失败'];
    }
	    //在线客服
    public function kefu(Request $request)
    {
        $list = kefu::query()->first();
        if ($request->isMethod('post')) {

            try {
                DB::begintransaction();

                    $list->html = $request->input('html');
                    if (!$list->save()) {
                        throw new \Exception('操作失败');
                    }


                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        if (empty($list)) {
            return '参数错误';
        }
        return view('manager.kefu',['list'=>$list]);
    }

}





