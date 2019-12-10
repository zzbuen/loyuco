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
        width: 80px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
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
        <label class="layui-form-label">用户ID</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$agent_list[0]['user_id']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">用户账号</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$agent_list[0]['username']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    @if($user_info_list)
    <div class="layui-form-item">
        <label class="layui-form-label">手机号:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$user_info_list[0]['mobile_number']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">微信号:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$user_info_list[0]['weixin_number']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">QQ号:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$user_info_list[0]['qq_number']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">邮箱:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$user_info_list[0]['email']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    @endif
    <div class="layui-form-item">
        <label class="layui-form-label">团队人数:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$team_acount[0]['team_acount']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">二级代理数:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$agent_acount[0]['agent_acount']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邀请码:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$relation_list[0]['invitation_num']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">分润比例:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$agent_list[0]['info']['share_percent']}}%" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    @if($user_info_list)
    <div class="layui-form-item">
        <label class="layui-form-label">收款银行:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$user_info_list[0]['bank_name']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">收款账号:</label>
        <div class="layui-input-inline">
            <input disabled type="text" name="source_name" value="{{$user_info_list[0]['bank_account']}}" lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>
    @endif
        <div class="layui-form-item">
            <label class="layui-form-label">总奖金:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$agent_list[0]['info']['totle_profit']}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">已提现金额:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$agent_list[0]['info']['expend_profit']}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">代理时间:</label>
            <div class="layui-input-inline">
                <input disabled type="text" name="source_name" value="{{$agent_list[0]['created_at']}}" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">创建时间:</label>
            <div class="layui-input-inline">
                @if($user_info_list)
                <input disabled type="text" name="source_name" value="{{$user_info_list[0]['create_time']}}" lay-verify="required" autocomplete="off" class="layui-input">
                @else
                    <input disabled type="text" name="source_name" value="" lay-verify="required" autocomplete="off" class="layui-input">
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