<?php
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgController extends Controller
{
    public $agent_name;
    public $agent_pass;
    public $private_key;
    public $host;
    public function __construct()
    {
        $this->agent_name = 'o72';
        $this->agent_pass = '8f8e26cf51';
        $this->private_key = '6a89b14b67e2c57c7c68bfffdc4949f7';
        $this->host = "http://api.kemairui.cn/ag?agent_name=" .$this->agent_name .'&agent_pass='.$this->agent_pass
            .'&private_key='.$this->private_key.'&function=';
    }
    /*入口*/
    public function index(Request $request){
        $data = $request->all();
        $function = $data['function'];
        return $this->$function($data);
    }
    /*发送url*/
    public function url($url){
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true);
        if($list['result']=='true') return api_response(true,$list['data'],$list['msg']);
        else return api_response(false,$list['data'],$list['msg']);
    }
    /*跳转到AG游戏页面*/
    public function ForwardGame(){
            if(!Auth::guard('user')->check()){
                return api_response(false,111,111);
            }
            $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id;
            $file_contents = file_get_contents($url);
            $list = json_decode($file_contents,true); /*"member_id not exists 未注册*/
            if($list['result']=='true')
                return api_response(true,$list['data'],$list['msg']);
            else{
                if ($list['msg']=='member_id not exists') $this->CheckOrCreateAccount(1);
                else   return api_response(false,$list['data'],$list['msg']);
            }
    }
    /*跳转到AG游戏页面*/
    public function GetForwardGame(){
        if(!Auth::guard('user')->check()){
            return [
                'msg'=>'login'
            ];
        }
        $url = $this->host  .'ForwardGame'.'&member_id='.auth('user')->user()->user_id;
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true); /*"member_id not exists 未注册*/

        if($list['result']=='true')
            return [
                'url'=>$list['data']
                ,'msg'=>$list['msg']
                ,'result'=>$list['result']
                ,'status'=>$list['result']
            ];
        else {
            if ($list['msg'] == 'member_id not exists') {
                $this->CheckOrCreateAccount(1);
            }
            else {
                return [
                    'url'=>$list['data']
                    ,'msg'=>$list['msg'].'3'
                    ,'result'=>$list['result']
                    ,'status'=>$list['result']
                ];
            }
        }
    }
    /*检测和注册AG账号*/
    public function CheckOrCreateAccount($type=''){
        $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id;
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true);
        if($list['result']=='true')
        {
            if($type==1) return $this->GetForwardGame();
            if($type==2) return $this->GetBalance();
            else return api_response(true,$list['data'],$list['msg']);
        }else return api_response(false,$list['data'],$list['msg']);
    }
    public function signup($user_id){
        $url = $this->host.'CheckOrCreateAccount'.'&member_id='.$user_id;
        $file_contents = file_get_contents($url);
    }


    /*获取游戏帐户登录名*/
    public function GetAccountLogin(){
        $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id;
        return $this->url($url);
    }
    /*获取余额*/
    public function GetBalance(){
        $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id;
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true);
        if($list['result']=='true') return api_response(true,$list['data'],$list['msg']);
        else{
            return $this->CheckOrCreateAccount(2);
        }
    }
    /*转入转出*/
    /*amount  int  Y   转入转出额度   direction  int  Y   1=转入，0=转出  ticket_id  string  N   订单号  */

