<?php
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BbinController extends Controller
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
        $this->host = "http://api.kemairui.cn/bbin?agent_name=" .$this->agent_name .'&agent_pass='.$this->agent_pass
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
    /*跳转到BBIN游戏页面*/
    public function Login($data){
        if(!Auth::guard('user')->check()){
            return api_response(false,111,111);
        }
        $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id.'&page_site='.$data['page_site'];
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true); /*"username not exists 未注册*/

        if($list['result']=='true'){
            $data = $list['data'];/*需要截取操作*/
            return api_response(true,$data,'登录成功');
        }
        else return api_response(false,$list['data'],$list['msg']);

    }
    /*跳转到BBIN游戏页面*/
    public function GetForwardGame(){
        if(!Auth::guard('user')->check()){
            return api_response(false,111,111);
        }
        $url = $this->host  .'Login'.'&username='.auth('user')->user()->user_id.'&page_site=';
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true);

        return [
            'url'=>$list['data']
            ,'msg'=>$list['msg']
            ,'result'=>$list['result']
        ];
    }
    /*注册Bbin账号*/
    public function CreateMember($type=''){
        $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id;
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true);
        if($list['result']=='true')
        {
            if($type==1) return $this->CheckUsrBalance();
            return api_response(true,$list['data'],$list['msg']);
        }else return api_response(false,$list['data'],$list['msg']);
    }
    /*注册Bbin账号*/
    public function signup($user){
        $url = $this->host  .'CreateMember'.'&username='.$user;
        $file_contents = file_get_contents($url);
    }

    /*获取游戏帐户登录名*/
    //    public function GetAccountLogin(){
    //        $url = $this->host  .__FUNCTION__.'&username='.auth()->user()->user_id;
    //        return $this->url($url);
    //    }
    /*获取余额*/
    public function CheckUsrBalance(){
        $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id;
        $file_contents = file_get_contents($url);
        $list = json_decode($file_contents,true);
        if($list['result']=='true') return api_response(true,$list['data']['data'][0]['Balance'],$list['msg']);
        else return $this->CreateMember(1);
    }
    /*转入转出*/
    /*amount  int  Y   转入转出额度   direction  int  Y   1=转入，0=转出  transid  string  N   订单号  */

//    public function Transfer($data){
//       // $bbinAmount = json_decode($this->CheckUsrBalance(),true)['data'];
//        //$cpAmount = Account::query()->where('user_id',auth('user')->user()->user_id)->value('remaining_money');
//        if($data['action']=='IN'){
//            /*转入BBIN账户 判断彩票账户余额是否足够 扣除彩票余额*/
//            try
//            {
//                DB::beginTransaction();
////                $result = Account::query()->decrement('remaining_money',$data['amount']);
////                if(!$result){
////                    throw new \Exception('扣除余额失败');
////                }
//
//                $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id.'&remit='.$data['amount'].'&action='.$data['action'].'&remitno='.$tranNum;
//                $file_contents = file_get_contents($url);
//                if(!json_decode($file_contents,true)['result']){
//                    throw new \Exception('转入BBIN账户失败,请稍后再试');
//                }
//                $tranNum = time().rand(1000,9999);
//                $result = JournalAccount::query()->insert([
//                    'user_id'=>auth('user')->user()->user_id,
//                    'tran_num'=>$tranNum,
//                    'old_money'=>$data['lotmoney'],/*原金额*/
//                    'change_status'=>10,
//                    'change_money'=>-$data['amount'],
//                    'bet_money'=>$data['lotmoney']-$data['amount'],
//                    'remarks'=>'转入BBIN账户',
//                    'create_time'=>Carbon::now()->toDateTimeString()
//                ]);
//                if (!$result){
//                    throw new \Exception('生成帐变记录失败');
//                }
//                DB::commit();
//                adminSendUser('转账','已成功转入BBIN账户',auth('user')->user()->username);
//                return api_response(true,'','已成功转入BBIN账户');//错误
//            }
//            catch(\Exception $e)
//            {
//                DB::rollBack();
//                return api_response(false,'',$e->getMessage());//错误
//            }
//        }elseif ($data['action']=='OUT'){
//            /*转出AG账户 判断AG账户余额是否足够 添加彩票余额*/
////            if($cpAmount<$data['amount']) return api_response(false,'','彩票账户余额不足');
//            try
//            {
//                DB::beginTransaction();
//             //   $result = Account::query()->increment('remaining_money',$data['amount']);
////                if(!$result){
////                    throw new \Exception('转出余额失败');
////                }
//                $tranNum = time().rand(1000,9999);
//                $result = JournalAccount::query()->insert([
//                    'user_id'=>auth('user')->user()->user_id,
//                    'tran_num'=>$tranNum,
//                    'old_money'=>$data['lotmoney'],/*原金额*/
//                    'change_status'=>9,
//                    'change_money'=>$data['amount'],
//                    'bet_money'=>$data['lotmoney']+$data['amount'],
//                    'remarks'=>'转出BBIN账户',
//                    'create_time'=>Carbon::now()->toDateTimeString()
//                ]);
//                if (!$result){
//                    throw new \Exception('生成帐变记录失败');
//                }
//                $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id.'&remit='.$data['amount'].'&action='.$data['action'].'&remitno='.$tranNum;
//                $file_contents = file_get_contents($url);
//                if(!json_decode($file_contents,true)['result']){
//                    throw new \Exception('转出BBIN账户失败,请稍后再试');
//                }
//                DB::commit();
//                adminSendUser('转账','已成功转入BBIN账户',auth('user')->user()->username);
//                return api_response(true,'','已成功转入BBIN账户');//错误
//            }
//            catch(\Exception $e)
//            {
//                DB::rollBack();
//                return api_response(false,'',$e->getMessage());//错误
//            }
//        }
//    }

    /*查询订单状态 */
