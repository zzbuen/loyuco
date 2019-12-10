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
        <legend>喇叭管理</legend>
    </fieldset>
    {{--<div class="add_div">--}}
        {{--<input id="add_announcement" type="button" style="float: right;margin-bottom: 10px;" class="layui-btn layui-btn" value="新增公告">--}}
    {{--</div>--}}

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
                    <th>内容</th>
                    {{--<th>状态</th>--}}
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 75%;">{{ $list['value'] }}</td>
                    <td style="width: 20%;">
                        <button class="layui-btn config-btn layui-btn-small" config_id="{{ $list['id'] }}">编辑</button>
                    </td>
                </tr>
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
                title: '喇叭編輯',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.horn_page.index") }}?id='+config_id
            });
        });
    });
</script>

</body>

</html>