<!DOCTYPE html>
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{asset('/plugins/layui/css/layui.css')}}" media="all">
    <title></title>
    <style>
        /*样式*/
        table { border: 0; margin: 0; border-collapse: collapse; border-spacing: 0; font-size: 0.6rem; font-family: Arial; margin: 0 auto; }
        table td, table th { padding: 0; height: 1.4rem; width: 1.4rem; text-align: center; color: #666; }
        table th { font-weight: bold; color: #000; }
        table td{
            background: #fff;
            padding:0.3rem 0px;
            /*text-align: center;*/
        }
        #form1{
            /*margin-left: 12rem;*/
        }
        .show_border{
            border-right: 1px solid rgba(155, 155, 155, 0.28);
            border-left: 1px solid rgba(155, 155, 155, 0.28);
        }
        .all_td{
            border-right: 1px solid rgba(155, 155, 155, 0.28)
        }
        .border_bottm{
            border-bottom: 1px solid rgba(155, 155, 155, 0.28)
        }
        .border_bottm_5{
            border-bottom: 1px solid rgba(155, 155, 155, 0.28)
        }
        .border_left{
            border-left: 1px solid rgba(155, 155, 155, 0.28)
        }
        table tr:last-child{
            border-bottom: 1px solid rgba(155, 155, 155, 0.28)
        }
        .height40{
            height: 2.3rem;
        }
        .color_one{
            height: 1.8rem;
            background: #515368;
            color: white
        }
        .color_two{
            height: 1.8rem;
            background: #3A3B50;
            color: white
        }
        .show_change{
              background: white;
              border:1px solid rgba(117, 126, 168, 0.38);
              border-radius: 0.18rem;
              padding: 0px 0px 0.5rem 0.5rem;
              margin-right: 0.5rem;
              float: left;
          }
        .show_change_font{
            color: #484848;
            font-size: 0.75rem;
            border-radius: 3px;
            padding:0.3rem 0.5rem;
            margin-top: 0.3rem;
            margin-right: 0.5rem;
            float: left;
        }
        .font_style{
            color:gray;
            background: white;
            border:1px solid rgba(117, 126, 168, 0.38);
        }
        #max{
            width: 80%;
            background: red!important;
            margin: 0 auto;
        }
        #main{
            width: 100%;
            padding-top: 0.6rem;
            /*padding-left: 5rem;*/
            padding-bottom: 15rem;
            float: left;
            background: #E6E6E6;
        }
        #category{
            width: 100%;
            height: 2.5rem;
            background: white;
        }
        #top{
            width: 100%;
            height: 2.5rem;
            background: white;
            background: #E6E6E6;
        }
        #category ul li{
            margin-top: 0.6rem;
            float: left;
            text-align: center;
            height: 2.3rem;
            line-height: 2.3rem;
            margin-right: 0.6rem;
            color: #333;
            padding: 0px 1.25rem;
        }
        .color_greend{
            background: #23807C;
            color: white!important;
        }
        #category ul li:hover{
            background: darkgray;
        }
        .max_right{
            width: 3.7rem;
        }
        .daxiao{
            background: #00A2FF;
            color: white;
            border-bottom: 1px solid white;
        }
        .daxiao_noborder{
            background: #00A2FF;
            color: white;
        }
        .danshuang{
            background: #34BC0F;
            color: white;
            border-bottom: 1px solid white;
        }
        .danshuang_noborder{
            background: #34BC0F;
            color: white;
        }
        .zusan{
            width: 1.8rem;
        }
        .kuadu{
            background: #00A2FF;
            color: white;
            border-bottom: 1px solid white;
        }
        .kuadu_noborder{
            background: #00A2FF;
            color: white;
        }
        .hezhi{
            background: #F53F00;
            color: white;
            border-bottom: 1px solid white;
        }
        .hezhi_noborder{
            background: #F53F00;
            color: white;
        }
        .bg_blue{
            background: #00A2FF;
            color: white;
            border-bottom: 1px solid white;
        }
        .bg_blue_noborder{
            background: #00A2FF;
            color: white;
        }
        .er_hezhi{
            width: 3rem;
        }




        .qihao{
            width: 5%;
        }
        .kaijiangjieguo{
            width: 5%;
        }
        .wanqian{
            width: 15%;
        }
        .haomafenbu{
            width: 15%;
        }



        #zanwu{
            margin: 0px;
            height: 1rem;
            line-height: 36px;
            background-color:white;
            font-size: 1rem;
            text-align: center;
            color: gray;
            border-right: 1px solid gainsboro;
            border-left: 1px solid gainsboro;
        }

    </style>
