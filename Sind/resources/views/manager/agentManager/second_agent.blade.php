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
    .agent_children,.group_settle{
        color:#009688;
        text-decoration:underline;
    }
    .total_detail{
        color:#009688;
        text-decoration:underline;
    }
    .agent_children:hover{
        text-decoration:underline;
        cursor: pointer;
    }
    .total_detail:hover{
        text-decoration:underline;
        cursor: pointer;
    }
    .agent_detail,.group_settle{
        text-decoration: underline;
        cursor: pointer;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>{{Request::input('agent_user_id')}}的二级代理列表</legend>
    </fieldset>
    <form method="post" action="{{ url("manager/second_agent") }}?agent_id={{Request::input('agent_id')}}">
        {{ csrf_field() }}
        <div style="display: inline-block">
            <div>
                <div class="text_div">用户ID<span class="text_span"></span></div>
                <input value="{{ Request::input('user_id')?Request::input('user_id'):''}}" name="user_id" type="text" class="layui-input username_input">
            </div>
        </div>
        <div style="display: inline-block;margin-left: 20px">
            <div>
                <div class="text_div">用户账号<span class="text_span"></span></div>
                <input value="{{ Request::input('username')?Request::input('username'):''}}" name="username" type="text" class="layui-input username_input">
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <a href="{{url('manager/agentCenter.index')}}" class="layui-btn">返回一级代理列表</a>
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>用户ID</th>
                    <th>用户账号</th>
                    <th class="{{ Request::get('column')=='totle_profit'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="totle_profit">总收入</th>
                    <th class="{{ Request::get('column')=='share_percent'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="share_percent">分润比</th>
                    <th class="{{ Request::get('column')=='valid_profit'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="valid_profit">可提现金额</th>
                    <th class="{{ Request::get('column')=='children_user'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="children_user">团队人数</th>
                    <th>团队盈亏</th>
                    <th class="{{ Request::get('column')=='created_at'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="created_at">代理时间</th>
                    <th>注册时间</th>
                    <th>邀请码</th>
                </tr>
            </thead>
        <tbody>
            @foreach($agent_list as $item)
            <tr>
                <input type="hidden" value="{{$item['user_id']}}">
            <td>{{$item['user_id']}}</td>
            <td><a class="agent_detail">{{$item['username']}}</a></td>
            <td>
                @if($item['totle_profit'])
                <a class="total_detail">{{$item['totle_profit']}}</a>
                @else
                <a>{{$item['totle_profit']}}</a>
                @endif
            </td>
            <td>{{$item['share_percent']}}</td>
            <td>{{$item['valid_profit']}}</td>
            <td>
                @if($item['children_user'])
                <a class="agent_children">{{$item['children_user']}}</a>
                @else
                <a>{{$item['children_user']}}</a>
                @endif
            </td>
            <td><a class="group_settle">{{number_format($item['group_settle'], 2)}}</a></td>
            <td>{{$item['created_at']}}</td>
            <td>{{$item['re_time']}}</td>
            <td>{{$item['invitation_num']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
        {{ $agent_list->appends([
          'user_id' => Request::input('user_id'),
          'username' => Request::input('username'),
          'column' => Request::input('column'),
           'sort' => Request::input('sort')
         ])->links() }}
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

        $(".group_settle").click(function () {
            var agent_id= $(this).parent().parent().children().eq(0).val();
            location.href = '{{ url("manager/stat.user.index") }}?agent_id='+agent_id+'&back=1';
        })

        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_begin' //指定元素
            });
        });
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_end' //指定元素
            });
        });
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
            location.href = '{{ url("manager/second_agent") }}?agent_id={{Request::input("agent_id")}}&user_id={{ Request::input("user_id") }}&username={{ Request::input("username") }}&column='+column+'&sort='+sort;
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
        $(".agent_children").click(function () {
            var leader_id = $(this).parent().parent().children().eq(0).val();
            location.href = "{{ url("manager/agent_children") }}?leader_id="+leader_id;
        })
        $(".total_detail").click(function () {
            var leader_id = $(this).parent().parent().children().eq(0).val();
            location.href = "{{ url("manager/profit_detail") }}?leader_id="+leader_id;
        })
        $(".agent_detail").click(function () {
            var agent_id= $(this).parent().parent().children().eq(0).val();
            layer.open({
                type: 2,
                title: '代理详情',
                shadeClose: false,
                shade: 0,
                area: ['40%', '70%'],
                maxmin: true,
                content: '{{ url("manager/getAgent_detail") }}?agent_id='+agent_id,
            });
        })
    });
</script>

</body>

</html>