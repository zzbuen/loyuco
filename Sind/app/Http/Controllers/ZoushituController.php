<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrawResultAnhui;
use App\Models\DrawResultBeijing;
use App\Models\DrawResultBeijingkl8;
use App\Models\DrawResultBeijingpk;
use App\Models\DrawResultChongqing;
use App\Models\DrawResultFenfen3d;
use App\Models\DrawResultFenfenpk;
use App\Models\DrawResultFucai3d;
use App\Models\DrawResultGuangdong;
use App\Models\DrawResultHanguo;
use App\Models\DrawResultHenei;
use App\Models\DrawResultJiangsu;
use App\Models\DrawResultJiangxi;
use App\Models\DrawResultLiuhecai;
use App\Models\DrawResultOuzhou;
use App\Models\DrawResultPailie3;
use App\Models\DrawResultPcdd;
use App\Models\DrawResultShandong;
use App\Models\DrawResultShanghai;
use App\Models\DrawResultTengxun;
use App\Models\DrawResultTianjin;
use App\Models\DrawResultXinjiang;
use App\Models\DrawResultXinjiapo;
use App\Models\Game;
use App\Models\GameType;

class ZoushituController  extends Controller
{
    /**
     * 作用：时时彩走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shishicai(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:1;
        $game_id   = $request->game_id?$request->game_id:1;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();
        $category       = ["五星","四星","前三","中三","后三","前二","后二"];

        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
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
        return view("zoushitu.zoushitu",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
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
     * 作用：11选5走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function xuan5(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:2;
        $game_id   = $request->game_id?$request->game_id:10;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();
        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
            ->where("res_status",2)
            ->orderBy("id","desc");


        $new_number = $request->number;
        $time       = $request->time;
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
            for($j=0;$j<12;$j++){
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
        for($j=0;$j<12;$j++){
            $fenbu_total_arr[$j] = [];
            for($k=0;$k<7;$k++){
                $fenbu_total_arr[$j][$k] = 0;
            }
        }

        /*分布管理里显示的遗漏值*/
        $fenbu_arr = [];
        for($i=0;$i<12;$i++){
            $fenbu_arr[$i] = 0;
        }


