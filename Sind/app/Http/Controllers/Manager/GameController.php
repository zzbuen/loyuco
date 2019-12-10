<?php

namespace App\Http\Controllers\Manager;

use App\Jobs\GamePeriod;
use App\Jobs\OpenPrize;
use App\Jobs\ShareProfit;
use App\Models\Game;
use App\Models\GameConfig;
use App\Models\Goods;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function configList()
    {
        $config_list = GameConfig::query()
            ->leftJoin('game', 'game.id', '=', 'game_config.game_id')
            ->select(['*', 'game_config.id as id'])
            ->get()->toArray();
        return view('manager.game.config', ['config_list' => $config_list]);
    }

    public function createGoods(Request $request)
    {
        $game_list = Game::query()->get();
        if (empty($game_list)) {
            return '游戏不存在或未配置';
        }
        foreach ($game_list as $game) {
            $this->dispatch(new GamePeriod($game['id']));
        }
        return 'success';
    }

    public function modifyStatus(Request $request)
    {
        $game = Game::query()->find($request->input('game_id'));
        if (empty($game)) {
            return ['success' => false,'msg'=>'游戏不存在'];
        }
        if ($game->status == 1) {
            $game->status = 2;
        } else {
            $game->status = 1;
        }
        if ($game->save()) {
            return ['success' => true,'msg'=>'修改成功'];
        }
        return ['success' => false,'msg'=>'修改失败'];
    }
    /*取消预设*/
    public function unResup(Request $request)
    {
        $gameId = $request->input('game_id');
        $periods = $request->input('periods');
        $model = getTheGameTable($gameId);
        $result = $model->where('periods',$periods)->update([
            'result'=>null,
            'is_resup'=>0,
            'update_at'=>Carbon::now()->toDateTimeString()
        ]);
        if ($result) {
            return ['success' => true,'msg'=>'修改成功'];
        }
        return ['success' => false,'msg'=>'修改失败'];
    }


    public function config(Request $request)
    {
        $config = GameConfig::query()
            ->leftJoin('game', 'game.id', '=', 'game_config.game_id')
            ->select(['*', 'game_config.id as id'])
            ->find($request->input('config_id'));
        if ($request->isMethod('post')) {
            if (empty($config)) {
                return ['success' => false,'msg'=>'配置项不存在'];
            }
            $validator = Validator::make($request->all(), [
                //'source_name' => 'required|max:20',
                'daily_start' => 'required|date_format:H:i:s',
                'daily_end' => 'required|date_format:H:i:s',
                'period_time' => 'required|numeric',
                'period_bet_time' => 'required|numeric',
                'period_draw_time' => 'required|numeric',
                'refresh_time' => 'required|numeric',
                'delay_time' => 'required|numeric',
            ], [
                //'source_name.required'  => '请输入来源名称',
                //'source_name.max'  => '来源名称不能超过20个字符',
                'daily_start.required'  => '请输入每天开始时间',
                'daily_start.date_format'  => '每天开始时间格式错误',
                'daily_end.required'  => '请输入每天结束时间',
                'daily_end.date_format'  => '每天结束时间格式错误',
                'period_time.required'  => '请输入每期时长',
                'period_time.numeric'  => '每期时长请输入整数',
                'period_bet_time.required'  => '请输入每期投注时间',
                'period_bet_time.numeric'  => '每期投注时间请输入整数',
                'period_draw_time.required'  => '请输入每期开奖时间',
                'period_draw_time.numeric'  => '每期开奖时间请输入整数',
                'refresh_time.required'  => '请输入刷新时间',
                'refresh_time.numeric'  => '刷新时间请输入整数',
                'delay_time.required'  => '请输入封盘时间',
                'delay_time.numeric'  => '封盘时间请输入整数',
            ]);
            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }
            $config->source_name = $request->input('source_name');
            $config->weeks = implode(',', $request->input('weeks'));
            $config->daily_start = $request->input('daily_start');
            $config->daily_end = $request->input('daily_end');
            $config->period_time = $request->input('period_time');
            $config->period_bet_time = $request->input('period_bet_time');
            $config->period_draw_time = $request->input('period_draw_time');
            $config->refresh_time = $request->input('refresh_time');
            $config->delay_time = $request->input('delay_time');
            if ($config->save()) {
                return ['success' => true,'msg'=>'修改成功'];
            }
            return ['success' => false,'msg'=>'修改成功'];
        }
        if (empty($config)) {
            return '参数错误';
        }
        return view('manager.game.setconfig', ['config' => $config]);
    }
    public function balance(Request $request)
    {
        $config = GameConfig::query()->where('id',$request->input('config_id'))->first();

        if ($request->isMethod('post')) {
            if (empty($config)) {
                return ['success' => false,'msg'=>'配置项不存在'];
            }

            $config->balance_end = $request->input('balance_end');
            $config->balance_start = $request->input('balance_start');
            $config->balance = $request->input('balance');

            if ($config->save()) {
                return ['success' => true,'msg'=>'修改成功'];
            }
            return ['success' => false,'msg'=>'修改成功'];
        }
        if (empty($config)) {
            return '参数错误';
        }
        return view('manager.game.balance', ['config' => $config]);
    }

    /*开奖失败记录*/
    public function drawFailList(Request $request){
        if ($request->ajax()) {
            $fail = Goods::query()->with('game')->where('draw_status', 2)->first();
            if (empty($fail)) {
                return ['success'=>false];
            }
        }
        $game = Game::query()->get();
        $fail_list = Goods::query()
            ->with('game')
            ->where('draw_status', 2)
            ->orderBy("created_at","desc");

        /*游戏名称查询*/
        $game_id = $request->game_id;
        if($game_id){
            $fail_list = $fail_list->where("game_id",$game_id);
        }

        /*期数查询*/
        $period_number = $request->period_number;
        if($period_number){
            $fail_list = $fail_list->where("period_number",$period_number);
        }

        /*快捷选时*/
        $time= $request->time;
        if($time){
            $start_time = $this->near_time($time)[0];
            $end_time   = $this->near_time($time)[1];
            $fail_list       = $fail_list->whereDate("created_at",">=",date("Y-m-d H:i:s",$start_time))
                ->whereDate("created_at","<=",date("Y-m-d H:i:s",$end_time));
        }

        $near_time= $request->near_time;
        if($near_time){
            $start_time =  date("Y-m-d 00:00:00",strtotime(substr($near_time,0,10)));
            $end_time   =  date("Y-m-d 23:59:59",strtotime(substr($near_time,13)));
            $fail_list       = $fail_list->whereDate("draw_time",">=",$start_time)
                ->whereDate("draw_time","<=",$end_time);
        }

        $fail_list = $fail_list->paginate(10);

        return view('manager.game.fail_list', [
            'fail_list' => $fail_list,
            "game"      => $game,
            "game_id"   => $game_id,
            "period_number" => $period_number,
            "time"      => $time,
            "near_time"    =>$near_time
        ]);
    }


    /**
     * 作用：手动开奖
     * 作者：
     * 时间：2018/4/8
     * 修改：信
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function drawFixed(Request $request)
    {
        $good = Goods::query()->where('id', $request->input('id'))->where('draw_status', 2)->first();

        /*开奖结果长度*/
        $result_length = [
            1 => 5,
            2 => 5,
            3 => 5,
            4 => 5,
            5 => 5,
            6 => 5,
            7 => 5,
            8 => 5,
            9 => 5,
            10 => 5,
            11 => 5,
            12 => 5,
            13 => 5,
            14 => 3,
            15 => 3,
            16 => 10,
            17 => 10,
            18 => 3,
            19 => 3,
            20 => 3,
            21 => 7,
            22 => 3,
            23 => 20,
        ];


        if ($request->isMethod('POST')) {
            if (empty($good)) {
                return ['success' => false, 'msg' => '对应期数开奖未失败或不存在'];
            }

            if (count($request->input('draw'))!= $result_length[$good->game_id]) {

                return ['success' => false, 'msg' => '开奖数据有误，使用英文逗号","隔开的数字个数应为' . $result_length[$good->game_id]];
            }
            /*开奖号码*/
            $draw_number = implode(',', $request->input('draw'));


            try {
                /*开始事物*/
                DB::begintransaction();

/*                $draw_data = [
                    'game_id' => $good->game_id,
                    'period_number' => $good->period_number,
                    'draw_time' => $good->draw_time,
                    'draw_number' => $draw_number,
                    'created_at' => date('Y-m-d H:i:s'),
                ];*/
               $where["game_id"] = $good->game_id;
               $where["periods"] = $good->period_number;

               $data = [
                   "result" => $draw_number,
                   "res_status" => 2,
               ];
                $tableName = $this->tableName($good->game_id);

                if (!DB::table('draw_result_' .$tableName)->where($where)->update($data)) {
                    throw new \Exception('开奖结果记录失败');
                }

                $good->draw_status = 1;
                if (!$good->save()) {
                    throw new \Exception('商品信息变更失败');
                }
                $order_list = Order::query()
                    ->where('status', 0)
                    ->where('bet_period',$where["periods"])
                    ->where('gameId',$where["game_id"])
                    ->get()->toArray();
                foreach ($order_list as $item) {
                    Log::info('进入order-订单id为'.$item['id'].'开奖结果为:'.$draw_number);
                    dispatch(new OpenPrize($item,$draw_number));
                }
/*                dispatch(new ShareProfit($good->period_number, $good->id));
                $notify_data = [
                    'betPeriod' => (string)$good->period_number,
                    'gameId' => (string)$good->game_id
                ];
                $notify_url = 'http://127.0.0.1:8085/caimi/api/drawNotify';
                $notify_res = curl_post($notify_data, $notify_url);
                Log::info('manualDraw:' . json_encode($notify_data));*/
                DB::commit();
            } catch (\Exception $e) {
                /*事物回滚*/
                DB::rollBack();
                return $e->getMessage();
                return ['success' => false, 'msg' => $e->getMessage()];
            }
            return ['success' => true, 'msg' => '修复成功'];
        }

        if (empty($good)) {
            return '对应期数开奖未失败或不存在';
        }
        return view('manager.game.fixed', ['length' => $result_length[$good->game_id]]);
    }







    /**
     * 作用：近几天和时间
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $time
     * @return array
     */
    public function near_time($time){
        switch ($time){
            /*今天*/
            case 1:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', time()));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*昨天*/
            case 2:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-1 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', strtotime("-1 day",time())));
                break;
            /*近三天 */
            case 3:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-3 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*近七天 */
            case 7:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-7 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*近半月 */
            case 15:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-15 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
            /*近一月 */
            case 30:
                $startTime  =   strtotime(date('Y-m-d 00:00:00', strtotime("-30 day",time())));
                $endTime    =   strtotime(date('Y-m-d 23:59:59', time()));
                break;
        }
        return [$startTime,$endTime];
    }







    /**
     * 作用：表名
     * 作者：信
     * 时间：2018/4/8
     * 修改：暂无
     */
    public function tableName($game_id){
        switch ($game_id){
            case 1:
                return "chongqing";
                break;
            case 2:
                return "beijingpk";
                break;
            case 3:
                return "xinjiang";
                break;
            case 4:
                return "pcdd";
                break;
            case 5:
                return "jnd";
                break;
            case 6:
                return "beijing";
                break;
            case 7:
                return "hanguo";
                break;
            case 8:
                return "xinjiapo";
                break;
            case 9:
                return "tep";
                break;
            case 10:
                return "pailie5";
                break;
            case 11:
                return "shandong";
                break;
            case 12:
                return "shanghai";
                break;
            case 13:
                return "jiangxi";
                break;
            case 14:
                return "jiangsu";
                break;
            case 15:
                return "anhui";
                break;
            case 16:
                return "beijingpk";
                break;
            case 17:
                return "fenfenpk";
                break;

            case 18:
                return "fucai3d";
                break;
            case 19:
                return "fenfen3d";
                break;
            case 20:
                return "pailie3";
                break;
            case 21:
                return "liuhecai";
                break;
            case 22:
                return "pcdd";
                break;
            case 23:
                return "beijingkl8";
                break;
        }
    }



    public function  drawKaijiang(){
        return 1;
    }
}
