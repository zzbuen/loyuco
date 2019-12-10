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
        <input style="height: 30px" type="text" disabled="disabled"  style="width: 130px;" value='{{$limit_list['gameName']}}'/>
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">类别<span class="text_span"></span></div>
        <input style="height: 30px" type="text" disabled="disabled"  style="width: 130px;" value='{{$limit_list['category']}}'/>
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">玩法<span class="text_span"></span></div>
        <input style="height: 30px" type="text"  disabled="disabled" id="play_name" style="width: 130px;" value='{{$limit_list['ruleName']}}'/>
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">单期最低注<span class="text_span"></span></div>
        <input style="height: 30px" type="number" id="single_lowest" style="width: 130px;" value='{{$limit_list['single_lowest']}}'/>
    </div>
    <div class="form-element element-name" style="margin-top: 30px">
        <div class="text_div">单期最高注<span class="text_span"></span></div>
        <input style="height: 30px" type="number" id="single_limit" style="width: 130px;" value='{{$limit_list['single_limit']}}'/>
    </div>
    {{--<div class="form-element element-name" style="margin-top: 30px">--}}
        {{--<div class="text_div">单期最高<span class="text_span"></span></div>--}}
        {{--<input style="height: 30px" type="number" id="current_limit" style="width: 130px;" value='{{$limit_list['current_limit']}}'/>--}}
    {{--</div>--}}
    <button id="mondify_ajax" class="layui-btn layui-btn-normal" style="margin-top: 20px;margin-left: 155px">确认修改</button>
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


        /**
         * 作用：修改限额
         * 作者：信
         * 时间：2018/03/20
         * 修改：暂无
         */
        $("#mondify_ajax").click(function () {
            var single_lowest = $("#single_lowest").val()*1;
            var single_limit = $("#single_limit").val()*1;
            /*var current_limit = $("#current_limit").val()*1;*/

            if(single_lowest<=0 || single_lowest>9999999999){
                layer.msg("单注最低不能小于0，最高不可超过10位数",{icon:2});
                return false;
            }

            if(single_lowest > single_limit){
                layer.msg("单注最低不能大于单注最高",{icon:2});
                return false;
            }

            if(single_limit<=0 || single_limit>9999999999){
                layer.msg("单注最低不能小于0，最高不可超过10位数",{icon:2});
                return false;
            }

            var limit_id = "{{$limit_id}}";
            var data = {limit_id :limit_id,single_lowest:single_lowest,single_limit:single_limit};
            $.ajax({
                type: "POST",
                url: "/manager/mondify_limit_ajax",
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res==1) {
                        layer.alert('修改成功', function(index){
                            window.parent.location.reload();
                        });
                    }
                    else{
                        layer.alert('修改失败', function(index){
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