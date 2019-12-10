<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
</head>
<style>
    .text_div{
        height: 30px;
        width: 100px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_div1{
        height: 30px;
        width: 75px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
    .username_input{
        width: 150px;
        display: inline-block;
    }
    .day_money_detail{
        cursor: pointer;
        text-decoration: underline;
    }
    .server_charge_detail{
        cursor: pointer;
        text-decoration: underline;
    }
    .share_profit_detail{
        cursor: pointer;
        text-decoration: underline;
    }
    .share_profit_user{
        cursor: pointer;
    }


</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>每日代理分润</legend>
    </fieldset>
    <form method="post" action="{{ url("manager/share_profit_detail") }}?time={{Request::input('time')}}">
        {{ csrf_field() }}
        <div style="display: inline-block;margin-top: 20px;">
            <div style="display: inline-block">
                <div class="text_div" style="width: 60px;margin-left: 20px">用户ID<span class="text_span"></span></div>
                <div style="display: inline-block">
                    <input value="{{ Request::input('user_id')?Request::input('user_id'):''}}"  type="text" class="layui-input" id="user_id" name="user_id" style="width: 130px">
                </div>
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <a class="layui-btn" href="{{url('manager/stat.income.index')}}">返回</a>
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>时间</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th class="{{ Request::get('column')=='daymoney'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="daymoney">分润</th>
                </tr>
            </thead>
        <tbody>
            @foreach($share_profit as $item)
            <tr class="share_profit_user">
                <td>{{$time}}</td>
                <td><span >{{$item['agent_user_id']}}</span></td>
                <td>{{$item['agentinfo']['name']}}</td>
                <td>{{$item['result']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
        {{ $share_profit->appends(['time'=>Request::input('time'),
            'user_name' => Request::input('user_name'),
            'column' => Request::input('column'),
           'sort' => Request::input('sort'),
           'user_id' => Request::input('user_id')])->links() }}
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
        $(".share_profit_user").click(function () {
            var user_id = $(this).children().eq(1).text();
            var time = '{{$time}}'
            location.href = '{{url('manager/share_profit_user')}}?time='+time+'&user_id='+user_id
        })

        $(".sorting").click(function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            var column = $(this).attr('sort');
            if($(this).hasClass("sorting_desc")){
                var sort = 'asc';
            }else {
                var sort = 'desc';
            }
            location.href = '{{ url("manager/share_profit_detail") }}?time={{Request::input('time')}}&column='+column+'&sort='+sort;
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
    });
</script>

</body>

</html>