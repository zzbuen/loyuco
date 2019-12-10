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
    tr td,th{
        text-align: center!important;
    }
</style>
<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>公告管理</legend>
    </fieldset>
    <div class="add_div">
        <input id="add_announcement" type="button" style="float: right;margin-bottom: 10px;" class="layui-btn layui-btn" value="新增公告">
    </div>

    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="500">
                <col>
                <col width="200">
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th>标题</th>
                    <th>发布时间</th>
                    <th>内容</th>
                    {{--<th>状态</th>--}}
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($announcement_list as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['show_time'] }}</td>
                    <td>{{ $item['content'] }}</td>
                    <td>
                        <button class="layui-btn config-btn layui-btn-small" config_id="{{ $item['id'] }}">编辑</button>
                        <button class="layui-btn layui-btn-danger layui-btn-small destroy-btn" config_id="{{ $item['id'] }}">删除</button>
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

        $('.config-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '公告编辑',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.announcement.edit") }}?id='+config_id,
            });
        });

        $('#add_announcement').click(function () {
            layer.open({
                type: 2,
                title: '新增公告',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.announcement.add") }}',
            });
        });

        $('.destroy-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.confirm('确定删除吗？', function () {
                $.ajax({
                    type: "POST",
                    url: "{{ route('manager.announcement.destroy') }}",
                    data: {id:config_id},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(res){
                        layer.msg(res.msg, {time:1100});
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            });
        });
    });
</script>

</body>

</html>