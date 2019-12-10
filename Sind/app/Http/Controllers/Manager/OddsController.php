<?php

namespace App\Http\Controllers\Manager;

use App\Models\AppSpecial;
use App\Models\Category;
use App\Models\DrawResultChongqing;
use App\Models\DrawResultTianjin;
use Illuminate\Support\Facades\DB;
use App\Models\Limit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\Odds;
use App\Models\TemporaryOdds;
use App\Models\Game;
use App\Models\GameType;
use Illuminate\Support\Facades\Session;

class OddsController  extends Controller
{




    /**
     * 作用：时时彩走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function zoushitu(Request $request){
        /*游戏所有类型*/
        $game_type = GameType::query()->get()->toArray();
        $game_type_id = $request->game_type_id?$request->game_type_id:1;
        $game = Game::query()->where("type",$game_type_id)->get()->toArray();
        $category = ["五星","四星","前三","中三","后三","前二","后二"];

        /*开奖数据*/
        $data = DrawResultChongqing::query()
            ->where("res_status",2)
            ->orderBy("id","desc");


        $new_number = $request->number;
        $time       = $request->time;
        $xing       = $request->xing?$request->xing:0;
        if($new_number){
            /*30期或50期*/
            if($new_number == 30 || $new_number == 50){
                $data   = $data->paginate($new_number);
            }
            /*近几天*/
            if($new_number == 1 || $new_number == 2 || $new_number == 5){
                $near_time  = $this->near_time($new_number);
                $start_time = $near_time[0];
                $end_time   = $near_time[1];
                $data       = $data->whereDate("kaijiang_time",">=",$start_time)
                                ->whereDate("kaijiang_time","<=",$end_time)
                                ->get()
                                ->toArray();
            }
        }

        /*日期范围*/
        if($time){
            $start_time = substr($time,0,10)." 00:00:00";
            $end_time   = substr($time,13)." 23:59:59";
            $data       = $data->whereDate("kaijiang_time",">=",$start_time)
                ->whereDate("kaijiang_time","<=",$end_time)
                ->get()
                ->toArray();
        }

        /*如果2个都没选择则初始化*/
        if(!$time && !$new_number){
            $data   = $data->paginate(30);
        }

        if($data){
            $number = substr_count($data[0]["result"],",")+1;
        }else{
            $number = 6;
        }
        /*开奖号码里显示的遗漏值*/
        /*最后一次循环下标例
        0：遗漏值
        1：平均总次数
        2：平均遗漏值
        3：最大遗漏值
        4：最大遗漏值保存最大值
        5：最大连出值
        6：最大连出值保存最大值
        7：最近中奖的号码
        */
        $arr = [];
        for($i=0;$i<$number;$i++){
            $arr[$i] = [];
            for($j=0;$j<10;$j++){
                $arr[$i][$j] = [];
                for($k=0;$k<8;$k++){
                    if($k==7){
                        $arr[$i][$j][$k] = 0;
                    }else{
                        $arr[$i][$j][$k] = 0;
                    }
                }
            }
        }

        /*分布号码开奖号码里显示的遗漏值*/
        /*最后一次循环下标例
        0：遗漏值（这里没用上）
        1：平均总次数
        2：平均遗漏值
        3：最大遗漏值
        4：最大遗漏值保存最大值
        5：最大连出值
        6：最大连出值保存最大值
        */
        $fenbu_total_arr = [];
        for($j=0;$j<10;$j++){
            $fenbu_total_arr[$j] = [];
            for($k=0;$k<7;$k++){
                $fenbu_total_arr[$j][$k] = 0;
            }
        }

        /*分布管理里显示的遗漏值*/
        $fenbu_arr = [];
        for($i=0;$i<10;$i++){
            $fenbu_arr[$i] = 0;
        }


        /*豹子遗漏值记录*/
        $baozi = [];
        for($i=0;$i<10;$i++){
            $baozi[$i] = 0;
        }
        /*组三遗漏值记录*/
        $zusan = [];
        for($i=0;$i<10;$i++){
            $zusan[$i] = 0;
        }
        /*组六遗漏值记录*/
        $zuliu = [];
        for($i=0;$i<10;$i++){
            $zuliu[$i] = 0;
        }

        /*对子遗漏值记录*/
        $duizi = [];
        for($i=0;$i<10;$i++){
            $duizi[$i] = 0;
        }

