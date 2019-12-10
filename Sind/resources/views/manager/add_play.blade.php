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
</style>

<body>
<div class="layui-everyday-list">
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">游戏<span class="text_span"></span></div>
        <select id="all_game" style="height: 30px;width: 175px;">
            @foreach($game_list as $item)
                <option value="{{$item['id']}}" {{ Request::input('game_id')==$item['id']?'selected':'' }}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">玩法<span class="text_span"></span></div>
        <input style="height: 30px" type="text"  id="play_name" style="width: 130px;" />
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">单注最低<span class="text_span"></span></div>
        <input style="height: 30px" type="text" id="single_lowest" style="width: 130px;" />
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">单注最高<span class="text_span"></span></div>
        <input style="height: 30px" type="text" id="single_limit" style="width: 130px;" />
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">单期最高<span class="text_span"></span></div>
        <input style="height: 30px" type="text" id="current_limit" style="width: 130px;"/>
    </div>
    <button id="add_limit_ajax" class="layui-btn layui-btn" style="margin-top: 20px;margin-left: 155px">确认添加</button>
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

        $("#add_limit_ajax").click(function () {
            var game_id = $("#all_game").val();
            var play_name = $("#play_name").val()
            var single_lowest = $("#single_lowest").val();
            var single_limit = $("#single_limit").val();
            var current_limit = $("#current_limit").val();

            var data = {game_id:game_id,play_name:play_name,single_lowest:single_lowest,single_limit:single_limit,current_limit:current_limit};
            $.ajax({
                type: "POST",
                url: "/manager/add_limit_ajax",
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.flag) {
                        layer.alert(res.content, {icon: 1},function(index){
                            window.parent.location.reload();
                        });
                    }
                    else{
                        layer.alert(res.content, {icon: 2},function(index){
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