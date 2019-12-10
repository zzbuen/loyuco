<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>编辑房间信息</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑各玩法投注限额</legend>
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $list['id'] }}">
        <div class="layui-form-item">
            <label class="layui-form-label">总注封顶</label>
            <div class="layui-input-block">
                <input type="text" name="sum_bet" value="{{ $list['sum_bet'] }}" lay-verify="required" placeholder="输入总注封顶" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">大小单双</label>
            <div class="layui-input-block">
                <input type="text" name="sum_dxds" value="{{ $list['sum_dxds'] }}" lay-verify="required" placeholder="输入大小单双" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">极值</label>
            <div class="layui-input-block">
                <input type="text" name="sum_jz" value="{{ $list['sum_jz'] }}" lay-verify="required" placeholder="输入极值" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">数字</label>
            <div class="layui-input-block">
                <input type="text" name="sum_sz" value="{{ $list['sum_sz'] }}" lay-verify="required" placeholder="输入数字" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">组合</label>
            <div class="layui-input-block">
                <input type="text" name="sum_zh" value="{{ $list['sum_zh'] }}" lay-verify="required" placeholder="输入组合" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">红绿蓝</label>
            <div class="layui-input-block">
                <input type="text" name="sum_hll" value="{{ $list['sum_hll'] }}" lay-verify="required" placeholder="红绿蓝" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">豹子</label>
            <div class="layui-input-block">
                <input type="text" name="sun_bz" value="{{ $list['sun_bz'] }}" lay-verify="required" placeholder="输入豹子" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">龙虎和</label>
            <div class="layui-input-block">
                <input type="text" name="sun_lhh" value="{{ $list['sun_lhh'] }}" lay-verify="required" placeholder="输入龙虎和" autocomplete="off" class="layui-input">
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

        //日期时间选择器
        laydate.render({
            elem: '#test5'
            ,type: 'datetime'
        });
        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            console.log(data.field);
            $.ajax({
                type: "POST",
                url: "{{ route('manager.house.xe_set') }}",
                data: data.field,
                dataType: "json",
                success: function(res){
                    layer.close(index);
                    layer.msg(res.msg, {time:1100});
                    if (res.success) {
                        parent.location.reload();
                    }
                }
            });
            return false;
        });

    });
</script>

</body>

</html>