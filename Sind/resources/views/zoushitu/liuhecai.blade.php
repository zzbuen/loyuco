<!DOCTYPE html>
<?php
use App\Http\Controllers\ZoushituController;
$model = new ZoushituController();
?>
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{asset('/plugins/layui/css/layui.css')}}" media="all">
    <title></title>
    <style>
        /*样式*/
        table { border: 0; margin: 0; border-collapse: collapse; border-spacing: 0; font-size: 0.6rem; font-family: Arial; margin: 0 auto; }
        table td, table th { padding: 0; height: 1.4rem; width: 1.4rem; text-align: center; color: #666; }
        table th { font-weight: bold; color: #000;font-size: 0.8rem }
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
            /*margin-left: 5rem;*/
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
        .big{
            margin: 0 auto;
            width: 3rem;
            height: 5rem;
        }
        .big_1{
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            position: relative;
            border: 2px solid gray;
        }
        .tema_1{
            width: 3rem;
            height: 3rem;
            line-height: 3rem;
            border-radius: 50%;
            position: relative;
            font-size: 1.5rem;
            border: 2px solid gray;
        }
        .big_2{
            border-radius: 50%;
            width:2.5rem;
            height: 2.5rem;
            line-height: 2.5rem;
            text-align: center;
            font-size: 1.5rem;
            border: 2px solid gray;
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        .big_3{
            width:100%;
            height: 2.5rem;
            line-height: 2.5rem;
            font-size: 1rem;
            text-align: center;
        }
        .tema_3{
            width:100%;
            height: 2.5rem;
            line-height: 2.5rem;
            font-size: 1rem;
            text-align: center;
        }
        .border_red{
            border-color: indianred;
        }
        .border_blue{
            border-color:dodgerblue;
        }
        .border_green{
            border-color: green;
        }
        .tema_red{
            background-color: indianred;
            border-color: indianred;
            color: white;
        }
        .tema_blue{
            background-color:dodgerblue;
            border-color:dodgerblue;
            color: white;
        }
        .tema_green{
            background-color: green;
            border-color: green;
            color: white;
        }
        .font_size{
            font-size: 1rem;
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
            <li class="category color_greend" >六合彩</li>
        </ul>
    </div>
<div id="main">
    {{--遗漏辅助线近期等选择start--}}
    <div >
        <form class="layui-form" action="">
            <div class="layui-form-item" pane="">
                <div class="layui-input-block" style="margin: 0px">
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
        <table id="zstable"  style="width: 100%;float: left;border-right: 1px solid rgba(155, 155, 155, 0.28)">
                {{--表头显示start--}}
                <thead>
                    <tr style="height: 2.5rem;">
                        <th class="all_td color_one" style="width: 10%">期号</th>
                        <th class="all_td border_bottm color_one" style="width: 11%">开奖时间</th>
                        <th class="color_one" style="width: 5%"></th>
                        <th class="color_one" style="width: 7%">正一</th>
                        <th class="color_one" style="width: 7%">正二</th>
                        <th class="color_one" style="width: 7%">正三</th>
                        <th class="color_one" style="width: 7%">正四</th>
                        <th class="color_one" style="width: 7%">正五</th>
                        <th class="color_one" style="width: 7%">正六</th>
                        <th class="color_one" style="width: 7%">特码</th>
                        <th class="all_td color_one" style="width: 5%"></th>
                        <th class="all_td border_bottm color_one" style="width: 10%" colspan="3">特码</th>
                        <th class="all_td color_one" style="width: 10%" colspan="4">和值</th>
                    </tr>
                </thead>
                {{--表头显示end--}}

                {{--内容显示start--}}
                <tbody>
                    @forelse($data as $key=>$value)
                        <tr style="height: 6rem">
                            <td class="all_td border_bottm border_left" style="width: 10%;font-size: 1rem">{{$value["periods"]}}</td>
                            <td class="all_td border_bottm " style="width: 11%;font-size: 1rem">{{$value["kaijiang_time"]}}</td>
                            <td class="border_bottm" style="width: 5%"></td>
                            <td class="border_bottm" style="width: 7%">
                               <div class="big">
                                   <div class="big_1 <?php $res = substr($value["result"],0,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">
                                       <div class="big_2 <?php $res = substr($value["result"],0,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">{{substr($value["result"],0,2)}}</div>
                                   </div>
                                   <div class="big_3">
                                       <?php
                                           $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                           echo $model->shengxiao($kaijiang_time,$res);
                                       ?>
                                   </div>
                               </div>
                            </td>
                            <td class="border_bottm" style="width: 7%">
                                <div class="big">
                                    <div class="big_1 <?php $res = substr($value["result"],3,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">
                                        <div class="big_2 <?php $res = substr($value["result"],3,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">{{substr($value["result"],3,2)}}</div>
                                    </div>
                                    <div class="big_3">
                                        <?php
                                        $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                        echo $model->shengxiao($kaijiang_time,$res);
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td class="border_bottm" style="width: 7%">
                                <div class="big">
                                    <div class="big_1 <?php $res = substr($value["result"],6,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">
                                        <div class="big_2 <?php $res = substr($value["result"],6,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">{{substr($value["result"],6,2)}}</div>
                                    </div>
                                    <div class="big_3">
                                        <?php
                                        $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                        echo $model->shengxiao($kaijiang_time,$res);
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td class="border_bottm" style="width: 7%">
                                <div class="big">
                                    <div class="big_1 <?php $res = substr($value["result"],9,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">
                                        <div class="big_2 <?php $res = substr($value["result"],9,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">{{substr($value["result"],9,2)}}</div>
                                    </div>
                                    <div class="big_3">
                                        <?php
                                        $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                        echo $model->shengxiao($kaijiang_time,$res);
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td class="border_bottm" style="width: 7%">
                                <div class="big">
                                    <div class="big_1 <?php $res = substr($value["result"],12,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">
                                        <div class="big_2 <?php $res = substr($value["result"],12,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">{{substr($value["result"],12,2)}}</div>
                                    </div>
                                    <div class="big_3 ">
                                        <?php
                                        $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                        echo $model->shengxiao($kaijiang_time,$res);
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td class="border_bottm" style="width: 7%">
                                <div class="big">
                                    <div class="big_1 <?php $res = substr($value["result"],15,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>" >
                                        <div class="big_2 <?php $res = substr($value["result"],15,2);if(in_array($res,$red)){echo 'border_red';}if(in_array($res,$blue)){echo 'border_blue';}if(in_array($res,$green)){echo 'border_green';}?>">{{substr($value["result"],15,2)}}</div>
                                    </div>
                                    <div class="big_3">
                                        <?php
                                        $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                        echo $model->shengxiao($kaijiang_time,$res);
                                        ?>
                                    </div>
                                </div>
                            </td>
                            {{--特码--}}
                            <td class="border_bottm" style="width: 7%">
                                <div class="big">
                                    <div class="tema_1 <?php $res = substr($value["result"],18,2);if(in_array($res,$red)){echo 'tema_red';}if(in_array($res,$blue)){echo 'tema_blue';}if(in_array($res,$green)){echo 'tema_green';}?>">
                                        {{substr($value["result"],18,2)}}
                                    </div>
                                    <div class="tema_3">
                                        <?php
                                        $kaijiang_time =  substr($value["kaijiang_time"],0,4);
                                        echo $model->shengxiao($kaijiang_time,$res);
                                        ?>
                                    </div>
                                </div>
                            </td>
                            @php
                                $tema = substr($value["result"],18,2);
                                $hezhi = array_sum(explode(",",$value["result"]));
                                $first_tema = substr($data[$key-1]["result"],18,2);
                                $first_hezhi = array_sum(explode(",",$data[$key-1]["result"]));
                            @endphp
                            <td class="all_td border_bottm font_size" style="width: 5%"></td>
                            <td class="all_td border_bottm font_size">
                                @if($tema>=25)
                                    大
                                @else
                                    小
                                @endif
                            </td>
                            <td class="all_td border_bottm font_size">
                                @if($tema%2==0)
                                    双
                                    @else
                                    单
                                @endif
                            </td>
                            <td class="all_td border_bottm font_size">
                                @if($first_tema!==false)
                                    @if($tema-0>$first_tema-0)
                                        升
                                    @elseif($tema==$first_tema)
                                        平
                                    @else
                                    降
                                    @endif
                                @else
                                    平
                                @endif
                            </td>
                            <td class="all_td border_bottm font_size">
                                @if($hezhi>=172)
                                    大
                                @else
                                    小
                                @endif
                            </td>
                            <td class="all_td border_bottm font_size">
                                @if($hezhi%2==0)
                                    双
                                @else
                                    单
                                @endif
                            </td>
                            <td class="all_td border_bottm font_size">
                                @if($first_hezhi!==false)
                                    @if($hezhi-0>$first_hezhi-0)
                                        升
                                    @elseif($hezhi==$first_hezhi)
                                        平
                                    @else
                                        降
                                    @endif
                                @else
                                    平
                                @endif
                            </td>
                            <td class="all_td border_bottm font_size">{{$hezhi}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="17" style="font-size: 1rem">无数据</td>
                        </tr>
                    @endforelse
                </tbody>
            {{--内容显示end--}}
        </table>
        <input type="hidden" value="{{$number}}" id="show_number">
        <div id="canvasdiv"></div>
    </form>

</div>

</div>



<script src="{{asset('/js/jquery-1.9.1.js')}}"></script>
<script src="{{asset('/plugins/layui/layui.js')}}"></script>
<script src="{{asset('/js/vue.js')}}"></script>
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




    /*页面初始化*/
    $(function () {
        var $number = $("#show_number").val();

        /**
         * 作用：近期 近日数据查询
         *作者：信
         *时间：2018/05/15
         * */
        $(".link_number").click(function () {
            var $data = $(this).attr("data");
            location.href = "/liuhecai?number="+$data;
        });

        /**
         * 作用：时间范围查询
         *作者：信
         *时间：2018/05/15
         * */
        $(".chaxun").click(function () {
            var $data = $("#test6").val();
            location.href = "/liuhecai?time="+$data;
        });




    });

</script>
</body>
</html>