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
    tr th{
        text-align: center!important;
    }
    tr td{
        text-align: center!important;
    }
</style>
<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>彩票配置</legend>
    </fieldset>

    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>游戏名称</th>
                    <th>每周售卖时间</th>
                    <th>每天开始时间</th>
                    <th>每天结束时间</th>
                    <th>每期时间</th>
                    <th>每期投注时间</th>
                    <th>每期开奖时间</th>
                    <th>刷新时间</th>
                    <th>封盘时间</th>
                    <th>状态</th>
                    <th style="width: 10%">操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($config_list as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['weeks'] }}</td>
                    <td>{{ $item['start_time'] }}</td>
                    <td>{{ $item['end_time'] }}</td>
                    <td>{{ $item['period_time'] }}</td>
                    <td>{{ $item['period_bet_time'] }}</td>
                    <td>{{ $item['period_draw_time'] }}</td>
                    <td>{{ $item['refresh_time'] }}</td>
                    <td>{{ $item['delay_time'] }}</td>
                    <td>{{ $item['status']==1?'启用':'禁用' }}</td>
                    <td>
                        <button class="layui-btn layui-btn-small config-btn" config_id="{{ $item['id'] }}">配置</button>
                        <button class="layui-btn layui-btn-small {{ $item['status']==1?'layui-btn-danger':'' }} modify-btn" game_id="{{ $item['game_id'] }}">{{ $item['status']==1?'禁用':'启用' }}</button>
                       @if($item['balance_is']==1)
                        <button class="layui-btn layui-btn-small balance" config_id="{{ $item['id'] }}">平衡值</button>
                           @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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

        $('.modify-btn').click(function () {
            var game_id = $(this).attr('game_id');
            layer.confirm('确定调整这个游戏吗？', function () {
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                $.ajax({
                    type: "POST",
                    headers:{
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('manager.game.modify') }}",
                    data: {game_id:game_id},
                    dataType: "json",
                    success: function(res){
                        layer.close(index);
                        layer.msg(res.msg, {time:1100});
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            })
        });

        $('.config-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '配置调整',
                shadeClose: true,
                shade: 0.8,
                area: ['60%', '90%'],
                maxmin: true,
                content: '{{ route("manager.game.config") }}?config_id='+config_id,
            });
        });
        $('.balance').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '平衡值',
                shadeClose: true,
                shade: 0.8,
                area: ['60%', '90%'],
                maxmin: true,
                content: '{{ route("manager.game.balance") }}?config_id='+config_id,
            });
        });
    });
</script>

</body>

</html>