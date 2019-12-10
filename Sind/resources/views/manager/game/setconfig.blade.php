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
            <label class="layui-form-label">每周售卖时间</label>
            <div class="layui-input-block">
                <?php $weeks = explode(',', $config['weeks']);?>
                <input type="checkbox" name="weeks[0]" value="1" title="周一" {{ in_array(1, $weeks)?'checked':'' }}>
                <input type="checkbox" name="weeks[1]" value="2" title="周二" {{ in_array(2, $weeks)?'checked':'' }}>
                <input type="checkbox" name="weeks[2]" value="3" title="周三" {{ in_array(3, $weeks)?'checked':'' }}>
                <input type="checkbox" name="weeks[3]" value="4" title="周四" {{ in_array(4, $weeks)?'checked':'' }}>
                <input type="checkbox" name="weeks[4]" value="5" title="周五" {{ in_array(5, $weeks)?'checked':'' }}>
                <input type="checkbox" name="weeks[5]" value="6" title="周六" {{ in_array(6, $weeks)?'checked':'' }}>
                <input type="checkbox" name="weeks[6]" value="0" title="周日" {{ in_array(0, $weeks)?'checked':'' }}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">每天开始时间</label>
            <div class="layui-input-inline">
                <input type="text" name="daily_start" readonly value="{{ $config['daily_start'] }}" id="daily_start" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">每天结束时间</label>
            <div class="layui-input-inline">
                <input type="text" name="daily_end" readonly value="{{ $config['daily_end'] }}" id="daily_end" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">每期时长</label>
            <div class="layui-input-inline">
                <input type="text" name="period_time" value="{{ $config['period_time'] }}" lay-verify="required" placeholder="请输入来源名称" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">每期投注时间</label>
            <div class="layui-input-inline">
                <input type="text" name="period_bet_time" value="{{ $config['period_bet_time'] }}" lay-verify="required" placeholder="请输入每期投注时间" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">每期开奖时间</label>
            <div class="layui-input-inline">
                <input type="text" name="period_draw_time" value="{{ $config['period_draw_time'] }}" lay-verify="required" placeholder="请输入每期开奖时间" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">刷新时间</label>
            <div class="layui-input-inline">
                <input type="text" name="refresh_time" value="{{ $config['refresh_time'] }}" lay-verify="required" placeholder="请输入刷新时间" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">时差(秒)</label>
            <div class="layui-input-inline">
                <input type="text" name="delay_time" value="{{ $config['delay_time'] }}" lay-verify="required" placeholder="时差(秒)" autocomplete="off" class="layui-input">
            </div>
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
                url: "{{ route('manager.game.config') }}",
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
    });
</script>

</body>

</html>