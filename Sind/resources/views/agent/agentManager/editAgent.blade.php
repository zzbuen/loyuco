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
    .show_res
    {
        color: red;
        width: 100%;
        text-align: center;
        margin-top: 20px;
        font-size: 20px;
    }
</style>

<body>
<div  class="layui-everyday-list" id="lay_body">
    <div class="layui-fluid main">
        <form style="margin-top: 20px;" class="layui-form layui-form-pane" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">代理ID</label>
                <div class="layui-input-inline">
                    <input disabled type="text" name="source_name" value="{{$agent_list[0]['user_id']}}" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">代理分润比</label>
                <div class="layui-input-inline">
                    <input type="text" id="share" name="share" value="{{$agent_list[0]['share_percent']}}" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
        </form>
        <div style="width: 100%;text-align: center">
            <input type="button" class="layui-btn" value="确认修改" id="modifyAgent_ajax" value="确认修改">
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
        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.ajax({
                type: "POST",
                url: "/agent/secure",
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
        $("#modifyAgent_ajax").click(function () {
            var user_id = "{{Request::input('user_id')}}";
            var share = $("#share").val();
            var data = {user_id:user_id,share:share};

            $.ajax({
                type: "POST",
                url: "/agent/modifyAgent_ajax",
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.flag) {
                        layer.alert(res.msg, {icon: 1},function(index){
                            window.parent.location.reload();
                        });
                    }
                    else{
                        layer.alert(res.msg, {icon: 2},function(index){
                            window.parent.location.reload();
                        });
                    }
                }
            });
        })
    });

</script>

</body>

</html>