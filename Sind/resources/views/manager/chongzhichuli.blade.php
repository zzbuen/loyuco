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

<body>
<div class="layui-everyday-list">
    <form class="layui-form layui-form-pane" action="" style=";margin-top: 10%">
        <div class="layui-form-item" style="display:flex; justify-content:center;">
            <label class="layui-form-label">用户姓名</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["user"]["username"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="display:flex; justify-content:center;">
            <label class="layui-form-label">充值金额</label>
            <div class="layui-input-inline">
                <input type="text" id="money" name="source_name" disabled="disabled" value="{{$data["trade_amount"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="text-align: center">
            <input type="hidden" id="user_id" value="{{$data["user_id"]}}">
            <input type="hidden" id="chuli_id" value="{{$data["id"]}}">
            <button class="layui-btn" type="button" id="sure">确定充值</button>
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



        /*处理提现请求*/
        $(document).on("click","#sure",function () {
            var money = $("#money").val();
            var user_id = $("#user_id").val();
            var chuli_id = $("#chuli_id").val();
            $.ajax({
                type: "POST",
                url: "/manager/chongzhichuli_ajax",
                data: {"money":money,"user_id":user_id,"chuli_id":chuli_id},
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.msg(res.msg);
                    if(res.code){
                        parent.location.reload();
                    }
                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            });
        })

    });

</script>

</body>

</html>