        /*跨度走势和前后二开奖号码里显示的遗漏值*/
        /*最后一次循环下标例
        0：遗漏值（这里没用上）
        1：平均总次数
        2：平均遗漏值
        3：最大遗漏值
        4：最大遗漏值保存最大值
        5：最大连出值
        6：最大连出值保存最大值
        */
        $er_arr = [];
        for($j=0;$j<10;$j++){
            $er_arr[$j] = [];
            for($k=0;$k<7;$k++){
                $er_arr[$j][$k] = 0;
            }
        }




        /*总期数*/
        $count_data = count($data);
        /*表头设置*/
        $header_name = ["万位","千位","百位","十位","个位"];
        return view("zoushitu",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "data"          => $data,
            "number"        => $number,
            "header_name"   => $header_name,
            "arr"           => $arr,
            "fenbu_arr"     => $fenbu_arr,
            "count_data"    => $count_data,
            "fenbu_total_arr"   => $fenbu_total_arr,
            "new_number"        => $new_number,
            "category"          => $category,
            "time"              => $time,
            "xing"              => $xing,
            "baozi"             => $baozi,
            "zusan"             => $zusan,
            "zuliu"             => $zuliu,
            "duizi"             => $duizi,
            "er_arr"            => $er_arr,
        ]);
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
                $startTime  =   date('Y-m-d 00:00:00', time());
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
            /*近2天*/
            case 2:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-2 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
            /*近五天 */
            case 5:
                $startTime  =   date('Y-m-d 00:00:00', strtotime("-5 day",time()));
                $endTime    =   date('Y-m-d 23:59:59', time());
                break;
        }
        return [$startTime,$endTime];
    }


    /**
     * 作用：赔率游戏信息显示
     * 作者：信
     * 时间：2018/3/29
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Odds(Request $request){
        /*获取所有的游戏信息*/
        $game_list = Game::query()->get()->keyBy('id')->toArray();
        /*获取当前选中的游戏ID*/
        $game_id = $request->input('game_id') ? $request->input('game_id') : "01";
        /*获取该游戏的所有分类*/
        $category = Odds::where("gameId",$game_id)->groupBy("cateId")->get()->toArray();
        /*获取当前选中的分类ID*/
        $category_id = $request->input('category_id') ? $request->input('category_id') : $category[0]['cateId'];
        $where["gameId"] = $game_id;
        $where["cateId"] = $category_id;
        $where["delete_time"] = 0;
        /*获取所有分类信息*/
        $odd_list = Odds::where($where)->get()->toArray();
        return view('manager.odds', [
            'category_list' => $category,
            'game_list' => $game_list,
            'odds_list' => $odd_list,
        ]);
    }


    /*
     * 修改赔率页面
     * */
    public function modify_odd(Request $request){
        $odd_id     = $request->input('odd_id');
        $odd_list   = Odds::find($odd_id)->toArray();
        $old_odd    = $odd_list;
        return view('manager.modify_odd', [
            'old_odd'   => $old_odd,
            'odd_id'    => $odd_id
        ]);
    }


    /*
     * 修改玩法赔率
     */
    public function mondify_ajax(Request $request){
        $odd_id     = $request->input('odd_id');
        $new_odd    = $request->input('new_odd');
        $new_odd2    = $request->input('new_odd2');
        $new_odd3    = $request->input('new_odd3');
        $new_odd4    = $request->input('new_odd4');
        $up_arr     = ['odds1' => $new_odd,'odds2' => $new_odd2,'odds3' => $new_odd3,'odds4' => $new_odd4];
        $updata_sql = Odds::query()->where('id', $odd_id)->update($up_arr);
        return $updata_sql;
    }


    /**
     * 作用：序列化游戏找旧赔率并将数据返回到修改页面
     * 作者：信
     * 时间：2018/3/29
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modify_odds(Request $request){
        $odd_id     = $request->input('odd_id');
        $key        = $request->input('key');
        $odd_list   = Odds::find($odd_id)->toArray();
        $old_odd    = unserialize($odd_list['odds'])[$key];
        return view('manager.modify_odds', [
            'old_odd'   => $old_odd,
            'odd_id'    => $odd_id,
            "key"       => $key,
        ]);
    }


    /**
     * 作用：修改序列化游戏玩法的赔率
     * 作者：信
     * 时间：2018/3/29
     * 修改：暂无
     * @param Request $request
     * @return int
     */
    public function mondify_odds_ajax(Request $request){
        $odd_id     = $request->input('odd_id');
        $new_odd    = $request->input('new_odd');
        $new_odd2    = $request->input('new_odd2');
        $key        = $request->input('key');
        $odd_list   = Odds::find($odd_id)->toArray();
        $old_odd    = unserialize($odd_list['odds']);
        $old_odd[$key][0] = $new_odd;
        $old_odd[$key][1] = $new_odd2;
        $old_odd    = serialize($old_odd);
        $up_arr     = ['odds' => $old_odd];
        $updata_sql = Odds::query()->where('id', $odd_id)->update($up_arr);
        return $updata_sql;
    }


    /** 所有游戏限额信息
     * 作用：
     * 作者：信
     * 时间：2018/3/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function limit(Request $request){
        $list = AppSpecial::query()->get()->toArray();
        return view('manager.limit', [
            'list' => $list
        ]);
    }


    /**
     * 作用：限额单个玩法信息并返回数据到页面
     * 作者：信
     * 时间：2018/3/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modify_limit(Request $request){
        $limit_id   = $request->input('limit_id');
        $limit_list = Odds::query()->find($limit_id)->toArray();
        return view('manager.modify_limit', [
            'limit_list' => $limit_list,
            'limit_id' => $limit_id
        ]);
    }


    /**
     * 作用：修改限额
     * 作者：信
     * 时间：2018/3/30
     * 修改：暂无
     * @param Request $request
     * @return int
     */
    public function mondify_limit_ajax(Request $request){
        $limit_id       = $request->input('limit_id');
        $single_lowest  = $request->input('single_lowest');
        $single_limit   = $request->input('single_limit');

        $up_arr = [
            'single_limit'  => $single_limit,
            'single_lowest' => $single_lowest,
        ];
        $limit_list = Odds::query()->where('id', $limit_id)->update($up_arr);
        return $limit_list;
    }


    /**
     * 作用：删除玩法
     * 作者：信
     * 时间：2018/3/30
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function del_limit_ajax(Request $request){
        $limit_id   = $request->input('limit_id');
        $res        = Odds::query()->where("id",$limit_id)->update(["delete_time"=>time()]);
        if($res){
            return ["code"=>1,"msg"=>"删除成功"];
        }
        return ["code"=>0,"msg"=>"删除失败，请稍后再试"];
    }


//    public function del_limit_ajax(Request $request)
//    {
//        $limit_id = $request->input('limit_id');
//        $find_bet = Bet::query()->where('limit_id',$limit_id)->get()->toArray();
//        if(!$find_bet) {
//            $limit = Limit::query()->where('id' ,$limit_id);
//            $limit = $limit->delete();
//            return  ['flag' => true, 'msg' => '删除成功'];
//        }
//        else{
//            try {
//                DB::begintransaction();
//                $limit = Limit::query()->where('id' ,$limit_id);
//                $bet = Bet::query()->where('limit_id',$limit_id);
//                $up_arr = ['limit_id'=>0];
//                if(!$limit->delete()){
//                    throw new \Exception("删除失败1，请重试");
//                }
//                if(!$bet->update($up_arr)) {
//                    throw new \Exception("删除失败2，请重试");
//                }
//                DB::commit();
//            } catch (\Exception $e) {
//                DB::rollBack();
//                return ['flag' => false, 'msg' => $e->getMessage()];
//            }
//            return  ['flag' => true, 'msg' => '删除成功'];
//        }
//    }


    public function add_play(Request $request){
        $game_list = Game::query()->get()->keyBy('id')->toArray();
        $game_id = $request->input('game_id') ? $request->input('game_id') : 1;

        return view('manager.add_play', [
            'game_list' => $game_list,
        ]);
    }
    public function add_limit_ajax(Request $request)
    {
        $game_id =$request->input('game_id');
        $play_name = $request->input('play_name');
        $single_lowest = $request->input('single_lowest');
        $current_limit = $request->input('current_limit');
        $single_limit = $request->input('single_limit');
        if($single_lowest>$single_limit) {
            $msg = [
                'flag' => false,
                'content' => '单注最低数量不能大于单注最高'
            ];
            return $msg;
        }
        if($current_limit<$single_limit) {
            $msg = [
                'flag' => false,
                'content' => '单注最高数量不能大于单期最高'
            ];
            return $msg;
        }
        $limit = new Limit();
        $limit->game_id = $game_id;
        $limit->name = $play_name;
        $limit->single_limit = $single_limit;
        $limit->single_lowest = $single_lowest;
        $limit->current_limit = $current_limit;
        if($limit->save()) {
            $msg = [
                'flag' => true,
                'content' => '添加玩法成功'
            ];
            return $msg;
        }else{
            $msg = [
                'flag' => false,
                'content' => '添加玩法失败'
            ];
            return $msg;
        }
    }
    public function option_limit(Request $request){
        $game_list = Game::query()->get()->keyBy('id')->toArray();
        $game_id = $request->input('game_id') ? $request->input('game_id') : 1;
        $bet_list = Bet::query()->with('category')->with('rule')->where('game_id', $game_id)->get()->groupBy('category_id')->toArray();

        $all_bet = [];
        foreach($bet_list as $key => $item) {
            $all_bet[$key]['category_info'] = $item[0]['category'];
            $all_bet[$key]['category_info']['rule'] = [];
            array_push($all_bet[$key]['category_info']['rule'],$item[0]['rule']);
            foreach ($item as $r_key=>$r_val)
            {
                for($i=0;$i<count($all_bet[$key]['category_info']['rule']);$i++) {
                    if($r_val['rule_id'] == $all_bet[$key]['category_info']['rule'][$i]['id']){
                        break;
                    }
                }
                if($i==count($all_bet[$key]['category_info']['rule'])) {
                    array_push($all_bet[$key]['category_info']['rule'],$r_val['rule']);
                }
            }
        }


        foreach($all_bet as $key=>$item){
            foreach ($item["category_info"]['rule'] as $k=>$v) {
            $sql = Bet::query()
                   ->where('game_id',$game_id)
                   ->where('category_id',$item['category_info']['id'])
                   ->where('rule_id' , $v['id'])->get()->toArray();
                $all_bet[$key]["category_info"]['rule'][$k]['name_info'] = $sql;
            }
        }
        return view('manager.option_limit', [
            'game_list' => $game_list,
            'game_id' => $game_id,
            'bet_list' => $all_bet
        ]);
    }
    public function option_limit_ajax(Request $request){
        $limit_id = $request->input('limit_id');
        $bet_id = $request->input('bet_id');
        $up_arr = ['limit_id'=>$limit_id];
        $limit_sql = Bet::query()->where('id',$bet_id)->update($up_arr);
        return $limit_sql;
    }


    /**
     * 作用：玩法分类管理
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @return int
     */
    public function fenleiguanli(Request $request){
        /*获取所有的游戏信息*/
        $game_list = Game::query()->get()->keyBy('id')->toArray();
        /*获取当前选中的游戏ID*/
        $game_id = $request->input('game_id') ? $request->input('game_id') : "01";
        /*获取该游戏的所有分类*/
        $category = Odds::where("gameId",$game_id)->groupBy("cateId")->get()->toArray();

        return view("manager.fenleiguanli",[
            "game_list" => $game_list,
            "game_id"   => $game_id,
            "category"  => $category
        ]);
    }


    /**
     * 作用：禁用游戏分类
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function jinyon_fenlei(Request $request){
        $data = $request->all();
        $where["gameId"]    = $data["game_id"];
        $where["typeId"]    = $data["type_id"];
        $where["cateId"]    = $data["cate_id"];
        $res = Odds::query()->where($where)->update(["status"=>2]);
        if($res){
            return ["code"=>1,"msg"=>"禁用成功"];
        }
        return ["code"=>0,"msg"=>"禁用失败"];
    }




    /**
     * 作用：启用游戏分类
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function qiyon_fenlei(Request $request){
        $data = $request->all();
        $where["gameId"]    = $data["game_id"];
        $where["typeId"]    = $data["type_id"];
        $where["cateId"]    = $data["cate_id"];
        $res = Odds::query()->where($where)->update(["status"=>1]);
        if($res){
            return ["code"=>1,"msg"=>"启用成功"];
        }
        return ["code"=>0,"msg"=>"启用失败"];
    }



}





