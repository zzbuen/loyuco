<?php

namespace App\Jobs;
use App\Models\Odds;
use App\Classes\Scc;
use App\Models\DrawResultChongqing;
use App\Models\DrawResultJisupk;
use App\Models\DrawResultJisussc;
use App\Models\DrawResultJnd;
use App\Models\DrawResultfucai;
use App\Models\DrawResultPCDD;
use App\Models\DrawResultBeijingpk;
use App\Models\DrawResultTenxun;
use App\Models\DrawResultXyft;
use App\Models\GameConfig;
use App\Models\Goods;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GetDrawResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $periods;
    public $goods_id;
    public $kaijiang_time;
    public $tries = 40;
    public $timeout = 60;

    public function __construct($periods,$goods_id,$kaijiang_time)
    {
        $this->periods = $periods;/*期数*/
        $this->goods_id = $goods_id;/*游戏id*/
        $this->kaijiang_time = $kaijiang_time;

        //$this->queue = 'getDrawResult';
    }

    public function handle()
    {
        Log::info('第'.$this->attempts().'次,进入开奖结果脚本获取,游戏id:'.$this->goods_id.'/期号:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
        $lotteyNo = 0;
        $resultflag = 0 ; /*判断是否开奖 0未中奖 1中奖*/
        $allGameList = getTheGameTable($this->goods_id);
        /*进入脚本先验证下 该期号是否开过奖 是否已插入开奖结果*/
        $resList = $allGameList->where('periods',$this->periods)->first();
        Log::info($resList);
        if($resList['res_status']!=1){
            Log::info('数据库该期不为:未开奖,退出队列任务 ! 游戏id:'. $this->goods_id.'/期号:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
            return true;
        }
        if($resList['result']!=null){
            $lotteyNo = $resList['result'];
            $resultflag = 1;
            $allGameList = getTheGameTable($this->goods_id);
            $result = $allGameList->where('periods',$this->periods)
                ->update(['res_status'=>2,'update_at'=>Carbon::now()]);
            if(!$result) return true;
        }
        else{
            /*验证一下是否超过开奖后的10分钟*/
            $allGameList = getTheGameTable($this->goods_id);
            $kaijiangList = $allGameList->where('periods',$this->periods)->first();
            $configPt = GameConfig::query()->where('game_id',$this->goods_id)->value('period_time');
            if(strtotime($kaijiangList['kaijiang_time']) == strtotime(Carbon::now()->toDateString().'20:00:00')&&$this->goods_id==5){
                Log::info('当前为加拿大28的当天最后一期,不判断为开奖失败!'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
            }else{
                if((strtotime($kaijiangList['kaijiang_time']) < time()-$configPt*10)){
                    $allGameList = getTheGameTable($this->goods_id);
                    $allGameList->where('periods',$this->periods)->update(['res_status'=>3]);
                    Log::info('超过10期,修改为开奖失败状态,游戏ID:'. $this->goods_id.'/期号:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
                    $this->kj_lost($this->goods_id,$this->periods);
                    return true;
                }
            }
            $kjCount = 1;
            switch ($this->goods_id){
                case 1:
                        Log::info('准备get重庆时时彩,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
                        $url = 'http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=cqssc&format=json';
                        $content = file_get_contents($url);
                        Log::info('结束get重庆时时彩,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
                       /* $content = $this->postUrl("http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=cqssc&format=json");*/
                        $content = json_decode($content,true);
                        if(is_array($content['data'])){
                            foreach ($content['data'] as $item){
                                if ($item['expect']== $this->periods){
                                    $result = DrawResultChongqing::query()->where('periods',$this->periods)->update([
                                        'res_status'=>2,
                                        'result'=>$item['opencode']
                                    ]);
                                    if(!$result){
                                        Log::info('更新重庆时时彩开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                                        break;
                                    }
                                    $lotteyNo = $item['opencode'];
                                    $resultflag = 1;
                                    break;
                                }
                            }
                        }
                        break;
                case 2:
                    Log::info('准备get北京PK10,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
                    //$url = 'http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=bjpk10&format=json';
                      $url = 'http://api.b1api.com/api?p=json&t=bjpk10&limit=20&token=59C229EBCCC6CA6D';
                    $content = file_get_contents($url);
                    Log::info('结束get北京PK10,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);

                    /*$content = $this->postUrl("http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=bjpk10&format=json");*/
                    $content = json_decode($content,true);
                    if(is_array($content['data'])) {
                        foreach ($content['data'] as $item) {
                            if ($item['expect'] == $this->periods) {
                                $result = DrawResultBeijingpk::query()->where('periods', $this->periods)->update([
                                    'res_status' => 2,
                                    'result' => $item['opencode']
                                ]);
                                if (!$result) {
                                    Log::info('更新北京PK10失败' . $this->goods_id . '/' . $this->periods . '/' . $this->kaijiang_time);
                                    break;
                                }
                                $lotteyNo = $item['opencode'];
                                $resultflag = 1;
                                break;
                            }
                        }
                    }
                    break;
                case 3:
                    Log::info('准备get幸运飞艇,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
                    $url = 'http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=mlaft&rows=10&format=json';
                    $content = file_get_contents($url);
                    Log::info('停止get幸运飞艇,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
                    /* $content = $this->postUrl("http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=mlaft&rows=10&format=json");*/
                    $content = json_decode($content,true);
                    if(is_array($content['data'])){
                        foreach ($content['data'] as $item){
                            if ($item['expect']== $this->periods){
                                $result = DrawResultXyft::query()->where('periods',$this->periods)->update([
                                    'res_status'=>2,
                                    'result'=>$item['opencode']
                                ]);
                                if(!$result){
                                    Log::info('更新幸运飞艇失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                                    break;
                                }
                                $lotteyNo = $item['opencode'];
                                $resultflag = 1;
                                break;
                            }
                        }
                    }
                    break;
                case 4:
//                    Log::info('准备get幸运28,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
//                    $url = 'http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=bjkl8&format=json&rows=20';
//                    $content = file_get_contents($url);
//                    Log::info('停止get幸运28,期号为:'.$this->periods.'/开奖时间:'.$this->kaijiang_time);
//                    /*$content = $this->postUrl("http://c.apiplus.net/newly.do?token=t7f04e0f3c14e0e8ek&code=bjkl8&format=json&rows=20");*/
//                    $content = json_decode($content,true);
//                    if(is_array($content['data'])){
//                        foreach ($content['data'] as $item){
//                            if ($item['expect']==$this->periods) {
//                                $kj = substr($item['opencode'],0,59);
//                                $kjs = explode(',',$kj);
//                                sort($kjs);
//                                $num1 = substr($kjs[0]+$kjs[1]+$kjs[2]+$kjs[3]+$kjs[4]+$kjs[5],-1,1);
//                                $num2 = substr($kjs[6]+$kjs[7]+$kjs[8]+$kjs[9]+$kjs[10]+$kjs[11],-1,1);
//                                $num3 = substr($kjs[12]+$kjs[13]+$kjs[14]+$kjs[15]+$kjs[16]+$kjs[17],-1,1);
//                                $code = $num1.','.$num2.','.$num3;
//                                $result = DrawResultPCDD::query()->where('periods', $this->periods)->update([
//                                    'res_status' => 2,
//                                    'result' => $code
//                                ]);
//                                if(!$result){
//                                    Log::info('更新PC蛋蛋失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
//                                    break;
//                                }
//                                $lotteyNo = $code;
//                                dispatch(new TongjiJob($this->goods_id,$lotteyNo,$this->periods));
//                                $resultflag = 1;
//                                break;
//                            }
//                        }
//                    }
//                    break;
                    $code = $this->simulate(10);
                    if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
                        Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$code);
                        return true;
                    }
                    Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);
                    $result = DrawResultPCDD::query()->where('periods',$this->periods)->update([
                        'res_status'=>2,
                        'result'=> $code,
                        'update_at' => now()->toDateTimeString()
                    ]);

                    if(!$result){
                        Log::info('pcdd开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                        break;
                    }
                    $lotteyNo = $code;
                    dispatch(new TongjiJob($this->goods_id,$lotteyNo,$this->periods));
                    $resultflag = 1;
                    break;
                case 5:
                    $code = $this->simulate(10);
                    if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
                        Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$code);
                        return true;
                    }
                    Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);
                    $result = DrawResultJnd::query()->where('periods',$this->periods)->update([
                        'res_status'=>2,
                        'result'=> $code,
                        'update_at' => now()->toDateTimeString()
                    ]);

                    if(!$result){
                        Log::info('更新加拿大开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                        break;
                    }
                    $lotteyNo = $code;
                    dispatch(new TongjiJob($this->goods_id,$lotteyNo,$this->periods));
                    $resultflag = 1;
                    break;
                case 6:
//                    $url = 'http://api.b1cp.com/api?p=json&t=txffc&limit=10&token=69d7d95499439349';
//                    $content = file_get_contents($url);
//                    $content = json_decode($content,true);
//                    if(is_array($content['data'])) {
//                        foreach ($content['data'] as $item){
//                            if ($item['expect']==$this->periods){
//                                if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
//                                    Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$item['opencode']);
//                                    return true;
//                                }
//                                Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);
//                                $result = DrawResultTenxun::query()->where('periods',$this->periods)->update([
//                                    'res_status'=>2,
//                                    'result'=>$item['opencode']
//                                ]);
//                                if(!$result){
//                                    Log::info('更新腾讯分分彩彩开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
//                                    break;
//                                }
//                                $lotteyNo = $item['opencode'];
//                                $resultflag = 1;
//                                break;
//                            }
//                        }
//                    }
//                    break;
                    $code = $this->getNewLoNum($this->goods_id,$this->periods,2);
                    if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
                        Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$code);
                        return true;
                    }
                    Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);
                    $result = DrawResultTenxun::query()->where('periods',$this->periods)->update([
                        'res_status'=>2,
                        'result'=> $code
                    ]);
                    if(!$result){
                        Log::info('更新QQ时时彩开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                        break;
                    }
                    $lotteyNo = $code;
                    $resultflag = 1;
                    break;
                case 7:
                    $code = $this->getNewLoNum($this->goods_id,$this->periods,5);
                    if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
                        Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$code);
                        return true;
                    }
                    Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);
                    $result = DrawResultJisupk::query()->where('periods',$this->periods)->update([
                        'res_status'=>2,
                        'result'=> $code
                    ]);

                    if(!$result){
                        Log::info('更新极速pk开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                        break;
                    }
                    $lotteyNo = $code;
                    $resultflag = 1;
                    break;
                case 8:
                    $code = $this->getNewLoNum($this->goods_id,$this->periods,2);
                    if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
                        Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$code);
                        return true;
                    }
                    Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);
                    $result = DrawResultJisussc::query()->where('periods',$this->periods)->update([
                        'res_status'=>2,
                        'result'=> $code
                    ]);

                    if(!$result){
                        Log::info('更新极速时时彩开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                        break;
                    }
                    $lotteyNo = $code;
                    $resultflag = 1;
                    break;
                case 9:
                    $code = $this->getNewLoNum($this->goods_id,$this->periods,9);
                    if( Cache::store('redisCache')->get('GetDrawResultGame'.$this->goods_id.$this->periods)){
                        Log::info('该游戏已有开奖结果,退出!'.$this->goods_id.'/'.$this->periods.'/'.$code);
                        return true;
                    }
                    Cache::store('redisCache')->add('GetDrawResultGame'.$this->goods_id.$this->periods,1,1);

                    $result = DrawResultfucai::query()->where('periods',$this->periods)->update([
                        'res_status'=>2,
                        'result'=> $code,
                        'update_at' => now()->toDateTimeString()
                    ]);
                    if(!$result){
                        Log::info('更新福彩28开奖结果失败'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time);
                        break;
                    }
                    $lotteyNo = $code;
                    dispatch(new TongjiJob($this->goods_id,$lotteyNo,$this->periods));
                    $resultflag = 1;
                    break;
            }
        }
        if($resultflag==0){
            Log::info('暂未找到开奖记录,游戏ID为:'.$this->goods_id.'/期号为:'.$this->periods.'/队列执行第'.$this->attempts().'次');
            if ($this->attempts()==45) {
                Log::info('队列执行第'.$this->attempts().'次!准备修改开奖状态为失败!');
                if(strtotime($kaijiangList['kaijiang_time']) == strtotime(Carbon::now()->toDateString().'20:00:00')&&$this->goods_id==5){
                    return true;
                }
                $allGameList = getTheGameTable($this->goods_id);
                $allGameList->where('periods',$this->periods)->update(['res_status'=>3]);
                Log::info('修改为开奖失败状态'. $this->goods_id.'/'.$this->periods.'/'.$this->kaijiang_time.'/第'.$this->attempts().'次.');
                //$this->kj_lost($this->goods_id,$this->periods);
                return true;
            }else{
                $this->release(5);
            }
        }else{
            $data = [
                'type'=>'award',
                'game_id'=>$this->goods_id,
                'periods'=>$this->periods,
                'lotteyNo'=>$lotteyNo
            ];
            $result = $this->postSwoole($data);
            Log::info('我第一次发给伟宏的:'.$result.$this->periods);
            Log::info('开奖成功:准备判断中奖'.$this->goods_id.'-'.$this->periods.'-'.$lotteyNo);
            $order_list = Order::query()
                ->where('status', 0)
                ->where('bet_period','=',$this->periods)
                ->where('gameId',$this->goods_id)
                ->where('delete_time',0)
                ->get()->toArray();
            $wHcount = 0;
            foreach ($order_list as $item) {
                Log::info('进入order-订单id为'.$item['id'].'开奖结果为:'.$lotteyNo);
                dispatch(new OpenPrize($item,$lotteyNo));
                $wHcount ++;
            }
            sleep(5);
            if($wHcount!=0){
                $data = [
                    'type'=>'awardOrder',
                    'game_id'=>$this->goods_id,
                    'periods'=>$this->periods,
                    'lotteyNo'=>$lotteyNo,
                    'key' => '^manks.top&swoole$'
                ];
                $result = $this->postSwoole($data);
                Log::info('我第二次发给伟宏的:'.$result.$this->periods);
            }
            return true;
        }
    }
    /*通知端口*/
    public function postSwoole($data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://0.0.0.0:9502");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1); //设置为POST方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //设置post数据
        $post_data = [
            'periods'=> $data['periods'],
            'game_id'=> $data['game_id'],
            'type'=>$data['type'],
            'lotteyNo'=>$data['lotteyNo'],
            'key' => '^manks.top&swoole$'
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    //自定义开奖结果
    protected function simulate($type)
    {
        switch ($type) {
            case 1:
                $numbers = range(1,10);
                shuffle($numbers);
                $draw_number = implode(',', $numbers);
                break;
            case 2:
                $numbers = [];
                for ($i=0; $i<5; $i++) {
                    $numbers[] = mt_rand(0, 9);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 3:
                $numbers = range(1,80);
                shuffle($numbers);
                $new_numbers = array_slice($numbers, 0, 20);
                sort($new_numbers);
                $draw_number = implode(',', $new_numbers);
                break;
            case 4:
                $numbers = range(1,20);
                shuffle($numbers);
                $draw_number = implode(',', array_slice($numbers, 0, 8));
                break;
            case 5:
                /*11选5*/
                $numbers = range (1,11);
                shuffle ($numbers);
                $numbers = array_slice($numbers, 0, 5);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            case 6:
                /*快三随机*/
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(1,6);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 7:
                /*pk10随机数*/
                $numbers = range (1,10);
                shuffle ($numbers);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            case 8:
                /*3D随机数生成*/
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(0,9);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 9:
                /*六合彩*/
                $numbers = range (1,49);
                shuffle ($numbers);
                $numbers = array_slice($numbers, 0, 7);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            case 10:
                /*pc蛋蛋*/
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(0,9);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 11:
                /*pk10随机数*/
                $numbers = range (1,80);
                shuffle ($numbers);
                $numbers = array_slice($numbers, 0, 20);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            default:
                $draw_number = 123456;
                break;
        }
        return $draw_number;
    }
    /*postUrl*/
    public function postUrl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1); //设置为POST方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    /*开奖失败操作*/
    public function kj_lost($goods_id,$periods){
        $allGameList = getTheGameTable($goods_id);
        $list = $allGameList->where('periods',$periods)->first();
        $result = Goods::query()->insert([
            'game_id'=>$this->goods_id,
            'period_number'=>$periods,
            'start_time'=>$list['start_time'],
            'end_time'=>$list['bet_end_time'],
            'bet_end_time'=>$list['bet_end_time'],
            'draw_time'=>$list['kaijiang_time'],
            'status'=>1,
            'draw_status'=>2,
            'created_at'=>now()->toDateTimeString(),
            'updated_at'=>now()->toDateTimeString()
        ]);
        if($result) return 1;
        return 0;
    }
    /*获取只赚不亏的开奖号码*/
    public function getNewLoNum($game_id,$periods,$n){
        $res=GameConfig::query()->where('game_id',$game_id)->first();
        $h=date('H');
        Log::info("H---".$h);
        Log::info("game_id".$game_id);
        Log::info("periods".$periods);
        Log::info("balance".$res['balance']);
        $h=(int)$h;
        Log::info("H+++".$h);
        Log::info("balance_start+++".$res['balance_start']);
        Log::info("balance_end+++".$res['balance_end']);
        $balance=$res['balance'];
        $suiji=rand(0,100);
        Log::info("suiji+++".$suiji);
        $t=0;
        if($suiji<$balance){
            $t=1;
        }
        if($h>=$res['balance_start'] && $h<=$res['balance_end'] && $t==1)
        {
            $order_list = Order::query()
                ->where('status', 0)
                ->where('bet_period','=',$periods)
                ->where('gameId',$game_id)
                ->where('delete_time',0)
                ->get()->toArray();
            $flag = 0;
            $pross=[];
            do {
                $flag++;

                $lotteyNo = $this->newLo($n);
                $orderMoney = 0;//下注金额
                $totalMoney = 0;//中奖金额
                foreach ($order_list as $item) {
                    $orderMoney += $item['bet_money'];
                    $totalMoney += $this->getTheMoney($item,$lotteyNo);
                }
                Log::info('flag'.$flag.'/'.$game_id.'/'.$periods.'/下注金额:'.$orderMoney.'/中奖金额'.$totalMoney.'/号码'.$lotteyNo);
                $profit=$orderMoney-$totalMoney;
                $pross["$lotteyNo"]=$profit;
            }while ($flag<10);
            arsort($pross);

            foreach($pross as $kkk=>$vvv){
                $lotteyNo=$kkk;
                break;
            }
            Log::info("periods".$lotteyNo);
            return $lotteyNo;
        }
        else{

            $lotteyNo = $this->newLo($n);
            Log::info("periods".$lotteyNo);

            return $lotteyNo;
        }

    }
    /*获取中奖金额*/
    public function getTheMoney($data,$lotteryNo){
        $Scc = new Scc();
        $fun_kj = 'kj_'.substr($data['serial_num'],2);
        $result = $Scc->$fun_kj($lotteryNo,$data['position']-1,$data['bet_value']);
//        $odds = Odds::query()->where('serial_num',$data['serial_num'])->value('odds1');
        $Odds = Odds::query()->where('serial_num',$data['serial_num']);
        $odds = $Odds->value('odds'.$data['room_type']);
        Log::info("赔率++++:".'odds'.$data['room_type']);

        if($result){
            $winBonus = round($odds * $data['bet_money'],3);
            Log::info("中奖++++:".$winBonus);

            return $winBonus;
        }else{
            return 0;
        }
    }
    //自定义开奖结果
    public function newLo($game_id)
    {
        switch ($game_id){
            case 1:
                $type = 2;
                break;
            case 2:
                $type = 2;
                break;
            case 3:
                $type = 2;
                break;
            case 4:
                $type = 2;
                break;
            case 5:
                $type = 1;
                break;
            case 6:
                $type = 1;
                break;
            case 7:
                $type = 1;
                break;
            case 8:
                $type = 10;
                break;
            case 9:
                $type = 10;
                break;
            case 10:
                $type = 4;
                break;
            case 11:
                $type = 6;
                break;
            case 12:
                $type = 9;
                break;
        }

        switch ($type) {
            case 1:
                /*北京pk10*/
                $numbers = range(1,10);
                shuffle($numbers);
                $draw_number = implode(',', $numbers);
                break;
            case 2:
                /*时时彩*/
                $numbers = [];
                for ($i=0; $i<5; $i++) {
                    $numbers[] = mt_rand(0, 9);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 3:
                /*80个号码*/
                $numbers = range(1,80);
                shuffle($numbers);
                $new_numbers = array_slice($numbers, 0, 20);
                sort($new_numbers);
                $draw_number = implode(',', $new_numbers);
                break;
            case 4:
                /*广东快乐10分*/
                $numbers = range(1,20);
                shuffle($numbers);
                $draw_number = implode(',', array_slice($numbers, 0, 8));
                break;
            case 5:
                /*11选5*/
                $numbers = range (1,11);
                shuffle ($numbers);
                $numbers = array_slice($numbers, 0, 5);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            case 6:
                /*快三随机*/
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(1,6);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 7:
                /*pk10随机数*/
                $numbers = range (1,10);
                shuffle ($numbers);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            case 8:
                /*3D随机数生成*/
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(0,9);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 9:
                /*六合彩*/
                $numbers = range (1,49);
                shuffle ($numbers);
                $numbers = array_slice($numbers, 0, 7);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            case 10:
                /*pc蛋蛋*/
                $numbers = [];
                for ($i=0; $i<3; $i++) {
                    $numbers[] = mt_rand(0,9);
                }
                $draw_number = implode(',', $numbers);
                break;
            case 11:
                /*pk10随机数*/
                $numbers = range (1,80);
                shuffle ($numbers);
                $numbers = array_slice($numbers, 0, 20);
                foreach ($numbers as $k => $v) {
                    if(strlen($v)==1) $numbers[$k] = '0'.$v;
                }
                $draw_number = implode(',',$numbers);
                break;
            default:
                $draw_number = 123456;
                break;
        }
        return $draw_number;
    }
}