        /*总期数*/
        $count_data = count($data);
        /*表头设置*/
        $header_name = ["一位","二位","三位","四位","五位"];
        return view("zoushitu.xuan5",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
            "data"          => $data,
            "number"        => $number,
            "header_name"   => $header_name,
            "arr"           => $arr,
            "fenbu_arr"     => $fenbu_arr,
            "count_data"    => $count_data,
            "fenbu_total_arr"   => $fenbu_total_arr,
            "new_number"        => $new_number,
            "time"              => $time,
        ]);
    }



    /**
     * 作用：快三走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function kuaisan(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:3;
        $game_id   = $request->game_id?$request->game_id:14;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();
        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
            ->where("res_status",2)
            ->orderBy("id","desc");


        $new_number = $request->number;
        $time       = $request->time;
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
            for($j=0;$j<7;$j++){
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
        for($j=0;$j<7;$j++){
            $fenbu_total_arr[$j] = [];
            for($k=0;$k<7;$k++){
                $fenbu_total_arr[$j][$k] = 0;
            }
        }

        /*分布管理里显示的遗漏值*/
        $fenbu_arr = [];
        for($i=0;$i<7;$i++){
            $fenbu_arr[$i] = 0;
        }


        /*豹子遗漏值记录*/
        $baozi = [];
        for($i=0;$i<10;$i++){
            $baozi[$i] = 0;
        }

        /*对子遗漏值记录*/
        $duizi = [];
        for($i=0;$i<10;$i++){
            $duizi[$i] = 0;
        }

        /*三不同遗漏值记录*/
        $buton = [];
        for($i=0;$i<10;$i++){
            $buton[$i] = 0;
        }

        /*三连号遗漏值记录*/
        $lianhao = [];
        for($i=0;$i<10;$i++){
            $lianhao[$i] = 0;
        }

        /*总期数*/
        $count_data = count($data);
        /*表头设置*/
        $header_name = ["百位","十位","个位"];
        return view("zoushitu.kuaisan",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
            "data"          => $data,
            "number"        => $number,
            "header_name"   => $header_name,
            "arr"           => $arr,
            "fenbu_arr"     => $fenbu_arr,
            "count_data"    => $count_data,
            "fenbu_total_arr"   => $fenbu_total_arr,
            "new_number"        => $new_number,
            "time"              => $time,
            "baozi"             => $baozi,
            "duizi"             => $duizi,
            "buton"             => $buton,
            "lianhao"           => $lianhao
        ]);
    }






    /**
     * 作用：pk10走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pk10(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:4;
        $game_id   = $request->game_id?$request->game_id:16;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();
        $category       = ["冠亚军","前五名","后五名"];

        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
            ->where("res_status",2)
            ->orderBy("id","desc");


        $new_number = $request->number;
        $time       = $request->time;
        $xing       = $request->xing?$request->xing:"0";
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
            /*选择冠亚军*/
            if($xing=="0"){
                $number = substr_count(substr($data[0]["result"],0,5),",")+1;
                $header_name = ["冠军","亚军"];
            }
            /*选择冠亚军*/
            if($xing=="1"){
                $number = substr_count(substr($data[0]["result"],0,14),",")+1;
                $header_name = ["冠军","亚军","第三名","第四名","第五名"];
            }
            /*选择冠亚军*/
            if($xing=="2"){
                $number = substr_count(substr($data[0]["result"],15),",")+1;
                $header_name = ["第六名","第七名","第八名","第九名","第十名"];
            }
        }else{
            $number = 0;
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
            for($j=0;$j<11;$j++){
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
        for($j=0;$j<11;$j++){
            $fenbu_total_arr[$j] = [];
            for($k=0;$k<7;$k++){
                $fenbu_total_arr[$j][$k] = 0;
            }
        }

        /*分布管理里显示的遗漏值*/
        $fenbu_arr = [];
        for($i=0;$i<11;$i++){
            $fenbu_arr[$i] = 0;
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
        for($j=0;$j<11;$j++){
            $er_arr[$j] = [];
            for($k=0;$k<7;$k++){
                $er_arr[$j][$k] = 0;
            }
        }




        /*总期数*/
        $count_data = count($data);
        /*表头设置*/
        return view("zoushitu.pk10",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
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
            "er_arr"            => $er_arr,
        ]);
    }







    /**
     * 作用：福彩3D走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fucai3d(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:5;
        $game_id   = $request->game_id?$request->game_id:18;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();

        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
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
        $header_name = ["百位","十位","个位"];
        return view("zoushitu.fucai3d",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
            "data"          => $data,
            "number"        => $number,
            "header_name"   => $header_name,
            "arr"           => $arr,
            "fenbu_arr"     => $fenbu_arr,
            "count_data"    => $count_data,
            "fenbu_total_arr"   => $fenbu_total_arr,
            "new_number"        => $new_number,
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
     * 作用：六合彩走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function liuhecai(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:6;
        $game_id   = $request->game_id?$request->game_id:21;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();

        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
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


        /*红波*/
        $red = [
            "01","02","07","08","12","13","18","19","23","24","29","30","34","35","40","45","46"
        ];
        /*蓝波*/
        $blue = [
            "03","04","09","10","14","15","20","25","26","31","36","37","41","42","47","48"
        ];
        /*绿波*/
        $green = [
            "05","06","11","16","17","21","22","27","28","32","33","38","39","43","44","49"
        ];

        /*总期数*/
        $count_data = count($data);
        return view("zoushitu.liuhecai",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
            "data"          => $data,
            "number"        => $number,
            "count_data"    => $count_data,
            "new_number"        => $new_number,
            "time"              => $time,
            "xing"              => $xing,
            "red"               => $red,
            "blue"              => $blue,
            "green"             => $green,
        ]);
    }


    /**
     * 作用：判断号码在那一年是什么生肖
     * 作者：信
     * 时间：2018/6/8
     * 修改：暂无
     * @param $year     年
     * @param $number   号码
     * @return string
     */
    public function shengxiao($year,$number){
         $toyear     = 1998;
         $birthyear  = $year+$number;
         $birthpet   =   "Ox";
         $x          = ($toyear - $birthyear) % 12;

        if (($x == 1) || ($x == -11)) {
            $birthpet="鼠";
        }elseif ($x==0){
            $birthpet="牛";
        }elseif(($x == 11) || ($x == -1)){
            $birthpet="虎";
        }elseif(($x == 10) || ($x == -2)){
            $birthpet="兔";
        }elseif (($x == 9) || ($x == -3)){
            $birthpet="龙";
        }elseif(($x == 8) || ($x == -4)){
            $birthpet="蛇";
        }elseif(($x == 7) || ($x == -5)){
            $birthpet="马";
        }elseif (($x == 6) || ($x == -6)){
            $birthpet="羊";
        }elseif (($x == 5) || ($x == -7)){
            $birthpet="猴";
        }elseif (($x == 4) || ($x == -8)){
            $birthpet="鸡";
        }elseif (($x == 3) || ($x == -9)){
            $birthpet="狗";
        }elseif(($x == 2) || ($x == -10)){
            $birthpet="猪";
        }
        return $birthpet;
    }

















    /**
     * 作用：PC蛋蛋走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pcdd(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:7;
        $game_id   = $request->game_id?$request->game_id:22;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();

        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
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
        $header_name = ["百位","十位","个位"];
        return view("zoushitu.pcdd",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
            "data"          => $data,
            "number"        => $number,
            "header_name"   => $header_name,
            "arr"           => $arr,
            "fenbu_arr"     => $fenbu_arr,
            "count_data"    => $count_data,
            "fenbu_total_arr"   => $fenbu_total_arr,
            "new_number"        => $new_number,
            "time"              => $time,
            "xing"              => $xing,
            "er_arr"            => $er_arr,
        ]);
    }








    /**
     * 作用：北京快乐8走势图
     * 作者：信
     * 时间：2018/5/12
     * 修改：暂无
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function beijingkl8(Request $request){
        $game_type_id   = $request->game_type_id?$request->game_type_id:8;
        $game_id   = $request->game_id?$request->game_id:23;
        /*游戏所有类型*/
        $game_type      = GameType::query()->get()->toArray();
        $game           = Game::query()->where("type",$game_type_id)->get()->toArray();

        $model = $this->game_model($game_id);
        /*开奖数据*/
        $data = $model::query()
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
            $number = 10;
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
        $fenbu_total_arr = [];

        for($j=0;$j<10;$j++){
            $fenbu_total_arr[$j] = [];
            for($k=0;$k<8;$k++){
                $fenbu_total_arr[$j][$k] = 0;
            }
        }

        /*和值尾数里显示的遗漏值*/
        $fenbu_arr = [];
        for($i=0;$i<10;$i++){
            $fenbu_arr[$i] = 0;
        }

        /*总期数*/
        $count_data = count($data);
        /*表头设置*/
        return view("zoushitu.beijingkl8",[
            "game_type"     => $game_type,
            "game"          => $game,
            "game_type_id"  => $game_type_id,
            "game_id"       => $game_id,
            "data"          => $data,
            "number"        => $number,
            "fenbu_total_arr"   => $fenbu_total_arr,
            "count_data"        => $count_data,
            "new_number"        => $new_number,
            "time"              => $time,
            "xing"              => $xing,
            "fenbu_arr"         => $fenbu_arr
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
     * 作用：不同的游戏id返回不同的表模型
     * 作者：信
     * 时间：2018/5/17
     * 修改：暂无
     * @param $game_id
     * @return 模型
     */
    public function game_model($game_id){
        switch ($game_id){
            case "1":
                $model = new DrawResultChongqing();
                break;
            case "2":
                $model = new DrawResultTianjin();
                break;
            case "3":
                $model = new DrawResultXinjiang();
                break;
            case "4":
                $model = new DrawResultTengxun();
                break;
            case "5":
                $model = new DrawResultOuzhou();
                break;
            case "6":
                $model = new DrawResultBeijing();
                break;
            case "7":
                $model = new DrawResultHanguo();
                break;
            case "8":
                $model = new DrawResultXinjiapo();
                break;
            case "9":
                $model = new DrawResultHenei();
                break;
            case "10":
                $model = new DrawResultGuangdong();
                break;
            case "11":
                $model = new DrawResultShandong();
                break;
            case "12":
                $model = new DrawResultShanghai();
                break;
            case "13":
                $model = new DrawResultJiangxi();
                break;
            case "14":
                $model = new DrawResultJiangsu();
                break;
            case "15":
                $model = new DrawResultAnhui();
                break;
            case "16":
                $model = new DrawResultBeijingpk();
                break;
            case "17":
                $model = new DrawResultFenfenpk();
                break;
            case "18":
                $model = new DrawResultFucai3d();
                break;
            case "19":
                $model = new DrawResultFenfen3d();
                break;
            case "20":
                $model = new DrawResultPailie3();
                break;
            case "21":
                $model = new DrawResultLiuhecai();
                break;
            case "22":
                $model = new DrawResultPcdd();
                break;
            case "23":
                $model = new DrawResultBeijingkl8();
                break;
        }
        return $model;
    }

}