//    public function PrepareTransferCredit($data){
//
//        //$cpAmount = Account::query()->where('user_id',auth('user')->user()->user_id)->value('remaining_money');
//        if($data['direction']==1){
//            /*转入AG账户 判断彩票账户余额是否足够 扣除彩票余额*/
//            try
//            {
//                DB::beginTransaction();
//               /* $result = Account::query()->decrement('remaining',$data['amount']);
//                if(!$result){
//                    throw new \Exception('扣除余额失败');
//                }*/
//                $tranNum = 'ag'.date('YmdHis').rand(1000,9999);
//
//                $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id.'&amount='.$data['amount'].'&direction='.$data['direction'].'&ticket_id='.$tranNum;
//                $file_contents = file_get_contents($url);
//                $list = json_decode($file_contents,true);
//                if($list['result']!='true') return api_response(false,'',$list['msg']);
//                $result = JournalAccount::query()->insert([
//                    'user_id'=>auth('user')->user()->user_id,
//                    'tran_num'=>$tranNum,
//                    'old_money'=>$data['lotmoney'],/*原金额*/
//                    'change_status'=>10,
//                    'change_money'=>-$data['amount'],
//                    'bet_money'=>$data['lotmoney']-$data['amount'],
//                    'remarks'=>'转入AG账户',
//                    'create_time'=>Carbon::now()->toDateTimeString()
//                ]);
//                if (!$result){
//                    throw new \Exception('生成帐变记录失败');
//                }
//                DB::commit();
//                adminSendUser('转账','已成功转入AG账户',auth('user')->user()->username);
//                return api_response(true,'','已成功转入AG账户');//错误
//            }
//            catch(\Exception $e)
//            {
//                DB::rollBack();
//                return api_response(false,'',$e->getMessage());//错误
//            }
//        }elseif ($data['direction']==0){
//            /*转出AG账户 判断AG账户余额是否足够 添加彩票余额*/
//            $agAmount = json_decode($this->GetBalance(),true)['data'];
//            if($agAmount<$data['amount']) return api_response(false,'','AG账户余额不足');
//            try
//            {
//                DB::beginTransaction();
//               /* $result = Account::query()->increment('remaining',$data['amount']);
//                if(!$result){
//                    throw new \Exception('转出余额失败');
//                }*/
//                $tranNum = 'ag'.date('YmdHis').rand(1000,9999);
//                $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id.'&amount='.$data['amount'].'&direction='.$data['direction'].'&ticket_id='.$tranNum;
//                $file_contents = file_get_contents($url);
//                $list = json_decode($file_contents,true);
//                if($list['result']!='true') return api_response(false,'',$list['msg']);
//             /*   if(!json_decode($url,true)['success']){
//                    throw new \Exception('转出AG账户失败,请稍后再试');
//                }*/
//                $result = JournalAccount::query()->insert([
//                    'user_id'=>auth('user')->user()->user_id,
//                    'tran_num'=>$tranNum,
//                    'old_money'=>$data['lotmoney'],/*原金额*/
//                    'change_status'=>9,
//                    'change_money'=>$data['amount'],
//                    'bet_money'=>$data['lotmoney']+$data['amount'],
//                    'remarks'=>'转出AG账户',
//                    'create_time'=>Carbon::now()->toDateTimeString()
//                ]);
//                if (!$result){
//                    throw new \Exception('生成帐变记录失败');
//                }
//                DB::commit();
//                adminSendUser('转账','已成功从AG账户转出',auth('user')->user()->username);
//                return api_response(true,'','已成功从AG账户转出');
//            }
//            catch(\Exception $e)
//            {
//                DB::rollBack();
//                return api_response(false,'',$e->getMessage());//错误
//            }
//        }
//    }
    public function PrepareTransferCredit($data){
        //$cpAmount = Account::query()->where('user_id',auth('user')->user()->user_id)->value('remaining_money');
        $user_id = auth('user')->user()->user_id;
        if($data['direction']==1){
            $url = $this->host  .__FUNCTION__.'&member_id='.$user_id.'&amount='.$data['amount'].'&direction='.$data['direction'].'&ticket_id='.$data['tranNum'];
            $file_contents = file_get_contents($url);
            $list = json_decode($file_contents,true);
            if($list['result']!='true') return api_response(false,'',$list['msg']);
            return api_response(true,'',$list['msg']);
        }elseif ($data['direction']==0){
            /*转出AG账户 判断AG账户余额是否足够 添加彩票余额*/
            $agAmount = json_decode($this->GetBalance(),true)['data'];
            if($agAmount<$data['amount']) return api_response(false,'','AG账户余额不足');
            $tranNum = 'ag'.date('YmdHis').rand(1000,9999);
            $url = $this->host  .__FUNCTION__.'&member_id='.$user_id.'&amount='.$data['amount'].'&direction='.$data['direction'].'&ticket_id='.$data['tranNum'];
            $file_contents = file_get_contents($url);
            $list = json_decode($file_contents,true);
            if($list['result']!='true') return api_response(false,'',$list['msg']);
            return api_response(true,'',$list['msg']);
        }
    }
    /*查询订单状态 */
    public function QueryOrderStatus($data){
        $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id.'&ticket_id='.$data['ticket_id'];
        return $this->url($url);
    }

    /*查询订单明细 */
    public function CheckTransferCredit($data){
        $url = $this->host  .__FUNCTION__.'&member_id='.auth('user')->user()->user_id.'&ticket_id='.$data['ticket_id'];
        return $this->url($url);
    }

    /*获取投注记录 */
    /*rType  string  Y   投注类型，0=全部， 1= 视讯，3=电子  frDate  datetime  Y   开始时间  YYYY-mm-ddHH:ii:ss  toDate  datetime  Y   结束时间  YYYY-mm-ddHH:ii:ss */
    public function GetReport($data){
        $data = array("agent_name" => 'o72',"agent_pass" => '8f8e26cf51',"private_key"=>"6a89b14b67e2c57c7c68bfffdc4949f7",
            "function"=>__FUNCTION__,"member_id"=>auth()->user()->user_id,"rType"=>$data['rType'],"frDate"=>$data['frDate'],"toDate"=>$data['toDate']);
        $data = http_build_query($data);

        $opts = array(
            'http'=>array(
                'method'=>"POST",
                'header'=>"Content-type: application/x-www-form-urlencoded\r\n".
                    "Content-length:".strlen($data)."\r\n" .
                    "Cookie: foo=bar\r\n" .
                    "\r\n",
                'content' => $data,
            )
        );
        $cxContext = stream_context_create($opts);
        $file_contents = file_get_contents("http://api.kemairui.cn/ag", false, $cxContext);
        $list = json_decode($file_contents,true);
        if($list['result']=='true') return api_response(true,$list['data'],$list['msg']);
        else return api_response(false,$list['data'],$list['msg']);
    }
    /*获取后台线路余额*/
    public function GetLineBlance(){
        $url = $this->host  .__FUNCTION__;
        return $this->url($url);
    }

}