<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>彩票配置设置</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{ $config['name'] }}</legend>
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        {{ csrf_field() }}
        <input type="hidden" name="config_id" value="{{ $config['id'] }}">
        {{--        <div class="layui-form-item">
                    <label class="layui-form-label">来源名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="source_name" value="{{ $config['source_name'] }}" lay-verify="required" placeholder="请输入来源名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">来源URL</label>
                    <div class="layui-input-block">
                        <input type="text" value="{{ $config['source_url'] }}" disabled class="layui-input layui-unselect layui-disabled">
                    </div>
                </div>--}}
        <div class="layui-form-item">
            <label class="layui-form-label ">开始时间</label>
            <div class="layui-input-block">
                <input style="width: 50px;" type="number" name="balance_start" value="{{ $config['balance_start'] }}"  class="layui-input layui-unselect start">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-block">
                <input style="width: 50px;" type="number" name="balance_end" value="{{ $config['balance_end'] }}"  class="layui-input layui-unselect end">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">平衡值</label>
            <div class="layui-input-inline">
                   <input id="bla" type="range" name="balance" value="{{ $config['balance'] }}">
                <span class="bla_v">{{ $config['balance'] }}</span>
            </div>
        </div>
        <div class="layui-form-item">
               备注：<span style="color:red">平衡值越大，杀率越大</span>

           </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">提交</button>
        </div>
    </form>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
                layer = layui.layer,
                $ = layui.jquery,
                laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#daily_start',
            type: 'time'
        });
        laydate.render({
            elem: '#daily_end',
            type: 'time'
        });

        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            console.log(data.field);
            $.ajax({
                type: "POST",
                url: "{{ route('manager.game.balance') }}",
                data: data.field,
                dataType: "json",
                success: function(res){
                    layer.close(index);
                    layer.msg(res.msg, {time:1100});
                    if (res.success) {
                        window.parent.location.reload();
                    }
                }
            });
            return false;
        });
        $("#bla").change(function(){

            $v=$("#bla").val();
            $(".bla_v").html($v);
        })
        $(".start").change(function(){

            $sta=$(".start").val();
            $end=$(".end").val();

            if($sta<0){
                alert("时间不能小于0")
            }

            if($end>24 || $sta>24){
                alert("时间不能大于24")
            }

        })
        $(".end").change(function(){

            $sta=$(".start").val();

            $end=$(".end").val();
            if($sta<0){
                alert("时间不能小于0")
            }

            if($end>24 || $sta>24){
                alert("时间不能大于24")
            }

        })
    });
</script>

</body>

</html>