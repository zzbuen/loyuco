<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>充值</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{$user['username']}} - 充值</legend>
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user['user_id'] }}">
        <div class="layui-form-item">
            <label class="layui-form-label">用户余额</label>
            <div class="layui-input-block">
                <input type="text" name="yuer" value="{{ $user['remaining_money']?$user['remaining_money']:0 }}" readonly lay-verify="required" autocomplete="off" class="layui-input layui-unselect layui-disabled">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">充值类型</label>
            <div class="layui-input-block">
                    <select name="recharge_type" class="recharge_type" id="recharge_type">
                        <option value="1" selected >余额</option>
                        <option value="2" >彩金</option>
                        <option value="3" >彩金2</option>
                    </select>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">充值金额</label>
            <div class="layui-input-block">
                <input type="number" name="recharge" lay-verify="required" placeholder="请输入充值金额" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remarks" style="height: 100px;width: 100%"></textarea>
            </div>
        </div>
        <div style="width: 100%;text-align: center">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">确认充值</button>
        </div>
    </form>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;

        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.ajax({
                type: "POST",
                url: "{{ route('manager.recharge') }}",
                data: data.field,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
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