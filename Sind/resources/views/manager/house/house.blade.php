<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>房间设置</title>
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
        <legend>房间设置</legend>
    </fieldset>
    {{--<div class="add_div">--}}
        {{--<input id="add_announcement" type="button" style="float: right;margin-bottom: 10px;" class="layui-btn layui-btn" value="新增公告">--}}
    {{--</div>--}}

    <div class="layui-form">
        <table class="layui-table">
            {{--<colgroup>--}}
                {{--<col width="150">--}}
                {{--<col width="500">--}}
                {{--<col>--}}
                {{--<col width="200">--}}
                {{--<col width="200">--}}
            {{--</colgroup>--}}
            <thead>
                <tr>
                    <th>彩票名称</th>
                    <th>房间名称</th>
                    <th>进入房间条件</th>
                    <th>是否加密</th>
                    <th>状态</th>
                    <th>最高投注</th>
                    <th>最低投注</th>
                    {{--<th>房间说明</th>--}}
                    <th>总注封顶</th>
                    <th>大小单双</th>
                    <th>极值</th>
                    <th>数字</th>
                    <th>组合</th>
                    <th>红绿蓝</th>
                    <th>豹子</th>
                    <th>龙虎和</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($list as $item)
                <tr>
                    <td>
                        @if($item['type']==1)
                            重庆时时彩
                        @elseif($item['type']==2)
                            北京PK10
                        @elseif($item['type']==3)
                            幸运飞艇
                        @elseif($item['type']==4)
                            蚂蚁庄园
                        @elseif($item['type']==5)
                            蚂蚁森林
                        @elseif($item['type']==6)
                            QQ分分彩
                        @elseif($item['type']==7)
                            极速PK10
                        @elseif($item['type']==8)
                            极速时时彩
                        @endif
                    </td>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['conditionmin'] }}</td>
                    <td>
                        {{$item['is_private']?'密码:'.$item['cipher']:''}}
                    </td>
                    <td>
                        @if($item['status']==0)
                        正常
                        @elseif($item['status']==1)
                        关闭
                        @endif
                    </td>
                    <td>{{ $item['maxBet'] }}</td>
                    <td>{{ $item['minBet'] }}</td>
                    <th>{{$item['sum_bet']}}</th>
                    <th>{{$item['sum_dxds']}}</th>
                    <th>{{$item['sum_jz']}}</th>
                    <th>{{$item['sum_sz']}}</th>
                    <th>{{$item['sum_zh']}}</th>
                    <th>{{$item['sum_hll']}}</th>
                    <th>{{$item['sun_bz']}}</th>
                    <th>{{$item['sun_lhh']}}</th>
                    {{--<td>{{ $item['info'] }}</td>--}}
                    <td>
                        <button class="layui-btn config-btn layui-btn-small" config_id="{{ $item['id'] }}">编辑</button>
                        @if($item['type']==4||$item['type']==5)
                        <button class="layui-btn  layui-btn-small xe_set" config_id="{{ $item['id'] }}">限额设定</button>
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

        $('.config-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '公告编辑',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.house.edit") }}?id='+config_id
            });
        });
        $('.xe_set').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '限额设定',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.house.xe_set") }}?id='+config_id
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