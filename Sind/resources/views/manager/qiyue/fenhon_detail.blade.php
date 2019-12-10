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
    <form style="margin-top: 20px;margin-left: 100px" class="layui-form layui-form-pane" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">序列:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["id"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">分红策略:</label>
            <div class="layui-input-inline">
                @if($data["status"] == 1)
                    <input disabled type="text" name="source_name" value="半月分红不累计" lay-verify="required" autocomplete="off" class="layui-input">
                @endif
                @if($data["status"] == 2)
                    <input disabled type="text" name="source_name" value="半月分红累计" lay-verify="required" autocomplete="off" class="layui-input">
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户名:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["user"]["username"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">上级代理:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["daili"]["username"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">签约时间:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["bonus"]["create_time"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">发放时间:</label>
            <div class="layui-input-inline">
                    <input disabled type="text" name="source_name" value="{{$data["create_time"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">总亏损要求:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["bonus"]["total"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">活跃人数要求:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["bonus"]["amount_num"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">累计金额:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["cum_amount"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">累计亏盈:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["cum_profit"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">活跃金额要求:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["bonus"]["amount_money"]}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">契约签订:</label>
            <div class="layui-input-inline">
                @switch($data["bonus"]["status"])
                    @case(1)
                        <input disabled type="text" name="source_name" value="签订成功" lay-verify="required" autocomplete="off" class="layui-input">
                    @break;
                    @case(2)
                        <input disabled type="text" name="source_name" value="等待签订" lay-verify="required" autocomplete="off" class="layui-input">
                    @break;
                    @case(3)
                        <input disabled type="text" name="source_name" value="拒绝签订" lay-verify="required" autocomplete="off" class="layui-input">
                    @break;
                    @default
                    <input disabled type="text" name="source_name" value="默认" lay-verify="required" autocomplete="off" class="layui-input">
                @endswitch
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分红比例:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["proportional"]}}%" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">应发分红:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$data["bonus_amount"]}}" lay-verify="required" autocomplete="off" class="layui-input">
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