</head>
<body>
<div id="max">
<div id="top">
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">选择彩种</label>
            <div class="layui-input-inline">
                <select id="leixing" lay-filter="leixing">
                    @foreach($game_type as $key=>$value)
                        @if($game_type_id == $value['id'])
                            <option value="{{$value['id']}}" selected="" >{{$value["typeName"]}}</option>
                            @else
                            <option value="{{$value['id']}}" >{{$value["typeName"]}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="layui-input-inline">
                <select lay-filter="game">
                    @foreach($game as $key=>$value)
                        @if($game_id == $value['id'])
                            <option value="{{$value['id']}}" selected="" >{{$value["name"]}}</option>
                        @else
                            <option value="{{$value['id']}}" >{{$value["name"]}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
<div id="category">
    <ul>
        @foreach($category as $key=>$value)
            <li class="category <?php if($xing==$key) echo 'color_greend'; ?>" data="{{$key}}">{{$value}}</li>
        @endforeach
    </ul>
</div>
<div id="main">
    {{--遗漏辅助线近期等选择start--}}
    <div >
        <form class="layui-form" action="">
            <div class="layui-form-item" pane="">
                <div class="layui-input-block" style="margin: 0px">
                    <div class="show_change">
                        <input type="checkbox" id="fuzhuxian" name="fuzhuxian" lay-filter="fuzhuxian" lay-skin="primary" title="辅助线" checked="">
                    </div>
                    <div class="show_change">
                        <input type="checkbox" lay-filter="yilou" name="yilou" lay-skin="primary" title="遗漏" checked="">
                    </div>
                    <div class="show_change">
                        <input type="checkbox" id="yiloutiao" name="yiloutiao" lay-filter="yiloutiao" lay-skin="primary" title="遗漏条">
                    </div>
                    <div class="show_change">
                        <input type="checkbox" name="lianxian" lay-filter="lianxian" lay-skin="primary" title="连线" checked="">
                    </div>
                    <div class="show_change">
                        <input type="checkbox" name="haowen" lay-filter="haowen" lay-skin="primary" title="号温" >
                    </div>
                    <div class="show_change_font link_number <?php if($new_number==30) echo 'show_change' ?>" data="30">
                        近30期
                    </div>
                    <div class="show_change_font link_number <?php if($new_number==50) echo 'show_change' ?>" data="50">
                        近50期
                    </div>
                    <div class="show_change_font link_number <?php if($new_number==1) echo 'show_change' ?>" data="1">
                        今日数据
                    </div>
                    <div class="show_change_font link_number <?php if($new_number==2) echo 'show_change' ?>" data="2">
                        近2天
                    </div>
                    <div class="show_change_font link_number <?php if($new_number==5) echo 'show_change' ?>" data="5">
                        近5天
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" style="color: #484848">日期范围：</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{$time}}" class="layui-input" id="test6" placeholder=" - ">
                        </div>
                    </div>

                    <button type="button" class="layui-btn btn chaxun">查询</button>
                </div>
            </div>
        </form>
    </div>
    {{--遗漏辅助线近期等选择end--}}

    {{--数据显示start--}}
    <form id="form1" runat="server">
        <table id="zstable"  style="float: left;border-right: 1px solid rgba(155, 155, 155, 0.28)">
            @if($data)
                {{--表头显示start--}}
                <thead>
                    <tr style="height: 2.5rem">
                        <th class="all_td color_one qihao"rowspan="2">期号</th>
                        <th class="all_td border_bottm color_one kaijiangjieguo" colspan="4">开奖结果</th>
                        @php
                            $heard_j = 0;
                            $heard_num = $number;
                            /*五星或者四星*/
                            if($xing==0 || $xing==1){
                                $heard_j = $xing;
                            }
                            /*前三*/
                            if($xing==2){
                                $heard_num = $number-$xing;
                            }
                            /*中三*/
                            if($xing==3){
                                $heard_j = 1;
                                $heard_num = $number-1;
                            }
                            /*后三*/
                            if($xing==4){
                                $heard_j = 2;
                            }
                            /*前二*/
                            if($xing==5){
                                $heard_num = $number-3;
                            }
                            /*后二*/
                            if($xing==6){
                                $heard_j = 3;
                            }
                        @endphp
                        @for($j=$heard_j;$j<$heard_num;$j++)
                            <th class="all_td border_bottm color_one wanqian" colspan="10">{{$header_name[$j]}}</th>
                        @endfor
                        <th class="all_td border_bottm color_one haomafenbu" colspan="10">号码分布</th>

                        {{--如果选择前中后三strat--}}
                        @if($xing==2 || $xing==3 || $xing==4)
                            <th class="all_td color_one max_right" rowspan="2">大小形态</th>
                            <th class="all_td color_one max_right" rowspan="2">单双形态</th>
                            <th class="all_td color_one max_right" rowspan="2">质合形态</th>
                            <th class="all_td color_one max_right" rowspan="2">012形态</th>
                            <th class="all_td color_one" rowspan="2" style="width: 5rem">豹子(三同号)</th>
                            <th class="all_td color_one zusan" rowspan="2">组三</th>
                            <th class="all_td color_one zusan" rowspan="2">组六</th>
                            <th class="all_td color_one zusan" rowspan="2">跨度</th>
                            <th class="all_td color_one zusan" rowspan="2">和值</th>
                            <th class="all_td color_one" rowspan="2" style="width: 3.75rem">和值尾数</th>
                        @endif
                        {{--如果选择前中后三end--}}
                        @if($xing==5 || $xing==6)
                            <th class="all_td color_one" rowspan="2" style="width: 5rem">对子(二同号)</th>
                            <th class="all_td border_bottm color_one" colspan="10">跨度走势</th>
                            <th class="all_td color_one er_hezhi" rowspan="2">和值</th>
                        @endif
                    </tr>
                    {{--0-9显示start--}}
                    <tr style="height: 2.5rem">
                        <td colspan="4" class="all_td" style="background: #3A3B50;color: white;">全部</td>
                        {{--如果是五星或者四星表头0-10start--}}
                        @php
                            $start_j = 0;
                            $number_length = $number+1;
                            /*五星四星*/
                            if($xing==0 || $xing==1){
                                $start_j = $xing;
                            }
                            /*前三*/
                            if($xing==2){
                                $number_length = $number+1-$xing;
                            }
                            /*中三*/
                            if($xing==3){
                                $start_j = 1;
                                $number_length = $number;
                            }
                            /*后三*/
                            if($xing==4){
                                $start_j = 2;
                            }
                            /*前二*/
                            if($xing==5){
                                $number_length = 3;
                            }
                            /*后二*/
                            if($xing==6){
                                $start_j = 3;
                            }
                        @endphp
                        @for($j=$start_j;$j<$number_length;$j++)
                            @for($i=0;$i<10;$i++)
                                @if($i==9)
                                    <td class="border_bottm all_td" style="background: #3A3B50;color: white;">{{$i}}</td>
                                @else
                                    <td class="border_bottm" style="background: #3A3B50;color: white;">{{$i}}</td>
                                @endif
                            @endfor
                        @endfor

                        @if($xing==5 || $xing==6)
                            @for($i=0;$i<10;$i++)
                                @if($i==9)
                                    <td class="border_bottm all_td" style="background: #3A3B50;color: white;">{{$i}}</td>
                                @else
                                    <td class="border_bottm" style="background: #3A3B50;color: white;">{{$i}}</td>
                                @endif
                            @endfor
                        @endif
                    </tr>
                    {{--0-9显示end--}}
                </thead>
                {{--表头显示end--}}



                {{--内容显示start--}}
                <tbody>
                    {{--打印数据start--}}
                    @foreach($data as $key=>$value)
                        <tr class="show_border <?php if(($key+1)%5==0) echo 'border_bottm_5' ?>">
                            <td  style="padding: 0 0.6rem;border-right: 1px solid rgba(155, 155, 155, 0.28)">{{$value["periods"]}}</td>
                            <td  colspan="4" class="all_td " style="padding: 0 0.6rem;color: indianred">{{str_replace(",","",$value["result"])}}</td>
                            @for($j=$start_j;$j<$number_length-1;$j++)
                                {{--开奖结果号码查找start--}}
                                @php
                                    $res = substr($value["result"],$j*2,$j*2+1);
                                    $str = substr($value["result"],$xing*2);
                                    /*前三*/
                                    if($xing==2){
                                        $str = substr($value["result"],0,5);
                                    }
                                    /*中三*/
                                    if($xing==3){
                                        $str = substr($value["result"],2,5);
                                    }
                                    /*后三*/
                                    if($xing==4){
                                        $str = substr($value["result"],4);
                                    }
                                    /*前二*/
                                    if($xing==5){
                                        $str = substr($value["result"],0,3);
                                    }
                                    /*后二*/
                                    if($xing==6){
                                        $str = substr($value["result"],6);
                                    }
                                    $res_arr_all = explode(",",$str);
                                    $res_arr = array_count_values(explode(",",$str));
                                @endphp
                                {{--开奖结果号码查找end--}}


                                {{--显示遗漏值或者开奖号码start--}}
                                @for($i=0;$i<10;$i++)
                                    {{--中奖了start--}}
                                    @if($res == $i)
                                        {{--php数值记录start--}}
                                        @php
                                            $arr[$j][$i][0] = 0;
                                            /*平均总次数+1*/
                                            $arr[$j][$i][1]++;
                                            /*平均遗漏中奖次数+1*/
                                            $arr[$j][$i][2]++;
                                            /*中奖后最大遗漏值清0*/
                                            $arr[$j][$i][3]=0;
                                            /*中奖了，连出值+1*/
                                            $arr[$j][$i][5]++;
                                             /*中奖号码*/
                                            $arr[$j][$i][7]++;

                                            if($key+1 == count($data)){
                                                /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $arr[$j][$i][3] = $arr[$j][$i][4];
                                                /*连出值判断*/
                                                if($arr[$j][$i][5]<$arr[$j][$i][6]){
                                                    $arr[$j][$i][5] = $arr[$j][$i][6];
                                                 }
                                            }else{
                                                /*连出值判断*/
                                                if($arr[$j][$i][5]>$arr[$j][$i][6]){
                                                    $arr[$j][$i][6] = $arr[$j][$i][5];
                                                 }
                                            }
                                        @endphp
                                        {{--php数值记录end--}}
                                        <td class="<?php if($i==9) echo 'all_td'; ?>"><span class="layui-badge xian{{$j}}">{{$i}}</span></td>
                                    {{--没中奖start--}}
                                    @else
                                        @php
                                            $arr[$j][$i][0]++;
                                            /*最大遗漏值+1*/
                                            $arr[$j][$i][3]++;
                                             /*没中奖连出值清零*/
                                            $arr[$j][$i][5]=0;

                                            if($key+1 == count($data)){
                                                /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $arr[$j][$i][5] = $arr[$j][$i][6];
                                                /*遗漏值判断*/
                                                if($arr[$j][$i][3]<$arr[$j][$i][4]){
                                                    $arr[$j][$i][3] = $arr[$j][$i][4];
                                                 }
                                            }else{
                                                /*遗漏值判断*/
                                                if($arr[$j][$i][3]>$arr[$j][$i][4]){
                                                    $arr[$j][$i][4] = $arr[$j][$i][3];
                                                 }
                                            }
                                        @endphp
                                        {{--第九个的时候添加右边start--}}
                                        <td class="<?php if($i==9) echo 'all_td';?> yiloutiao{{$j}}{{$i}}{{$arr[$j][$i][7]}} <?php if(($key+1)%5==0) echo 'yiloutiao_5'.$j.$i ?>">
                                            <span class="yilou">
                                                {{$arr[$j][$i][0]}}
                                            </span>
                                        </td>
                                    @endif
                                @endfor
                                {{--显示遗漏值或者开奖号码end--}}
                            @endfor

                            {{--号码分布start--}}
                            @for($i=0;$i<10;$i++)
                                @if(array_key_exists($i,$res_arr))
                                    @php
                                        /*遗漏值清0*/
                                        $fenbu_arr[$i] = 0;
                                        /*平均总次数+1*/
                                        $fenbu_total_arr[$i][1]++;
                                        /*平均遗漏中奖次数+1*/
                                        $fenbu_total_arr[$i][2]++;
                                        /*中奖后最大遗漏值清0*/
                                        $fenbu_total_arr[$i][3]=0;
                                        /*中奖了，连出值+1*/
                                        $fenbu_total_arr[$i][5]++;

                                        if($key+1 == count($data)){
                                            /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                            $fenbu_total_arr[$i][3] = $fenbu_total_arr[$i][4];
                                            /*连出值判断*/
                                            if($fenbu_total_arr[$i][5]<$fenbu_total_arr[$i][6]){
                                                $fenbu_total_arr[$i][5] = $fenbu_total_arr[$i][6];
                                             }
                                        }else{
                                            /*连出值判断*/
                                            if($fenbu_total_arr[$i][5]>$fenbu_total_arr[$i][6]){
                                                $fenbu_total_arr[$i][6] = $fenbu_total_arr[$i][5];
                                             }
                                        }
                                    @endphp
                                    @if($res_arr[$i]>=2)
                                        <td <?php if($i==9) echo 'class="all_td"';?>><span style="background: #BD3ED4!important;" class=" layui-badge layui-bg-cyan">{{$i}}</span></td>
                                    @else
                                        <td <?php if($i==9) echo 'class="all_td"';?>><span class="layui-badge layui-bg-green">{{$i}}</span></td>
                                    @endif
                                @else
                                    @php
                                        /*遗漏值++*/
                                        $fenbu_arr[$i]++;
                                        /*最大遗漏值+1*/
                                        $fenbu_total_arr[$i][3]++;
                                         /*没中奖连出值清零*/
                                        $fenbu_total_arr[$i][5]=0;

                                        if($key+1 == count($data)){
                                            /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                            $fenbu_total_arr[$i][5] = $fenbu_total_arr[$i][6];
                                            /*遗漏值判断*/
                                            if($fenbu_total_arr[$i][3]<$fenbu_total_arr[$i][4]){
                                                $fenbu_total_arr[$i][3] = $fenbu_total_arr[$i][4];
                                             }
                                        }else{
                                            /*遗漏值判断*/
                                            if($fenbu_total_arr[$i][3]>$fenbu_total_arr[$i][4]){
                                                $fenbu_total_arr[$i][4] = $fenbu_total_arr[$i][3];
                                             }
                                        }
                                    @endphp
                                    <td <?php if($i==9) echo 'class="all_td"';?>><span class="yilou ">{{$fenbu_arr[$i]}}</span></td>
                                @endif
                            @endfor
                            {{--号码分布end--}}

                            {{--如果选择前中后三start--}}
                            @if($xing==2 || $xing==3 || $xing==4)
                                {{--大小--}}
                                <td class="all_td <?php if($key+1 == $count_data){echo 'daxiao_noborder';}else{echo 'daxiao';}?>">
                                    @foreach($res_arr_all as $k=>$v)
                                        @if($v>=5)
                                            大
                                            @else
                                            小
                                        @endif
                                    @endforeach
                                </td>

                                {{--单双--}}
                                <td class="all_td <?php if($key+1 == $count_data){echo 'danshuang_noborder';}else{echo 'danshuang';}?>">
                                    @foreach($res_arr_all as $k=>$v)
                                        @if($v%2==0)
                                            双
                                        @else
                                            单
                                        @endif
                                    @endforeach
                                </td>

                                {{--质合数--}}
                                <td class="all_td <?php if($key+1 == $count_data){echo 'daxiao_noborder';}else{echo 'daxiao';}?>">
                                    @foreach($res_arr_all as $k=>$v)
                                        @php $zhishu = 0; @endphp
                                        @for($i=2;$i<$v;$i++)
                                           @if($v%$i==0)
                                                @php $zhishu++ @endphp
                                           @endif
                                        @endfor
                                        @if($zhishu)
                                            合
                                        @else
                                            质
                                        @endif
                                    @endforeach
                                </td>

                                {{--012形态--}}
                                <td class="all_td <?php if($key+1 == $count_data){echo 'danshuang_noborder';}else{echo 'danshuang';}?>">
                                    @foreach($res_arr_all as $k=>$v)
                                        {{$v%3}}
                                    @endforeach
                                </td>

                                {{--豹子--}}
                                <td class="all_td">
                                    @if($res_arr_all[0] == $res_arr_all[1] && $res_arr_all[1] == $res_arr_all[2])
                                            {{--豹子中奖数据统计start--}}
                                            @php
                                                /*豹子遗漏值清零*/
                                                $baozi[0] = 0;
                                                /*平均总次数+1*/
                                                $baozi[1]++;
                                                /*平均遗漏中奖次数+1*/
                                                $baozi[2]++;
                                                /*中奖后最大遗漏值清0*/
                                                $baozi[3]=0;
                                                /*中奖了，连出值+1*/
                                                $baozi[5]++;

                                                if($key+1 == count($data)){
                                                    /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                                    $baozi[3] = $baozi[4];
                                                    /*连出值判断*/
                                                    if($baozi[5]<$baozi[6]){
                                                        $baozi[5] = $baozi[6];
                                                     }
                                                }else{
                                                    /*连出值判断*/
                                                    if($baozi[5]>$baozi[6]){
                                                        $baozi[6] = $baozi[5];
                                                     }
                                                }
                                            @endphp
                                            {{--豹子中奖数据统计end--}}
                                            <span style="color: red;font-size: 1rem;">✔</span>
                                        @else
                                            {{--豹子未中奖数据统计start--}}
                                            @php
                                                /*遗漏值++*/
                                               $baozi[0]++;
                                               /*最大遗漏值+1*/
                                               $baozi[3]++;
                                                /*没中奖连出值清零*/
                                               $baozi[5]=0;

                                               if($key+1 == count($data)){
                                                   /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                                   $baozi[5] = $baozi[6];
                                                   /*遗漏值判断*/
                                                   if($baozi[3]<$baozi[4]){
                                                       $baozi[3] = $baozi[4];
                                                    }
                                               }else{
                                                   /*遗漏值判断*/
                                                   if($baozi[3]>$baozi[4]){
                                                       $baozi[4] = $baozi[3];
                                                    }
                                               }
                                            @endphp
                                            {{--豹子未中奖数据统计end--}}
                                            {{$baozi[0]}}
                                    @endif
                                </td>

                                {{--组三--}}
                                <td class="all_td">
                                    @php $res_arr_all_uni = array_unique($res_arr_all); @endphp
                                    @if(count($res_arr_all_uni)==2)
                                        {{--组三中奖数据统计start--}}
                                        @php
                                            /*豹子遗漏值清零*/
                                            $zusan[0] = 0;
                                            /*平均总次数+1*/
                                            $zusan[1]++;
                                            /*平均遗漏中奖次数+1*/
                                            $zusan[2]++;
                                            /*中奖后最大遗漏值清0*/
                                            $zusan[3]=0;
                                            /*中奖了，连出值+1*/
                                            $zusan[5]++;

                                            if($key+1 == count($data)){
                                                /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $zusan[3] = $zusan[4];
                                                /*连出值判断*/
                                                if($zusan[5]<$zusan[6]){
                                                    $zusan[5] = $zusan[6];
                                                 }
                                            }else{
                                                /*连出值判断*/
                                                if($zusan[5]>$zusan[6]){
                                                    $zusan[6] = $zusan[5];
                                                 }
                                            }
                                        @endphp
                                        {{--组三中奖数据统计end--}}
                                        <span style="color: red;font-size: 1.1rem">✔</span>
                                    @else
                                        {{--组三未中奖数据统计start--}}
                                        @php
                                            /*遗漏值++*/
                                           $zusan[0]++;
                                           /*最大遗漏值+1*/
                                           $zusan[3]++;
                                            /*没中奖连出值清零*/
                                           $zusan[5]=0;

                                           if($key+1 == count($data)){
                                               /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                               $zusan[5] = $zusan[6];
                                               /*遗漏值判断*/
                                               if($zusan[3]<$zusan[4]){
                                                   $zusan[3] = $zusan[4];
                                                }
                                           }else{
                                               /*遗漏值判断*/
                                               if($zusan[3]>$zusan[4]){
                                                   $zusan[4] = $zusan[3];
                                                }
                                           }
                                        @endphp
                                        {{--组三未中奖数据统计end--}}
                                        {{$zusan[0]}}
                                    @endif
                                </td>

                                {{--组六--}}
                                <td class="all_td">
                                    @if(count($res_arr_all_uni)==3)
                                        {{--组六中奖数据统计start--}}
                                        @php
                                            /*豹子遗漏值清零*/
                                            $zuliu[0] = 0;
                                            /*平均总次数+1*/
                                            $zuliu[1]++;
                                            /*平均遗漏中奖次数+1*/
                                            $zuliu[2]++;
                                            /*中奖后最大遗漏值清0*/
                                            $zuliu[3]=0;
                                            /*中奖了，连出值+1*/
                                            $zuliu[5]++;

                                            if($key+1 == count($data)){
                                                /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $zuliu[3] = $zuliu[4];
                                                /*连出值判断*/
                                                if($zuliu[5]<$zuliu[6]){
                                                    $zuliu[5] = $zuliu[6];
                                                 }
                                            }else{
                                                /*连出值判断*/
                                                if($zuliu[5]>$zuliu[6]){
                                                    $zuliu[6] = $zuliu[5];
                                                 }
                                            }
                                        @endphp
                                        {{--组六中奖数据统计end--}}
                                        <span style="color: red;font-size: 1.1rem">✔</span>
                                    @else
                                        {{--组六未中奖数据统计start--}}
                                        @php
                                            /*遗漏值++*/
                                           $zuliu[0]++;
                                           /*最大遗漏值+1*/
                                           $zuliu[3]++;
                                            /*没中奖连出值清零*/
                                           $zuliu[5]=0;

                                           if($key+1 == count($data)){
                                               /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                               $zuliu[5] = $zuliu[6];
                                               /*遗漏值判断*/
                                               if($zuliu[3]<$zuliu[4]){
                                                   $zuliu[3] = $zuliu[4];
                                                }
                                           }else{
                                               /*遗漏值判断*/
                                               if($zuliu[3]>$zuliu[4]){
                                                   $zuliu[4] = $zuliu[3];
                                                }
                                           }
                                        @endphp
                                        {{--组六未中奖数据统计end--}}
                                        {{$zuliu[0]}}
                                    @endif
                                </td>

                                {{--跨度--}}
                                <td class="all_td <?php if($key+1 == $count_data){echo 'kuadu_noborder';}else{echo 'kuadu';}?>">
                                    @php echo max($res_arr_all)-min($res_arr_all); @endphp
                                </td>

                                {{--和值--}}
                                <td class="all_td <?php if($key+1 == $count_data){echo 'hezhi_noborder';}else{echo 'hezhi';}?>">
                                    @php echo array_sum($res_arr_all); @endphp
                                </td>

                                {{--和值尾数--}}
                                <td class="all_td">
                                    @php
                                        $weishu =    (string)array_sum($res_arr_all);
                                        echo substr($weishu,-1);
                                    @endphp
                                </td>
                            @endif
                            {{--如果选择前中后三end--}}

                            {{--如果选择前后二start--}}
                            @if($xing==5 || $xing==6)
                                {{--对子--}}
                                <td class="all_td" style="width: 5rem">
                                    @if($res_arr_all[0] == $res_arr_all[1])
                                        {{--对子中奖数据统计start--}}
                                        @php
                                            /*对子遗漏值清零*/
                                            $duizi[0] = 0;
                                            /*平均总次数+1*/
                                            $duizi[1]++;
                                            /*平均遗漏中奖次数+1*/
                                            $duizi[2]++;
                                            /*中奖后最大遗漏值清0*/
                                            $duizi[3]=0;
                                            /*中奖了，连出值+1*/
                                            $duizi[5]++;

                                            if($key+1 == count($data)){
                                                /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $duizi[3] = $duizi[4];
                                                /*连出值判断*/
                                                if($duizi[5]<$duizi[6]){
                                                    $duizi[5] = $duizi[6];
                                                 }
                                            }else{
                                                /*连出值判断*/
                                                if($duizi[5]>$duizi[6]){
                                                    $duizi[6] = $duizi[5];
                                                 }
                                            }
                                        @endphp
                                        {{--对子中奖数据统计end--}}
                                        <span style="color: red;font-size: 1rem;">✔</span>
                                    @else
                                        {{--对子未中奖数据统计start--}}
                                        @php
                                            /*遗漏值++*/
                                           $duizi[0]++;
                                           /*最大遗漏值+1*/
                                           $duizi[3]++;
                                            /*没中奖连出值清零*/
                                           $duizi[5]=0;

                                           if($key+1 == count($data)){
                                               /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                               $duizi[5] = $duizi[6];
                                               /*遗漏值判断*/
                                               if($duizi[3]<$duizi[4]){
                                                   $duizi[3] = $duizi[4];
                                                }
                                           }else{
                                               /*遗漏值判断*/
                                               if($duizi[3]>$duizi[4]){
                                                   $duizi[4] = $duizi[3];
                                                }
                                           }
                                        @endphp
                                        {{--对子未中奖数据统计end--}}
                                        {{$duizi[0]}}
                                    @endif
                                </td>

                                {{--跨度走势--}}
                                @for($i=0;$i<10;$i++)
                                    {{--@if(array_key_exists($i,$res_arr))--}}
                                    @if(max($res_arr_all)-min($res_arr_all) == $i)
                                        @php
                                            /*遗漏值清0*/
                                            $er_arr[$i][0]=0;
                                            /*平均总次数+1*/
                                            $er_arr[$i][1]++;
                                            /*平均遗漏中奖次数+1*/
                                            $er_arr[$i][2]++;
                                            /*中奖后最大遗漏值清0*/
                                            $er_arr[$i][3]=0;
                                            /*中奖了，连出值+1*/
                                            $er_arr[$i][5]++;

                                            if($key+1 == count($data)){
                                                /*如果最大遗漏值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $er_arr[$i][3] = $er_arr[$i][4];
                                                /*连出值判断*/
                                                if($er_arr[$i][5]<$er_arr[$i][6]){
                                                    $er_arr[$i][5] = $er_arr[$i][6];
                                                 }
                                            }else{
                                                /*连出值判断*/
                                                if($er_arr[$i][5]>$er_arr[$i][6]){
                                                    $er_arr[$i][6] = $er_arr[$i][5];
                                                 }
                                            }
                                        @endphp
                                        <td class="<?php if($key+1 == $count_data){echo 'bg_blue_noborder';}else{echo 'bg_blue';}?> <?php if($i==9) echo 'all_td';?>" >{{$i}}</td>
                                    @else
                                        @php
                                            /*遗漏值++*/
                                            $er_arr[$i][0]++;
                                            /*最大遗漏值+1*/
                                            $er_arr[$i][3]++;
                                             /*没中奖连出值清零*/
                                            $er_arr[$i][5]=0;

                                            if($key+1 == count($data)){
                                                /*如果最大连出值最后一个中奖了，则把最大的遗漏值付给他*/
                                                $er_arr[$i][5] = $er_arr[$i][6];
                                                /*遗漏值判断*/
                                                if($er_arr[$i][3]<$er_arr[$i][4]){
                                                    $er_arr[$i][3] = $er_arr[$i][4];
                                                 }
                                            }else{
                                                /*遗漏值判断*/
                                                if($er_arr[$i][3]>$er_arr[$i][4]){
                                                    $er_arr[$i][4] = $er_arr[$i][3];
                                                 }
                                            }
                                        @endphp
                                        <td <?php if($i==9) echo 'class="all_td"';?>><span class="yilou ">{{$er_arr[$i][0]}}</span></td>
                                    @endif
                                @endfor

                                {{--对子和值--}}
                                <td class="all_td">{{array_sum($res_arr_all)}}</td>
                            @endif
                            {{--如果选择前后二end--}}
                        </tr>
                    @endforeach
                    {{--打印数据end--}}
                </tbody>
                {{--内容显示end--}}





                <tbody>
                    {{--平均总次数start--}}
                    <tr class="height40">
                        <th rowspan="1" class="all_td border_bottm color_one" >平均总次数</th>
                        <td rowspan="1" class="all_td border_bottm color_one" colspan="4"></td>
                        @for($j=$start_j;$j<$number_length-1;$j++)
                            @for($i=0;$i<10;$i++)
                                <td rowspan="1" class="border_bottm color_one <?php if($i==9) echo 'all_td'; ?>">{{$arr[$j][$i][1]}}</td>
                            @endfor
                        @endfor

                        {{--号码分布平均总次数start--}}
                        @for($i=0;$i<10;$i++)
                            <td rowspan="1" class="border_bottm color_one <?php if($i==9) echo 'all_td'; ?>">{{$fenbu_total_arr[$i][1]}}</td>
                        @endfor
                        {{--号码分布平均总次数end--}}

                        {{--如果选择前中后三平均总次数start--}}
                        @if($xing==2 || $xing==3 || $xing==4)
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td">{{$baozi[1]}}</td>
                            <td class="border_bottm color_one  all_td">{{$zusan[1]}}</td>
                            <td class="border_bottm color_one  all_td">{{$zuliu[1]}}</td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                        {{--如果选择前中后三平均总次数end--}}

                        @if($xing==5 || $xing==6)
                            <td class="border_bottm color_one  all_td">{{$duizi[1]}}</td>
                            @for($i=0;$i<10;$i++)
                                <td rowspan="1" class="border_bottm color_one <?php if($i==9) echo 'all_td'; ?>">{{$er_arr[$i][1]}}</td>
                            @endfor
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                    </tr>
                    {{--平均总次数end--}}


                    {{--平均遗漏值start--}}
                    <tr class="height40">
                        <th rowspan="1" class="all_td border_bottm color_one">平均遗漏值</th>
                        <td class="all_td border_bottm color_one" colspan="4" ></td>
                        {{--如果是五星或者四星start--}}
                        @for($j=$start_j;$j<$number_length-1;$j++)
                            @for($i=0;$i<10;$i++)
                                <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">
                                    @if($arr[$j][$i][2] == 0)
                                        {{$count_data}}
                                    @else
                                        {{ceil(($count_data-$arr[$j][$i][2])/$arr[$j][$i][2])}}
                                    @endif
                                </td>
                            @endfor
                        @endfor


                        {{--号码分布平均遗漏值start--}}
                        @for($i=0;$i<10;$i++)
                            <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">
                                @if($fenbu_total_arr[$i][2] == 0)
                                    {{$count_data}}
                                @else
                                    {{ceil(($count_data-$fenbu_total_arr[$i][2])/$fenbu_total_arr[$i][2])}}
                                @endif
                            </td>
                        @endfor
                        {{--号码分布平均遗漏值end--}}

                        {{--如果选择前中后三平均遗漏值start--}}
                        @if($xing==2 || $xing==3 || $xing==4)
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td">
                                @if($baozi[2]==0)
                                    {{$count_data}}
                                    @else
                                    {{ceil(($count_data-$baozi[2])/$baozi[2])}}
                                @endif
                            </td>
                            <td class="border_bottm color_one  all_td">
                                @if($zusan[2]==0)
                                    {{$count_data}}
                                @else
                                    {{ceil(($count_data-$zusan[2])/$zusan[2])}}
                                @endif
                            </td>
                            <td class="border_bottm color_one  all_td">
                                @if($zuliu[2]==0)
                                    {{$count_data}}
                                @else
                                    {{ceil(($count_data-$zuliu[2])/$zuliu[2])}}
                                @endif
                            </td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                        {{--如果选择前中后三平均遗漏值end--}}

                        @if($xing==5 || $xing==6)
                            <td class="border_bottm color_one  all_td">
                                @if($duizi[2]==0)
                                    {{$count_data}}
                                @else
                                    {{ceil(($count_data-$duizi[2])/$duizi[2])}}
                                @endif
                            </td>
                            @for($i=0;$i<10;$i++)
                                <td rowspan="1" class="border_bottm color_one <?php if($i==9) echo 'all_td'; ?>">
                                    @if($er_arr[$i][2] == 0)
                                        {{$count_data}}
                                    @else
                                        {{ceil(($count_data-$er_arr[$i][2])/$er_arr[$i][2])}}
                                    @endif
                                </td>
                            @endfor
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                    </tr>
                    {{--平均遗漏值end--}}


                    {{--最大遗漏值start--}}
                    <tr class="height40">
                        <th rowspan="1" class="all_td border_bottm color_one">最大遗漏值</th>
                        <td class="all_td border_bottm color_one" colspan="4"></td>
                        @for($j=$start_j;$j<$number_length-1;$j++)
                            @for($i=0;$i<10;$i++)
                                <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">{{$arr[$j][$i][3]}}</td>
                            @endfor
                        @endfor

                        {{--号码分布最大遗漏值start--}}
                        @for($i=0;$i<10;$i++)
                            <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">{{$fenbu_total_arr[$i][3]}}</td>
                        @endfor
                        {{--号码分布最大遗漏值end--}}

                        {{--如果选择前中后三最大遗漏值start--}}
                        @if($xing==2 || $xing==3 || $xing==4)
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td">{{$baozi[4]}}</td>
                            <td class="border_bottm color_one  all_td">{{$zusan[4]}}</td>
                            <td class="border_bottm color_one  all_td">{{$zuliu[4]}}</td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                        {{--如果选择前中后三最大遗漏值end--}}

                        @if($xing==5 || $xing==6)
                            <td class="border_bottm color_one  all_td">{{$duizi[3]}}</td>
                            @for($i=0;$i<10;$i++)
                                <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">{{$er_arr[$i][3]}}</td>
                            @endfor
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                    </tr>
                    {{--最大遗漏值end--}}

                    {{--最大连出值start--}}
                    <tr class="height40">
                        <th rowspan="1" class="all_td border_bottm color_one">最大连出值</th>
                        <td class="all_td border_bottm color_one" colspan="4"></td>
                        @for($j=$start_j;$j<$number_length-1;$j++)
                            @for($i=0;$i<10;$i++)
                                <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">{{$arr[$j][$i][5]}}</td>
                            @endfor
                        @endfor


                        {{--号码分布最大连出值start--}}
                        @for($i=0;$i<10;$i++)
                            <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">{{$fenbu_total_arr[$i][5]}}</td>
                        @endfor
                        {{--号码分布最大连出值end--}}

                        {{--如果选择前中后三最大连出值start--}}
                        @if($xing==2 || $xing==3 || $xing==4)
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td">{{$baozi[6]}}</td>
                            <td class="border_bottm color_one  all_td">{{$zusan[6]}}</td>
                            <td class="border_bottm color_one  all_td">{{$zuliu[6]}}</td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                        {{--如果选择前中后三最大连出值end--}}

                        @if($xing==5 || $xing==6)
                            <td class="border_bottm color_one  all_td">{{$duizi[5]}}</td>
                            @for($i=0;$i<10;$i++)
                                <td class="border_bottm color_one <?php if($i==9)echo 'all_td';?>">{{$er_arr[$i][5]}}</td>
                            @endfor
                            <td class="border_bottm color_one  all_td"></td>
                        @endif
                    </tr>
                    {{--最大连出值end--}}



                </tbody>



                {{--下方表头start--}}
                <tbody>
                    {{--0-10显示--}}
                    <tr style="height: 2.25rem">
                        <th rowspan="2" class="all_td border_bottm color_one qihao" >期号</th>
                        <th class="all_td border_bottm color_one kaijiangjieguo" colspan="4" rowspan="2" >开奖结果</th>
                        @for($j=$start_j;$j<$number_length;$j++)
                            @for($i=0;$i<10;$i++)
                                <td class=" border_bottm color_two <?php if($i==9)echo 'all_td';?>">{{$i}}</td>
                            @endfor
                        @endfor
                        @if($xing==2 || $xing==3 || $xing==4)
                            <th class="all_td color_one max_right" rowspan="2">大小形态</th>
                            <th class="all_td color_one max_right" rowspan="2">单双形态</th>
                            <th class="all_td color_one max_right" rowspan="2">质合形态</th>
                            <th class="all_td color_one max_right" rowspan="2">012形态</th>
                            <th class="all_td color_one" rowspan="2" style="width: 5rem">豹子(三同号)</th>
                            <th class="all_td color_one zusan" rowspan="2">组三</th>
                            <th class="all_td color_one zusan" rowspan="2">组六</th>
                            <th class="all_td color_one zusan" rowspan="2">跨度</th>
                            <th class="all_td color_one zusan" rowspan="2">和值</th>
                            <th class="all_td color_one" rowspan="2" style="width: 3.75rem">和值尾数</th>
                        @endif
                        @if($xing==5 || $xing==6)
                            <th class="all_td color_one" rowspan="2" style="width: 5rem">对子(二同号)</th>
                            @for($i=0;$i<10;$i++)
                                <td class="border_bottm color_two <?php if($i==9)echo 'all_td';?>">{{$i}}</td>
                            @endfor
                            <th class="all_td color_one er_hezhi" rowspan="2">和值</th>
                        @endif
                    </tr>
                    <tr style="height: 2.25rem">
                        @for($j=$start_j;$j<$number_length-1;$j++)
                            <th class="all_td border_bottm color_one wanqian" colspan="10" >{{$header_name[$j]}}</th>
                        @endfor
                        <th class="all_td border_bottm color_one haomafenbu" colspan="10" >号码分布</th>
                        @if($xing==5 || $xing==6)
                                <th class="all_td border_bottm color_one" colspan="10">跨度走势</th>
                        @endif
                    </tr>
                </tbody>
                {{--下方表头end--}}
            @else
                {{--如果没数据的时候显示暂无数据start--}}
                <form class="layui-form" action="">
                    <div class="layui-form-item" pane="">
                        <div class="layui-input-block" id="zanwu">
                            暂无数据
                        </div>
                    </div>
                </form>
                {{--如果没数据的时候显示暂无数据end--}}
            @endif
        </table>
        <input type="hidden" value="{{$number}}" id="show_number">
        <div id="canvasdiv"></div>
    </form>
    <input id="arr" type="hidden" value="{{json_encode($arr)}}">
</div>

</div>



<script src="{{asset('/js/jquery-1.9.1.js')}}"></script>
<script src="{{asset('/plugins/layui/layui.js')}}"></script>
<script type="text/javascript">
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        laydate.render({
            elem: '#test6'
            ,range: true
        });

        /*辅助线*/
        form.on('checkbox(fuzhuxian)', function(data){
            var is_check = data.elem.checked;
            if(is_check){
                $(".border_bottm_5").css("border-bottom","1px solid rgba(155,155,155,0.28)");
            }else{
                $(".border_bottm_5").css("border-bottom","1px solid #fff");
            }
        });

        /*遗漏值是否显示*/
        form.on('checkbox(yilou)', function(data){
            var yiloutiao = $('#yiloutiao').is(':checked');
            var is_check = data.elem.checked;
            if(is_check){
               $(".yilou").css("color","#666");
            }else{
                $(".yilou").css("color","#fff");
                if(yiloutiao){
                    var arr = $("#arr").val();
                    arr = JSON.parse(arr);
                    show_yiloutiao(arr,"#DADAE5","#B7B7B7");
                }
            }
        });

        /*连线是否显示*/
        form.on('checkbox(lianxian)', function(data){
            var is_check = data.elem.checked;
            if(is_check){
                $(".lianxian").show();
            }else{
                $(".lianxian").hide();
            }
        });

        /*遗漏条是否显示*/
        form.on('checkbox(yiloutiao)', function(data){
            var is_check = data.elem.checked;
            var arr = $("#arr").val();
            arr = JSON.parse(arr);
            if(is_check){
                show_yiloutiao(arr,"#DADAE5","#B7B7B7")
            }else{
                show_yiloutiao(arr,"#fff","#666")
            }
        });


        /*类型选择*/
        form.on('select(leixing)', function(data){
            var game_type_id = data.value;
            switch (game_type_id){
                case "1":
                    location.href = "/shishicai/";
                    break;
                case "2":
                    location.href = "/xuan5/";
                    break;
                case "3":
                    location.href = "/kuaisan/";
                    break;
                case "4":
                    location.href = "/pk10/";
                    break;
                case "5":
                    location.href = "/fucai3d/";
                    break;
                case "6":
                    location.href = "/liuhecai/";
                    break;
                case "7":
                    location.href = "/pcdd/";
                    break;
                case "8":
                    location.href = "/beijingkl8/";
                    break;
            }
        });


        /*游戏选择*/
        form.on('select(game)', function(data){
            var game_id = data.value;
            var game_type_id = $("#leixing").val();
            switch (game_type_id){
                case "1":
                    location.href = "/shishicai?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "2":
                    location.href = "/xuan5?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "3":
                    location.href = "/kuaisan?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "4":
                    location.href = "/pk10?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "5":
                    location.href = "/fucai3d?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "6":
                    location.href = "/liuhecai?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "7":
                    location.href = "/pcdd?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
                case "8":
                    location.href = "/beijingkl8?game_type_id="+game_type_id+"&game_id="+game_id;
                    break;
            }
        });


    });


    /**
     * 作用：遗漏条显示不同样式
     *作者：信
     *时间：2018/05/14
     * */
    function show_yiloutiao(arr,bg,color) {
        for(var i=0;i<arr.length;i++){
            for (var j=0;j<arr[i].length;j++){
                var class_name = "yiloutiao"+i+j+arr[i][j][7];
                $("."+class_name).css("background",bg);
                $("."+class_name).css("color",color);
            }
        }
    }

    function show_bottom(color) {
        var arr = $("#arr").val();
        arr = JSON.parse(arr);
        for(var i=0;i<arr.length;i++){
            for (var j=0;j<arr[i].length;j++){
                var class_name = "yiloutiao"+i+j+arr[i][j][7];
                $("."+class_name).css("border-bottom","1px solid "+color);
            }
        }
    }


    /*页面初始化*/
    $(function () {
        var $number = $("#show_number").val();
        for (var i=0;i<$number;i++){
            var ids = $(".xian"+i);
            CreateLine(ids, 20, "#ff6600", "canvasdiv", "#d5d5d5");
        }

        /**
         * 作用：近期 近日数据查询
         *作者：信
         *时间：2018/05/15
         * */
        $(".link_number").click(function () {
            var $color_greend =$(".color_greend").attr("data");
            var $data = $(this).attr("data");
            location.href = "/shishicai?number="+$data+"&xing="+$color_greend;
        });

        /**
         * 作用：时间范围查询
         *作者：信
         *时间：2018/05/15
         * */
        $(".chaxun").click(function () {
            var $color_greend =$(".color_greend").attr("data");
            var $data = $("#test6").val();
            location.href = "/shishicai?time="+$data+"&xing="+$color_greend;
        });


        /**
         * 作用：五星 四星等选择
         *作者：信
         *时间：2018/05/15
         * */
        $(".category").click(function () {
            var $data = $(this).attr("data");
            location.href = "/shishicai?xing="+$data;
        });


    });



    /*画线*/
    function CreateLine(ids, w, c, div, bg) {
        var list = ids;
        for (var j = list.length - 1; j > 0; j--) {
            var first_td_id = $(list[j]);     /*连线起点第一个td*/
            var second_td_id = $(list[j - 1]); /*连线起点第二个td*/
            var f_width = second_td_id.outerWidth();     /*第二个td的外宽度 外宽（包含：border，padding）*/
            var f_height = second_td_id.outerHeight();   /*第二个td的外高度 外高（包含：border，padding）*/
            /*第一个td的偏移量*/
            var t_offset = first_td_id.offset();
            var t_top = t_offset.top;
            var t_left = t_offset.left;
            /*第二个td的偏移量*/
            var f_offset = second_td_id.offset();
            var f_top = f_offset.top;
            var f_left = f_offset.left;
            /*返回最低值*/
            var cvs_left = Math.min(f_left, t_left);
            var cvs_top = Math.min(f_top, t_top);
            /*创建画布并设置属性*/
            var cvs = document.createElement("canvas");
            cvs.setAttribute("class","lianxian");
            cvs.width           = Math.abs(f_left - t_left)-2 < w-2 ? w-2 : Math.abs(f_left - t_left)-2;
            cvs.height          = Math.abs(f_top - t_top)-2 ;
            cvs.style.top       = (cvs_top + parseInt(f_height / 2)+2)/16 + "rem";
            cvs.style.left      = (cvs_left + parseInt(f_width / 2))/16 + "rem";
            cvs.style.position  = "absolute";
            var cxt = cvs.getContext("2d");
            cxt.save();
            cxt.strokeStyle = c;        /*设置画线的颜色*/
            cxt.lineWidth = 2;          /*线的大小*/
            cxt.lineJoin = "round";     /*两条线交会时创建圆形边角*/
            cxt.beginPath();            /*开画*/
            cxt.moveTo(f_left - cvs_left , f_top - cvs_top);     /*起点坐标*/
            cxt.lineTo(t_left - cvs_left , t_top - cvs_top );     /*终点坐标*/
            cxt.closePath();        /*结束*/
            cxt.stroke();           /*进行画线*/
            cxt.restore();          /*去除保存状态*/
            $("#" + div).append(cvs);
        }
    }
</script>
</body>
</html>