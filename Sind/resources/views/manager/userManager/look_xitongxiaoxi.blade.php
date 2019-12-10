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
    <form id="uploadForm" method="post" style="margin-top: 20px;" class="layui-form " action="">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <input type="hidden" name="user_id" value="{{$question_info['user_id']}}">
            <label class="layui-form-label">发送者</label>
            <div class="layui-input-inline">
                <input disabled value="{{$question_info['send_user_id']}}" type="text" name="username" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <input type="hidden" name="user_id" value="{{$question_info['user_id']}}">
            <label class="layui-form-label">接收者</label>
            <div class="layui-input-inline">
                <input disabled value="{{$question_info['user_id']}}" type="text" name="username" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系方式</label>
            <div class="layui-input-inline">
                <input disabled value="{{$question_info['phone']}}" type="text" name="username" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发送时间</label>
            <div class="layui-input-inline">
                <input disabled value="{{$question_info['create_time']}}" type="text" name="create_time" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">消息标题</label>
            <div class="layui-input-block">
                <textarea readonly name="title" placeholder="请输入内容" class="layui-textarea">{{$question_info['title']}}</textarea>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">消息内容</label>
            <div class="layui-input-block">
                <textarea disabled name="content" placeholder="请输入内容" class="layui-textarea">{{$question_info['content']}}</textarea>
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
       $("#submit_btn").click(function () {
           $("#uploadForm").trigger('submit');
       })
        $("#uploadForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ url("manager/replay_qurestion") }}",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if(data.flag){
                        layer.alert(data.msg, {icon: 1,shade:0},function () {
                            parent.location.reload();
                        })
                    } else{
                        layer.alert(data.msg, {icon: 2,shade:0},function () {
                            parent.location.reload();
                        })
                    }
                },
                error: function(){}
            });
        });
    });
</script>

</body>

</html>