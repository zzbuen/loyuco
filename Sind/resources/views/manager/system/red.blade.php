<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>彩金设置</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>
<style>
    tr td,th{
        border: none!important;
        border-bottom: 1px solid #f2f2f2!important;
        text-align: center!important;
    }
</style>
<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>彩金设置</legend>
    </fieldset>

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
                    <th style="width: 200px">配置项目</th>
                    <th>配置值</th>
                    {{--<th style="width: 50px">是否对外显示</th>--}}
                    {{--<th style="width: 50px">是否启用</th>--}}
                </tr>
            </thead>
            <tbody>
            @foreach($list as $item)
                <tr>
                    <td>每日彩金领取金额:</td>
                    <td>每日充值达到
                        <input type="text" class= "a{{$item['id']}}" style="height: 25px;width:70px " value="{{ $item['right_num']}}">
                        元,
                        可领取彩金为:今日充值金额的
                        <input type="text" class= "b{{$item['id']}}" style="height: 25px;width:70px " value="{{ $item['red_money']}}">
                        %
                        <button class="layui-btn config-btn layui-btn-small" config_id="{{ $item['id'] }}" style="position: relative;top: -2px;margin-left: 20px">保存</button>
                    </td>
                    {{--<td>--}}
                        {{--<button class="layui-btn layui-btn-small" config_id="{{ $item['id'] }}">是</button>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<button class="layui-btn layui-btn-small" config_id="{{ $item['id'] }}">是</button>--}}
                    {{--</td>--}}
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
            var value1 = $('.a'+config_id).val();
            var value2 = $('.b'+config_id).val();
            layer.confirm('确定保存吗？', function () {
                $.ajax({
                    type: "POST",
                    url: "{{ route('manager.red.result')}}",
                    data: {id:config_id,value1:value1,value2:value2},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(res){
                        if (res.success) {
                            layer.msg(res.msg, {time:1100},function(){
                                location.reload();
                            });

                        }else{
                            layer.msg(res.msg, {time:1100});
                        }
                    }
                });
            });
        });
    });
</script>

</body>

</html>