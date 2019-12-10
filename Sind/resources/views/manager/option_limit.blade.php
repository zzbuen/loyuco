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
        width: 50px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
</style>

<body>
<div class="layui-fluid main">
    <table class="layui-table table-body">
        <tbody>
        @foreach($bet_list as $bet=>$bet_val)
            <tr style="margin-top: 10px;">
                <td style="color: red">{{$bet_val['category_info']['name']}}</td>
                <td style="display: inline-block;width: 700px;">
                    <ul>
                    @foreach($bet_val['category_info']['rule'] as $rule_key=>$rule_val)
                    <li>
                        <span class="text_div" style="color: blue">{{$rule_val['name']}}<span class="text_span"></span></span>
                        <p style="display: inline-block;margin-left: 10px">
                        @foreach($rule_val['name_info'] as $name_key => $name_val)
                            @if($name_val['limit_id'])
                            @if($name_val['limit_id']!= Request::input('limit_id') )
                            <input checked="checked" disabled value="{{$name_val['id']}}" class="checkbox layui-unselect layui-disabled" type="checkbox"/>
                            <sapn style="color: green">{{$name_val['name']}}</sapn>
                            @else
                            <input checked="checked" value="{{$name_val['id']}}" class="checkbox" type="checkbox"/>
                            <sapn style="color: green">{{$name_val['name']}}</sapn>
                            @endif
                            @else
                            <input value="{{$name_val['id']}}" class="checkbox" type="checkbox"/>
                            <sapn style="color: green">{{$name_val['name']}}</sapn>
                            @endif
                        @endforeach
                        </p>
                    </li>
                    @endforeach
                    </ul>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
                }
            });
            return false;
        });

        if($(".select_game").length>1){
            $($(".select_game")[0]).removeClass('select_game');
        }
        if($(".layui-this").length>1){
            $($(".layui-this")[0]).removeClass('layui-this');
        }
       var game_td_length = $(".game_td").length-1;
       $($(".game_td")[game_td_length]).addClass('last_game_td');
       $(".game_td").click(function () {
           var game_id = $(this).parent().attr('id');
           var game_id = game_id.substr(1);
           location.href = "{{url('manager/limit') }}?game_id="+game_id;
       })

        $(".checkbox").click(function () {
            var limit_id = null;
            var bet_id = $(this).val();
            if($(this).prop('checked')){
                limit_id = "{{Request::input('limit_id')}}"
            } else{
                limit_id = 0;
            }
            var data = {limit_id:limit_id,bet_id:bet_id};
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });

            $.ajax({
                type: "POST",
                url: "/manager/option_limit_ajax",
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    layer.close(index);
                    if(res){
                        layer.msg('配置成功', {time:1100});
                    }
                    else{
                        layer.msg('配置失败', {time:1100});
                    }
                }
            });
        })
    });

</script>

</body>

</html>