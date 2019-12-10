<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>投注管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
</head>
<style>
    .text_div{
        height: 30px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_div1{
        height: 30px;
        line-height: 0px;
        display: inline-block;

    }
    .text_span{
        display: inline-block ;
    }
    .username_input{
        width: 150px;
        display: inline-block;
    }
    tr th{
        text-align: center!important;
    }
    tr td{
        text-align: center!important;
    }
    td button{
        margin: 0!important;
    }
    .neiron{
        max-height: 20px!important;
        max-width: 100px!important;
        overflow: hidden!important;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .look_neiron:hover{
        color: dodgerblue;
        text-decoration: underline;
    }
</style>

<body>
<div class="layui-fluid main">
    <div class="layui-tab">
        <fieldset class="layui-field-title layui-elem-field">
            <legend>投注管理</legend>
        </fieldset>
        <form method="post" action="{{url('manager/getOrder_back')}}" style="margin-top: 25px;">
            {{ csrf_field() }}
            <div style="display: inline-block;padding: 0 30px">
                <div>
                    <div class="text_div">订单号<span class="text_span"></span></div>
                    <input id="dingdanhao" value="{{Request::input('order_id')}}" placeholder="请输入订单号" name="order_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;">
                <div>
                    <div class="text_div">用户名<span class="text_span"></span></div>
                    <input id="yonghuming" value="{{Request::input('user_id')}}" name="user_id"  placeholder="请输入用户名" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 20px">
                <div>
                    <div class="text_div">期号<span class="text_span"></span></div>
                    <input id="qihao" value="{{Request::input('bet_period')}}" name="bet_period" placeholder="请输入期号" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;">
                <div>
                    <div class="text_div" style="margin-left: 25px">日期<span class="text_span"></span></div>
                    <div style="display: inline-block">
                        <input placeholder="开始时间" value="{{ Request::input('date_begin')?Request::input('date_begin'):''}}"  type="text" class="layui-input" id="date_begin" name="date_begin" style="width: 100px">
                    </div> —
                    <div style="display: inline-block">
                        <input placeholder="结束时间" value="{{ Request::input('date_end')?Request::input('date_end'):''}}" type="text" class="layui-input" id="date_end" name="date_end" style="width: 100px">
                    </div>
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <span>游戏</span>
                    <select class="layui-select" name="game_id" id="game_id" style="width: 150px">
                        <option value="">全部</option>
                        @foreach($game_list as $item)
                            <option @if($item['id']==Request::input('game_id')) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <span>状态</span>
                    <select class="layui-select" name="is_status" id="is_status" style="width: 150px">
                        <option value="">全部</option>
                        <option value="1" <?php if($is_status=="1") echo 'selected=""'?>>已中奖</option>
                        <option value="2" <?php if($is_status=="2") echo 'selected=""'?>>未中奖</option>
                        <option value="3" <?php if($is_status=="3") echo 'selected=""'?>>未开奖</option>
                        <option value="4" <?php if($is_status=="4") echo 'selected=""'?>>已撤单</option>
                    </select>
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <span>房间</span>
                    <select class="layui-select" name="room_type" id="room_type" style="width: 150px">
                        <option value="">全部</option>
                        <option value="1" <?php if($room_type=="1") echo 'selected=""'?>>初级房</option>
                        <option value="2" <?php if($room_type=="2") echo 'selected=""'?>>中级房</option>
                        <option value="3" <?php if($room_type=="3") echo 'selected=""'?>>高级房</option>
                        <option value="3" <?php if($room_type=="4") echo 'selected=""'?>>尊贵房</option>
                    </select>
                </div>
            </div>
            <input type="submit" class="layui-btn" value="查询" style="position: relative;top: -2px;margin-left: 0">
        </form>
        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>注单编号</th>
                        <th>用户</th>
                        <th>下注时余额</th>
                        <th>游戏</th>
                        <th>玩法</th>
                        <th>期号</th>
                        <th style="min-width: 60px!important;">金额
                            <span data="asc"   style="cursor: pointer; <?php if($order=='asc')echo 'color:red'; ?>" class="icon_shang money_order">▲</span>
                            <span data="desc" class="icon_xia money_order" style="cursor: pointer;margin-left: -10px!important;<?php if($order=='desc')echo 'color:red'; ?>">▼</span>
                        </th>
                        <th class="neiron">投注内容</th>
                        <th>房间</th>
                        <th>奖金</th>
                        <th>开奖号</th>
                        <th>状态</th>
                        <th>投注时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
            <tbody>
                @forelse($data as $key=>$val)
                    <tr>
                        <td>{{$val["order_id"]}}</td>
                        <td>{{$val["user"]["username"]}}</td>
                        <td>{{$val["balance"]}}</td>
                        <td>{{$val["odds"]["gameName"]}}</td>
                        <td>{{$val["odds"]["category"]}}</td>
                        <td>{{$val["bet_period"]}}</td>
                        <td style="min-width: 50px!important;">{{$val["bet_money"]}}</td>
                        <td data="{{$val["id"]}}" class="neiron look_neiron" title="点击查看投注内容详情">
                            <?php
                            $str = $val['bet_value'];
                            $index = strpos($str,'|');
                            if($index){
                                $wei = substr($str,0,$index);
                                $wei = str_split($wei,1);
                                foreach ($wei as $key=>$item)
                                {
                                    switch ($item)
                                    {
                                        case '0':
                                            $wei[$key] = '万';
                                            break;
                                        case '1':
                                            $wei[$key] = '千';
                                            break;
                                        case '2':
                                            $wei[$key] = '百';
                                            break;
                                        case '3':
                                            $wei[$key] = '十';
                                            break;
                                        case '4':
                                            $wei[$key] = '个';
                                            break;
                                    }
                                }
                                $wei = implode('',$wei);
                                $str = substr($str,$index);
                                $str = $wei.$str;
                                echo $str;
                            }else{
                                echo $str;
                            }

                            ?>
                        </td>
                        <td>
                            @if($val["room_type"]==1)初级房
                            @elseif($val["room_type"]==2)中级房
                            @elseif($val["room_type"]==3)高级房
                            @elseif($val["room_type"]==4)尊贵房
                            @endif
                        </td>
                        <td>
                            @if($val["delete_time"]!="0")
                                <span style="color: red;">已撤单</span>
                            @elseif($val["status"])
                                @if($val["bonus"]=="0")
                                    <span>未中奖</span>
                                @else
                                    <span style="color: green;font-weight: bold">{{$val["bonus"]}}</span>
                                @endif
                            @else
                               未开奖
                            @endif
                        </td>
                        <td>{{$val["lotteryNo"]}}</td>
                        <td>
                            @if($val["delete_time"]!=0)
                                <span style="color: red;">已撤单</span>
                            @elseif($val['result']==1)
                                <span style="color: green;font-weight: bold">已中奖</span>
                            @elseif($val['status']==1&&$val['result']==0)
                                未中奖
                            @elseif($val['status']==0&&$val['delete_time']==0)
                                未开奖
                            @endif
                        </td>
                        <td> {{$val["order_dateTime"]}}</td>
                        <td>
                            {{--<button data="{{$val["id"]}}" class="layui-btn layui-btn-small layui-btn-primary order_detail">详情</button>--}}
                            @if(!$val["status"] && $val["delete_time"]==0)
                                {{--<button data="{{$val["id"]}}" class="layui-btn layui-btn-small modification">修改</button>--}}
                                <button data="{{$val["id"]}}" class="layui-btn layui-btn-small layui-btn-danger backout">撤单</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13"> 暂无数据 </td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="5"> </td>
                    <td colspan="2"><span>总计投注金额：</span> {{$orderBetZ}} </td>
                    <td colspan="2"><span>总计中奖金额：</span> {{$orderBetMoney}} </td>
                    <td colspan="4"> </td>
                </tr>
            </tbody>
        </table>
        {{$data->appends([
            "game_id"   =>  Request::input("game_id"),
            "user_id"   =>  Request::input("user_id"),
            "order_id"  =>  Request::input("order_id"),
            "bet_period"=>  Request::input("bet_period"),
            "is_status"=>  Request::input("is_status"),
            "room_type" => Request::input("room_type"),
            "date_begin" => Request::input('date_begin'),
            "date_end" => Request::input('date_end'),
            "order"     =>  $order
        ])->links()}}
        </div>
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;

        /**
         * 作用：撤单
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(document).on("click",".backout",function () {
           var id = $(this).attr("data");
           layer.confirm("确定撤销此订单吗?",function () {
               $.ajax({
                   type:"post",
                   url:"{{url("manager/backout_order")}}",
                   data:{"id":id},
                   headers:{
                       'X-CSRF-TOKEN': '{{ csrf_token() }}'
                   },
                   success:function (res) {
                        layer.msg(res.msg);
                        if(res.code){
                            location.reload();
                        }
                   },
                   error:function (err) {
                        layer.msg("系统异常，请稍后再试")
                   }
               })
           })
        });


        /**
         * 作用：清空选择
         * 作者：信
         * 修改：暂无
         * 时间：2018/06/14
         */
        $(document).on("click",".clear",function () {
            $("#game_id").val("");
            $("#dingdanhao").val("");
            $("#qihao").val("");
            $("#yonghuming").val("");
            $("#date_begin").val("");
            $("#date_end").val("");
            $("#is_status").val("");
            $("#room_type").val("");
        });



        /**
         * 作用：各种排序
         * 作者：信
         * 时间：2018/06/13
         * 修改：暂无
         */
        $(document).on("click",".money_order",function () {
            var order        = $(this).attr("data");
            var dingdanhao    = $("#dingdanhao").val();
            var yonghuming    = $("#yonghuming").val();
            var qihao    = $("#qihao").val();
            var game_id    = $("#game_id").val();
            var is_status = $("#is_status").val();
            var room_type = $("#room_type").val();
            location.href ='{{url("manager/getOrder_back")}}?order='+order+"&dingdanhao="+dingdanhao+"&yonghuming="+yonghuming+"&qihao="+qihao+"&game_id="+game_id+"&is_status="+is_status+"&room_type="+room_type;
        });


        /**
         * 作用：修改
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(document).on("click",".modification",function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '修改订单信息',
                shadeClose: true,
                shade: 0,
                area: ['60%', '90%'],
                maxmin: true,
                content: '{{ url("manager/modification_order") }}?id='+id,
            });
        });



        /**
         * 作用：订单详情
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(".order_detail").click(function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '订单详情',
                shadeClose: true,
                shade: 0,
                area: ['70%', '90%'],
                maxmin: true,
                content: '{{ url("manager/getOrder_detail") }}?id='+id,
            });
        });


        /**
         * 作用：查看投注内容详情
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(".look_neiron").click(function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '投注内容详情',
                shadeClose: true,
                shade: 0,
                area: ['60%', '80%'],
                maxmin: true,
                content: '{{ url("manager/look_neiron") }}?id='+id,
            });
        });

        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_begin' //指定元素
            });
        });
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_end' //指定元素
            });
        });

    });
</script>

</body>

</html>