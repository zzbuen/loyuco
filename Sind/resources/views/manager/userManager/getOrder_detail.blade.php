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
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>订单号 - {{$data["order_id"]}}</legend>
        </fieldset>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">订单号</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$data["order_id"]}}" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">期号</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$data["bet_period"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">游戏名称</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$data["odds"]['gameName']}}" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">游戏类型</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$data["odds"]['type']}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">游戏玩法</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$data["odds"]['category']}}" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">子玩法</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$data["odds"]['ruleName']}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">投注内容</label>
                        <div class="layui-input-inline">
                            <input readonly type="text" style="white-space: nowrap;text-overflow: ellipsis;" data="{{$data["id"]}}" title="点击查看投注内容详情"
                                   value="<?php

                                   $str = $data['bet_value'];
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

                                   ?>" lay-verify="required" autocomplete="off" class="layui-input look_neiron">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">投注金额</label>
                        <div class="layui-input-inline">
                            <input  disabled   name="user_gold"  type="text" autocomplete="off" value="{{$data["bet_money"]}}"  class="layui-input" >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">返点</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$data["fanDian"]}}" lay-verify="required" autocomplete="off" class="layui-input ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">返点金额</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$data["bet_money"]*$data["fanDian"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">倍数</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$data['beiShu']}}" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">模式</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold" style="color: red"  type="text" autocomplete="off" value="{{$data['mode']}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">注数</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$data["bet_num"]}}" lay-verify="required" autocomplete="off" class="layui-input ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">上级返点</label>
                        <div class="layui-input-inline">
                            @if($data["status"])
                                <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$data["fanDianAmount"]}}"  class="layui-input " >
                            @else
                                <input disabled  name="user_gold"  type="text" autocomplete="off" value="未开奖"  class="layui-input " >
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">开奖时间</label>
                        <div class="layui-input-inline">
                            <input disabled type="text"  value="{{$draw_res["kaijiang_time"]}}" lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">投注时间</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$data["order_dateTime"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">开奖号码</label>
                        <div class="layui-input-inline">
                            @if($data["status"])
                                <input disabled  name="user_gold"  type="text" autocomplete="off" value="{{$draw_res["result"]}}"  class="layui-input " >
                            @else
                                <input disabled  name="user_gold"  type="text" autocomplete="off" value="未开奖"  class="layui-input " >
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">中奖金额</label>
                        <div class="layui-input-inline">
                            @if($data["status"])
                                @if($data["bonus"]=="0")
                                    <input disabled  name="user_gold" type="text" style="color: green" autocomplete="off" value="未中奖"  class="layui-input " >
                                @else
                                    <input disabled  name="user_gold" type="text" style="color: red;font-weight: bold" autocomplete="off" value="{{$data["bonus"]}}"  class="layui-input " >
                                @endif
                            @else
                                <input disabled  name="user_gold" type="text" autocomplete="off" value="未开奖"  class="layui-input " >
                            @endif
                        </div>
                    </div>
                </div>
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

    });
</script>

</body>

</html>