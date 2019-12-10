<?php

namespace App\Http\Controllers\Agent;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\Game;
use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\Profit;
use App\Models\Relation;
use App\Models\Admin;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Withdraw;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class agentManagerController  extends Controller
{
    public function agentCenter(Request $request) {
        $user_id = auth('agent')->user()->user_id;
        $agent_list = Agent::query()->with('uinfo')
                     ->select(['agent.login_state','userinfo.create_time as re_time','relation.invitation_num','userinfo.parent_user_id',
                         DB::raw('count(t_caimi_userinfo.parent_user_id) as children_user'),
                         'agent.id', 'agent.user_id','agent.username','agent.created_at','agent_info.share_percent',
                         'agent_info.valid_profit','agent_info.totle_profit'])
                     ->where('agent.leader_id' ,$user_id)
                     ->leftJoin('agent_info','agent.user_id','=','agent_info.user_id')
                     ->leftJoin('userinfo','agent.user_id','=','userinfo.parent_user_id')
                     ->leftJoin('relation','relation.user_id','=','agent.user_id')
                     ->groupBy('agent.user_id');
        if($request->input('user_id')) {
            $agent_list = $agent_list->where('agent.user_id' ,$request->input('user_id'));
        }
        if($request->input('username')) {
            $agent_list = $agent_list->where('agent.username' ,$request->input('username'));
        }
        switch ($request->input('column')) {
            case 'share_percent':
                $order = 'share_percent';
                break;
            case 'children_user':
                $order = 'children_user';
                break;
            case 'totle_profit':
                $order = 'totle_profit';
                break;
            case 'valid_profit':
                $order = 'valid_profit';
                break;
            case 'children_user':
                $order = 'children_user';
                break;
            default:
                $order = 'created_at';
                break;
        }

        $agent_list =  $agent_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(15);

        $children_userid_array = array_filter(array_column($agent_list->toArray()['data'], 'user_id'));
        $children_list = Agent::query()->select(['leader_id',DB::raw('count(t_caimi_agent.leader_id) as people')])
            ->whereIn('leader_id',$children_userid_array)
            ->groupBy('leader_id')
            ->get()->keyBy('leader_id')
            ->toArray();


        return view('agent.agentManager.agentCenter',[
            'agent_list' => $agent_list,
            'children_list' => $children_list
        ]);
    }
    public function editAgent(Request $request)
    {
        $user_id = $request->input('user_id');
        $agent_list = AgentInfo::query()->where('user_id' ,$user_id)->get()->toArray();
        return view('agent.agentManager.editAgent',[
            'agent_list' => $agent_list
        ]);
    }
    public function agent_loginState(Request $request){
        $user_id = $request->input('user_id');
        $login_state = $request->input('login_state');
        $agent =Agent::query()->where('user_id',$user_id);
        $up_arr = ['login_state'=>$login_state];

        if(!$agent->update($up_arr)){
            $retrun_arr = ['flag' => false, 'msg' => '状态修改失败，服务器出错'];
            return $retrun_arr;
        }
        $retrun_arr = ['flag' => true, 'msg' => '状态修改成功'];
        return $retrun_arr;
    }
    public function modifyAgent_ajax(Request $request)
    {
        $share = $request->input('share');
        $user_id = $request->input('user_id');
        if (!is_numeric($share)) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，请输入整数或小数字'];
            return $retrun_arr;
        }
        if ($share < 0) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，分润比不能低于0'];
            return $retrun_arr;
        }
        if ($share > 100) {
            $retrun_arr = ['flag' => false, 'msg' => '指定失败，分润比不能大于100'];
            return $retrun_arr;
        }
        $up_arr = ['share_percent'=>$share];
        $agent = AgentInfo::query()->where('user_id',$user_id)->update($up_arr);
        $retrun_arr = ['flag' => true, 'msg' => '修改成功'];
        return $retrun_arr;
    }
    public function agent_children(Request $request)
    {
        $leader_id = $request->input('leader_id');
        $user_list = BuyUser::query()
            ->with('agent')
            ->select(['userinfo.name','account.remaining_money','account.unliquidated_money','user.username', 'user.user_id', 'user.role_id', 'userinfo.parent_user_id', 'user.id', DB::raw('sum(result) as order_resule')])
            ->leftJoin('userinfo', 'user.user_id', '=', 'userinfo.user_id')
            ->leftJoin('role', 'user.role_id', '=', 'role.id')
            ->leftJoin('order', 'user.user_id', '=', 'order.user_id')
            ->leftJoin('account','user.user_id','=','account.user_id')
            ->where('userinfo.parent_user_id',$leader_id);

        if ($request->input('user_id')) {
            $user_list = $user_list->where('userinfo.user_id', $request->input('user_id'));
        }
        switch ($request->input('column')) {
            case 'order_resule':
                $order = 'order_resule';
                break;
            case 'unliquidated_money':
                $order = 'unliquidated_money';
                break;
            case 'remaining_money':
                $order = 'remaining_money';
                break;
            default:
                $order = 'user.id';
                break;
        }

        $user_list = $user_list ->groupBy('user.user_id')
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);
        $parent_userid_array = array_filter(array_column($user_list->toArray()['data'], 'parent_user_id'));

        $parent_userinfo = BuyUser::query()
            ->leftJoin('userinfo', 'user.user_id', '=', 'userinfo.user_id')
            ->whereIn('user.user_id', $parent_userid_array)
            ->get()->keyBy('user_id')->toArray();
        $parent_userinfo[235689] = ['name' => '系统'];

        return view('agent.agentManager.agent_children', [
            'user_list' => $user_list,
            'parent_userinfo' => $parent_userinfo,
        ]);
    }
    public function profit_detail(Request $request) {
        $leader_id= $request->input('leader_id');
        $profit_list = Profit::query()->with('user')->with('info')
                     ->leftJoin('order','profit.order_id','=','order.id')
                     ->where('agent_user_id',$leader_id);

        if($request->input('order_sn')) {
            $profit_list->where('order.order_sn',$request->input('order_sn'));
        }
        if($request->input('user_id')) {
            $profit_list->where('profit.user_id',$request->input('user_id'));
        }
        if($request->input('date_begin')) {
            $profit_list->whereDate('profit.created_at','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $profit_list->whereDate('profit.created_at','<=' ,$request->input('date_end'));
        }

        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'created_at':
                $order = 'profit.created_at';
                break;
            default:
                $order = 'profit.id';
                break;
        }
        $profit_list = $profit_list
                     ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
                     ->paginate(10);
        return view('agent.agentManager.profit_detail', [
            'profit_list' => $profit_list
        ]);
    }
    public function agent_profit(Request $request) {
        $leader_id = auth('agent')->user()->user_id;
        $profit_list = Profit::query()->with('info')->with('user')
            ->where('leader_user_id',$leader_id);

        if($request->input('agent_user_id')) {
            $profit_list->where('agent_user_id',$request->input('agent_user_id'));
        }
        if($request->input('order_sn')) {
            $profit_list->where('order.order_sn',$request->input('order_sn'));
        }
        if($request->input('user_id')) {
            $profit_list->where('profit.user_id',$request->input('user_id'));
        }
        if($request->input('date_begin')) {
            $profit_list->whereDate('profit.created_at','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $profit_list->whereDate('profit.created_at','<=' ,$request->input('date_end'));
        }

        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'created_at':
                $order = 'profit.created_at';
                break;
            default:
                $order = 'profit.id';
                break;
        }
        $profit_list = $profit_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);
        $agent_info = BuyUser::query()->with('info')->get()->keyBy('user_id')->toArray();
        return view('agent.agentManager.agent_profit', [
            'profit_list' => $profit_list,
            'agent_info' => $agent_info
        ]);
    }
    public function withdraw_verify(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_list = Withdraw::query()->with('info')->with('userbank.bankname')
                       ->where('leader_id',$agent_id)->where('status',0)->paginate(10);
        return view('agent.userManager.withdraw_verify',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }
    public function withdraw_paying() {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_list = Withdraw::query()->with('info')->with('userbank.bankname')
            ->where('leader_id',$agent_id)->where('status',1)->paginate(10);
        return view('agent.userManager.withdraw_paying',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }
    public function withdraw_payed() {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_list = Withdraw::query()->with('info')->with('userbank.bankname')
            ->orderBy('id', 'desc')
            ->where('leader_id',$agent_id)->where('status',2)->paginate(10);
        return view('agent.userManager.withdraw_payed',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }
    public function withdraw_back() {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_list = Withdraw::query()->with('user')->with('userbank.bankname')
            ->orderBy('id', 'desc')
            ->with('info')->where('leader_id',$agent_id)->where('status',3)->paginate(10);
        return view('agent.userManager.withdraw_back',[
            'withdraw_list'=> $withdraw_list,
        ]);
    }


    public function verify_excel(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_list = Withdraw::query()->with('info')
                        ->with('userbank.bankname')
                        ->where('leader_id',$agent_id)
                        ->where('status',0)->get()->toArray();

        $up_arr = ['status'=>1];
        $up_sql = Withdraw::query()->where('leader_id',$agent_id)->where('status',0)->update($up_arr);
        $data_list = [];
        $data_list[] = [
            '用户ID',
            '姓名',
            '提现订单号',
            '提现金额',
            '申请时间',
            '汇款银行',
            '汇款账号'
        ];

        foreach ($withdraw_list as $item){
            $data_list[] = [
                $item['user_id'],
                $item['info']['bank_account_name'],
                $item['withdraw_sn'],
                $item['amount'],
                $item['created_at'],
                $item['userbank']['bankname']['bank_name'].$item['userbank']['bank_branch'].'支行',
                $item['userbank']['account']
            ];
        }

        $title = '提现审核'.Carbon::now()->toDateString();
        $final_title = iconv('UTF-8', 'GBK', $title.'|'.date('Ymd'));

        Excel::create($final_title, function ($excel) use ($data_list) {
            $excel->sheet('提现审核', function($sheet) use($data_list) {
                $sheet->fromArray($data_list);
            });
        })->export('xls');
    }
    public function changeStatus_ajax(Request $request) {
        $withdraw_sn = $request->input('withdraw_sn');
        $change_sql = Withdraw::query()->where('withdraw_sn',$withdraw_sn)->update(['status'=>1]);
        $retrun_arr = ['flag' => true, 'msg' => '转处理成功'];
        return $retrun_arr;
    }
    public function backStatus_ajax(Request $request) {
        $withdraw_sn = $request->input('withdraw_sn');
        $withdraw__sql = Withdraw::query()->where('withdraw_sn',$withdraw_sn)->get()->toArray();
        $amount_mponey = $withdraw__sql[0]['amount'];
        $agent_id = $withdraw__sql[0]['user_id'];

        $agent_iist = AgentInfo::query()->where('user_id',$agent_id)->get()->toArray();
        $valid_profit = $agent_iist[0]['valid_profit']+$amount_mponey;
        $expend_profit = $agent_iist[0]['expend_profit']-$amount_mponey;
        $up_arr = ['valid_profit' => $valid_profit,'expend_profit'=>$expend_profit];
        $withdraw_up = ['status'=>3];

        try {
            DB::begintransaction();
            $agent_info = AgentInfo::query()->where('user_id',$agent_id);
            $withdraw_info = Withdraw::query()->where('withdraw_sn',$withdraw_sn);
            if(!$agent_info->update($up_arr)) {
                throw new \Exception("用户信息变更失败，请重试");
            }
            if(!$withdraw_info->update($withdraw_up)) {
                throw new \Exception("用户信息变更失败，请重试");
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['flag' => false, 'msg' => $e->getMessage()];
        }
        $retrun_arr = ['flag' => true, 'msg' => '撤销成功'];
        return $retrun_arr;
    }
    public function get_excel(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $withdraw_status = $request->input('withdraw_type');
        $withdraw_list = Withdraw::query()
                        ->with('userbank.bankname')
                        ->with('info')
                        ->where('leader_id',$agent_id)
                        ->where('status',$withdraw_status)
                        ->get()->toArray();
        $data_list = [];
        $data_list[] = [
            '用户ID',
            '姓名',
            '提现订单号',
            '提现金额',
            '申请时间',
            '汇款银行',
            '汇款账号'
        ];
        foreach ($withdraw_list as $item){
            $data_list[] = [
                $item['user_id'],
                $item['info']['bank_account_name'],
                $item['withdraw_sn'],
                $item['amount'],
                $item['created_at'],
                $item['userbank']['bankname']['bank_name'].$item['userbank']['bank_branch'].'支行',
                $item['userbank']['account']
            ];
        }
        if($withdraw_status==2) {
            $ti = '已提现';
        } elseif($withdraw_status==1) {
            $ti = '付款中';
        } else {
            $ti = '已撤销';
        }
        $title = $ti .Carbon::now()->toDateString();
        $final_title = iconv('UTF-8', 'GBK', $title.'|'.date('Ymd'));
        Excel::create($final_title, function ($excel) use ($data_list) {
            $excel->sheet('提现审核', function($sheet) use($data_list) {
                $sheet->fromArray($data_list);
            });
        })->export('xls');
    }
    public function payChange_status_ajax(Request $request) {
        $withdraw_sn = $request->input('withdraw_sn');
        $status = $request->input('status');
        $change_sql = Withdraw::query()->where('withdraw_sn',$withdraw_sn)->update(['status'=>$status]);
        if($status==2) {
            $retrun_arr = ['flag' => true, 'msg' => '转提现成功'];
            return $retrun_arr;
        } else {
            $retrun_arr = ['flag' => true, 'msg' => '转审核成功'];
            return $retrun_arr;
        }

    }
    public function submit_excel(Request $request){
        if (!$request->file('over_excel')->isValid()) {
            return ['success' => false, 'msg'=>'文件上传失败'];
        }

        if ($request->file('over_excel')->getClientOriginalExtension() != 'xls') {
            return ['success' => false, 'msg'=>'请上传后缀为.xls的excel文件'];
        }

        $path = $request->file('over_excel')->storeAs('inport', date('YmdHis').'-'.uniqid().'.xls');
        $result = Excel::load(Storage::url('app/'.$path))->get();
        $withdraw_sn_list = [];
        foreach ($result as $key=>$v) {
            if($key){
                $withdraw_sn_list[] = $v[2];
            }
        }
        if (Withdraw::query()->whereIn('withdraw_sn', $withdraw_sn_list)->where('status',1)->update(['status'=>2])) {
            return ['success' => true, 'msg'=>'导入成功'];
        }
        return ['success' => false, 'msg'=>'服务器错误，请重试'];
    }
    public function agent_income(Request $request) {
        $agent_id = auth('agent')->user()->user_id;
        $profit_list = Profit::query()->with('info')
            ->where('agent_user_id',$agent_id );
        if($request->input('agent_user_id')) {
            $profit_list->where('agent_user_id',$request->input('agent_user_id'));
        }

        if($request->input('user_id')) {
            $profit_list->where('profit.user_id',$request->input('user_id'));
        }
        if($request->input('date_begin')) {
            $profit_list->whereDate('profit.created_at','>=' ,$request->input('date_begin'));
        }
        if($request->input('date_end')) {
            $profit_list->whereDate('profit.created_at','<=' ,$request->input('date_end'));
        }

        if($request->input('date_begin')&&$request->input('date_end')) {
            $begin = $request->input('date_begin');
            $end = $request->input('date_end');
            if(strtotime($end)<strtotime($begin)) {
                $errors = ['error' => '查询时间有误'];
                return redirect()->back()
                    ->withErrors($errors);
            }
        }
        switch ($request->input('column')) {
            case 'created_at':
                $order = 'profit.created_at';
                break;
            default:
                $order = 'profit.id';
                break;
        }
        $profit_list = $profit_list
            ->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')
            ->paginate(10);
        return view('agent.agentManager.agent_income', [
            'profit_list' => $profit_list
        ]);
    }
    public function getAgent_detail(Request $request)
    {
        $agent_id= $request->input('agent_id');
        $agent_list = Agent::query()->with('info')->where('user_id',$agent_id)->get()->toArray();
        $user_info_list = UserInfo::query()->where('user_id',$agent_id)->get()->toArray();
        $relation_list = Relation::query()->where('user_id',$agent_id)->get()->toArray();
        $team_acount = UserInfo::query()
            ->select([DB::raw('count(t_caimi_userinfo.parent_user_id) as team_acount'),])
            ->where('parent_user_id',$agent_id)->get()->toArray();
        $agent_count = Agent::query() ->select([DB::raw('count(t_caimi_agent.leader_id) as agent_acount')])
            ->where('leader_id',$agent_id)
            ->get()->toArray();

        return view('agent.agentManager.getAgent_detail',[
            'agent_list'=> $agent_list,
            'user_info_list' => $user_info_list,
            'relation_list' => $relation_list,
            'team_acount' => $team_acount,
            'agent_acount' => $agent_count
        ]);
    }
}





