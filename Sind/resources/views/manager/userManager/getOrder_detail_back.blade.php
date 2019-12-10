<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>
<style>
    .game_td{
        height: 37px;
        line-height: 37px;
        width: 100px;
        text-align: center;
        border-bottom: 1px solid #e2e2e2;
        cursor: pointer;

    }
    .game_table{
        width: 8%;
        border-right: 1px solid #e2e2e2;
        float: left;
    }
    .last_game_td{
        border-bottom: none;

    }
    .body_right{
        width: 91%;
    }
    .category_show{
        height: 60px;
        width: 100%;
    }
    .category_li{
        float: left;
        line-height: 60px;
        height: 60px;
        width: 105px;
        text-align: center;
        margin-left: 10px;
    }
    .select_game {
        color:red;
    }
    .add_div{
        height: 39px;
        line-height: 39px;
        width: 99%;
        float: right;
        margin-right: 13px;
    }
    .text_div{
        height: 30px;
        line-height: 0px;
        display: inline-block;
    }
    .text_span{
        display: inline-block ;

    }
    .message_div {
        margin-top: 20px;
        margin-left: 30px;
        font-size: 18px;
    }
</style>

<body>
<div class="layui-fluid main">
    <form style="margin-top: 20px;" class="layui-form layui-form-pane" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">订单号</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$order_list[0]['order_sn']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">用户ID:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$order_list[0]['user_id']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">下注金额:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$order_list[0]['bet_money']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">玩家盈亏:</label>
        <div class="layui-input-inline">
            <input @if($order_list[0]['result']>=0) style="color: red" @else style="color: blue" @endif disabled type="text" name="source_name" value="{{$order_list[0]['result']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">游戏:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$game_list[0]['name']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">购买期号:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$order_list[0]['bet_period']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">购买号码:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name"
                   value="<?php

                   $str = $order_list[0]['bet_value'];
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

                   ?>" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">开奖号码:</label>
        @if($result_list)
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$result_list[0]->draw_number}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        @else
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="还没到开奖时间" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        @endif
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开奖结果1:</label>
        <div class="layui-input-inline">
            @if($result_list)
            <input disabled type="text" name="source_name" value="{{$result_list[0]->draw_cr_sum}}" lay-verify="required" autocomplete="off" class="layui-input">
            @else
            <input disabled type="text" name="source_name" value="还没到开奖时间" lay-verify="required" autocomplete="off" class="layui-input">
            @endif
        </div>
        <label class="layui-form-label">开奖结果2:</label>
        <div class="layui-input-inline">
            @if($result_list)
                <input disabled type="text" name="source_name" value="{{$result_list[0]->draw_dragon_tiger}}" lay-verify="required" autocomplete="off" class="layui-input">
            @else
                <input disabled type="text" name="source_name" value="还没到开奖时间" lay-verify="required" autocomplete="off" class="layui-input">
            @endif
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">购买时间:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$order_list[0]['order_dateTime']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">开奖时间:</label>
        <div class="layui-input-inline">
            @if($result_list)
                <input disabled type="text" name="source_name" value="{{$result_list[0]->draw_time}}" lay-verify="required" autocomplete="off" class="layui-input">
            @else
                <input disabled type="text" name="source_name" value="还没到开奖时间" lay-verify="required" autocomplete="off" class="layui-input">
            @endif
        </div>
    </div>
    </form>
</div>

<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.ajax({
                type: "POST",
                url: "/manager/secure",
                data: data.field,
                dataType: "json",
                success: function(res){
                    layer.close(index);
                    layer.msg(res.msg, {time:1100});
                    if (res.success) {
                        location.reload();
                    }
                }
            });
            return false;
        });
    });
</script>

</body>

</html>