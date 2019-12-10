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
            <legend>用户 - {{$data["user"]["username"]}}</legend>
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
                        <label class="layui-form-label">投注金额</label>
                        <div class="layui-input-inline">
                            <input disabled  name="user_gold" style="color: red"  type="text" autocomplete="off" value="{{$data['bet_money']}}"  class="layui-input " >
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

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">投注内容</label>
            <div class="layui-input-block">
                <textarea disabled  class="layui-textarea">
                        @if($file!='')
                            <?php

                            $str = $file;
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

                        @else
                            {{--{{$data['bet_value']}}--}}
                            <?php

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

                            ?>
                        @endif

                </textarea>

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