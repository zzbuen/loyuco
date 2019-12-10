<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>彩票注数配置</title>
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

        height: 750px;
        width: 91%;
        margin-left: 140px;

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
        <legend>特殊赔率设置</legend>
    </fieldset>

    <div class="layui-form">
        <table class="layui-table">
            <thead>
            <tr>
                <th>房名</th>
                <th>遇13/14</th>
                <th>遇豹子顺子对子</th>
                <th>赔率</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($list as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>
                        @if($item['value1'])
                            <button class="layui-btn layui-btn-small mod_close1 layui-btn-danger" data_id="{{ $item['room_type'] }}">关闭</button>
                        @else
                            <button class="layui-btn layui-btn-small mod_open1" data_id="{{ $item['room_type'] }}">开启</button>
                        @endif
                    </td>
                    <td>
                        @if($item['value2'])
                            <button class="layui-btn layui-btn-small mod_close2 layui-btn-danger" data_id="{{ $item['room_type']}}">关闭</button>
                        @else
                            <button class="layui-btn layui-btn-small mod_open2" data_id="{{ $item['room_type'] }}">开启</button>
                        @endif
                    </td>
                    <td>{{ $item['value3'] }}</td>
                    <td>
                        <button class="layui-btn layui-btn-small mod_pl" data_id="{{ $item['room_type'] }}">修改赔率</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<script src="/js/jquery-1.9.1.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;


        /**
         * 作用：删除玩法
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".delete_limit",function () {
            var limit_id    = $(this).prev().val();
            var ruleName    = $(this).parent().parent().children().eq(0).html();
            layer.confirm("确定删除【"+ruleName+"】该玩法吗？",function () {
                $.ajax({
                    type:"post",
                    url:"{{ url('manager/del_limit_ajax')}}",
                    data:{"limit_id":limit_id},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success:function (res) {
                        layer.alert(res.msg);
                        if(res.code){
                            location.reload();
                        }
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试");
                    }
                })
            });
        })

    });

    $('.mod_open1').click(function () {
        var id = $(this).attr('data_id');
        $.ajax({
            type: "POST",
            url:"{{ url('manager/manager.limit_mod_open1')}}",
            data: {id:id},
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

    $('.mod_close1').click(function () {
        var id = $(this).attr('data_id');
        $.ajax({
            type: "POST",
            url:"{{ url('manager/manager.limit_mod_close1')}}",
            data: {id:id},
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

    $('.mod_open2').click(function () {

        var id = $(this).attr('data_id');
        $.ajax({
            type: "POST",
            url:"{{ url('manager/manager.limit_mod_open2')}}",
            data: {id:id},
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

    $('.mod_close2').click(function () {
        var id = $(this).attr('data_id');
        $.ajax({
            type: "POST",
            url:"{{ url('manager/manager.limit_mod_close2')}}",
            data: {id:id},
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

    $('.mod_pl').click(function(){
        var id = $(this).attr('data_id');
        layer.prompt({title: '请输入您要修改的赔率值', formType: 2}, function(pass, index){
            layer.close(index);
            $.ajax({
                type: "POST",
                url: "{{ url('manager/limit.mod_value')}}",
                data: {id:id,pass:pass},
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
    })
</script>

</body>

</html>