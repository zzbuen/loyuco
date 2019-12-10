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
        width: 100px;
        border-right: 1px solid #e2e2e2;
        float: left;
    }
    .last_game_td{
        border-bottom: none;

    }
    .body_right{

        height: 750px;
        width: 1300px;
        margin-left: 140px;

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
    .text_div{
        height: 30px;
        width: 100px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;
        margin-left: 30px;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
    .input15{
        display: inline-block ;
    }
</style>

<body style="padding: 0 3%">
<fieldset class="layui-elem-field layui-field-title">
    <legend>@if($roleId==1)会员@elseif($roleId==2)代理团队 @endif -{{$user_data["username"]}} - 数据统计</legend>
</fieldset>
<div class="layui-everyday-list" style="margin-top: 5%">
    <form class="layui-form layui-form-pane" action="">
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$user_data["username"]}}" lay-verify="required"  autocomplete="off" class="layui-input">

                        </div>
                    </div>
                </div>
            </div>
            @if($roleId==1)
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">余额</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$user_data["remaining_money"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>

                    </div>
                </div>
            </div>
            @endif
        </div>
        @if($roleId==2)
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">团队人数</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$teamNum}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">团队余额</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$teamMoney}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">投注总额</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$data["betting"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">中奖总额</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$data["winning"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">总充值</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$data["recharge"]}}" lay-verify="required"  autocomplete="off" class="layui-input">

                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">总提现</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$data["withdrawals"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">总彩金</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$data["bonus"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">总盈亏</label>
                        <div class="layui-input-inline">
                            <input type="text" name="source_name" disabled="disabled" value="{{$data["total"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
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