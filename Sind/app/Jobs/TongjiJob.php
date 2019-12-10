<?php

namespace App\Jobs;

use App\Classes\Scc;
use App\Models\DrawResultJnd;
use App\Models\DrawResultPcdd;
use App\Models\Tongji;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class TongjiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    //public $delay = [];
    public $gameId;
    public $result;
    public $periods;
    public function __construct($gameId,$result,$periods)
    {
        $this->gameId = $gameId;
        $this->result = $result;
        $this->periods = $periods;
        $this->queue = 'Tong';
    }

    public function handle()
    {
        Log::info('有没有进来统计1?'.$this->gameId);
        switch ($this->gameId){
            case 4:
                Log::info('有没有进来统计2?'.$this->gameId);
                $flagName = '';
                $list = Tongji::query()->where('game_id',4)->get()->toArray();
                $whereList = [];
                foreach ($list as $item){
                    $whereList[$item['key']] = $item['value']+1;
                }
                $Scc = new Scc();
                $fun_kj = 'kj_030101';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['da'] = 0;
                }else{
                    $whereList['xiao'] = 0;
                };
                $fun_kj = 'kj_030103';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['dan'] = 0;
                }else{
                    $whereList['shuang'] = 0;
                };
                $fun_kj = 'kj_030105';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['jida'] = 0;
                }
                $fun_kj = 'kj_030106';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['dadan'] = 0;
                    $flagName = '大单';
                }
                $fun_kj = 'kj_030107';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['xiaodan'] = 0;
                    $flagName = '小单';
                }
                $fun_kj = 'kj_030108';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['dashuang'] = 0;
                    $flagName = '大双';
                }
                $fun_kj = 'kj_030109';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['xiaoshuang'] = 0;
                    $flagName = '小双';
                }
                $fun_kj = 'kj_030110';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['jixiao'] = 0;
                }
                Log::info('有没有进来统计3?'.$this->gameId);
                Tongji::query()->where('game_id',4)->where('key','da')->update(['value'=>$whereList['da']]);
                Tongji::query()->where('game_id',4)->where('key','xiao')->update(['value'=>$whereList['xiao']]);
                Tongji::query()->where('game_id',4)->where('key','dan')->update(['value'=>$whereList['dan']]);
                Tongji::query()->where('game_id',4)->where('key','shuang')->update(['value'=>$whereList['shuang']]);
                Tongji::query()->where('game_id',4)->where('key','dadan')->update(['value'=>$whereList['dadan']]);
                Tongji::query()->where('game_id',4)->where('key','dashuang')->update(['value'=>$whereList['dashuang']]);
                Tongji::query()->where('game_id',4)->where('key','xiaodan')->update(['value'=>$whereList['xiaodan']]);
                Tongji::query()->where('game_id',4)->where('key','xiaoshuang')->update(['value'=>$whereList['xiaoshuang']]);
                Tongji::query()->where('game_id',4)->where('key','jida')->update(['value'=>$whereList['jida']]);
                Tongji::query()->where('game_id',4)->where('key','jixiao')->update(['value'=>$whereList['jixiao']]);
                Log::info('有没有进来统计4?'.$this->gameId);
                $kaijiang_time = DrawResultPcdd::query()->where('periods',$this->periods)->value('kaijiang_time');
                $list = Tongji::query()->where('game_id',4)->get()->toArray();
                $data = [
                    'type'=>'tongji',
                    'game_id'=>4,
                    'periods'=>$this->periods,
                    'lotteyNo'=>$this->result,
                    'data'=>$list,
                    'kaijiang_time'=>$kaijiang_time,
                    'result_name'=>$flagName,
                    'key' => '^manks.top&swoole$'
                ];
                $result = $this->postSwoole($data);
                Log::info('我第二次发给伟宏的:'.$result);
                return true;
                break;
            case 5:
                Log::info('有没有进来统计2?'.$this->gameId);
                $flagName = '';
                $list = Tongji::query()->where('game_id',5)->get()->toArray();
                $whereList = [];
                foreach ($list as $item){
                    $whereList[$item['key']] = $item['value']+1;
                }
                $Scc = new Scc();
                $fun_kj = 'kj_030101';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['da'] = 0;
                }else{
                    $whereList['xiao'] = 0;
                };
                $fun_kj = 'kj_030103';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['dan'] = 0;
                }else{
                    $whereList['shuang'] = 0;
                };
                $fun_kj = 'kj_030105';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['jida'] = 0;
                }
                $fun_kj = 'kj_030106';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['dadan'] = 0;
                    $flagName = '大单';
                }
                $fun_kj = 'kj_030107';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['xiaodan'] = 0;
                    $flagName = '小单';
                }
                $fun_kj = 'kj_030108';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['dashuang'] = 0;
                    $flagName = '大双';
                }
                $fun_kj = 'kj_030109';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['xiaoshuang'] = 0;
                    $flagName = '小双';
                }
                $fun_kj = 'kj_030110';
                $winNum =  $Scc->$fun_kj($this->result);
                if($winNum){
                    $whereList['jixiao'] = 0;
                }
                Log::info('有没有进来统计3?'.$this->gameId);
                Tongji::query()->where('game_id',5)->where('key','da')->update(['value'=>$whereList['da']]);
                Tongji::query()->where('game_id',5)->where('key','xiao')->update(['value'=>$whereList['xiao']]);
                Tongji::query()->where('game_id',5)->where('key','dan')->update(['value'=>$whereList['dan']]);
                Tongji::query()->where('game_id',5)->where('key','shuang')->update(['value'=>$whereList['shuang']]);
                Tongji::query()->where('game_id',5)->where('key','dadan')->update(['value'=>$whereList['dadan']]);
                Tongji::query()->where('game_id',5)->where('key','dashuang')->update(['value'=>$whereList['dashuang']]);
                Tongji::query()->where('game_id',5)->where('key','xiaodan')->update(['value'=>$whereList['xiaodan']]);
                Tongji::query()->where('game_id',5)->where('key','xiaoshuang')->update(['value'=>$whereList['xiaoshuang']]);
                Tongji::query()->where('game_id',5)->where('key','jida')->update(['value'=>$whereList['jida']]);
                Tongji::query()->where('game_id',5)->where('key','jixiao')->update(['value'=>$whereList['jixiao']]);
                Log::info('有没有进来统计4?'.$this->gameId);
                $kaijiang_time = DrawResultJnd::query()->where('periods',$this->periods)->value('kaijiang_time');
                $list = Tongji::query()->where('game_id',5)->get()->toArray();
                $data = [
                    'type'=>'tongji',
                    'game_id'=>5,
                    'periods'=>$this->periods,
                    'lotteyNo'=>$this->result,
                    'data'=>$list,
                    'kaijiang_time'=>$kaijiang_time,
                    'result_name'=>$flagName,
                    'key' => '^manks.top&swoole$'
                ];
                $result = $this->postSwoole($data);
                Log::info('我把统计发给伟宏:'.$result.$this->periods);
                return true;
                break;
        }
        return true;
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
        $post_data = $data;
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