//    public function QueryOrderStatus($data){
//        $url = $this->host  .__FUNCTION__.'&username='.auth()->user()->user_id.'&transid'.$data['transid'];
//        return $this->url($url);
//    }
    public function Transfer($data){
        $user_id = auth('user')->user()->user_id;
        if($data['action']=='IN'){
            $url = $this->host  .__FUNCTION__.'&username='.$user_id.'&remit='.$data['amount'].'&action='.$data['action'].'&remitno='.$data['tranNum'];
            $file_contents = file_get_contents($url);
            if(!json_decode($file_contents,true)['result']){
                return api_response(false,'','转入BBIN账户失败,请稍后再试');//错误
            }
            return api_response(true,'','已成功转入BBIN账户');//错误
        }elseif ($data['action']=='OUT') {
            $url = $this->host . __FUNCTION__ . '&username=' .$user_id .'&remit=' . $data['amount'] . '&action=' . $data['action'] . '&remitno=' . $data['tranNum'];
            $file_contents = file_get_contents($url);
            if (!json_decode($file_contents, true)['result']) {
                return api_response(false, '', '转出BBIN账户失败,请稍后再试');//错误
            }
            return api_response(true, '', '已成功转入BBIN账户');//错误
        }
    }
    /*查询转账记录 */
    public function CheckTransfer($data){
        $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id.'&transid=1216'.$data['transid'];
        return $this->url($url);
    }

    /*获取投注记录 rounddate日期 gametype 分类 */
    public function BetRecord($data){
        $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id.'&transid'.$data['transid'].
        '&rType ='.$data['rType'].'&starttime='.$data['starttime'].'&endtime ='.$data['endtime'];
        return $this->url($url);
    }
    /*获取投注记录 rounddate日期 gametype 分类 */
    public function BetRecordResult($data){
        $url = $this->host  .__FUNCTION__.'&username='.auth('user')->user()->user_id.'&transid'.$data['transid'].
            '&rType ='.$data['rType'].'&starttime='.$data['starttime'].'&endtime ='.$data['endtime'];
        return $this->url($url);
    }
    /*获取后台线路余额*/
    public function GetLineBlance(){
        $url = $this->host  .__FUNCTION__;
        return $this->url($url);
    }
    /*获取转账记录*/
    /*获取后台线路余额*/
    public function CheckTransfe(){
        $url = $this->host  .__FUNCTION__;
        return $this->url($url);
    }



}