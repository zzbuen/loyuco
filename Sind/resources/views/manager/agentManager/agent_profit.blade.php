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
        line-height: 0px;
        display: inline-block;

    }
    .text_div1{
        height: 30px;
        line-height: 0px;
        display: inline-block;
    }
    .text_span{
        display: inline-block ;
    }
    .username_input{
        width: 150px;
        display: inline-block;
    }
</style>

<body>
<div class="layui-fluid main">
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li id="profit" class="layui-this">今日分润</li>
            <li id="profit_back">历史分润</li>
        </ul>
        <form method="post" action="{{url('manager/agent_profit.index')}}" style="margin-top: 25px">
            {{ csrf_field() }}
            <div style="display: inline-block">
                <div>
                    <div class="text_div">用户ID<span class="text_span"></span></div>
                    <input value="{{ Request::input('user_id')?Request::input('user_id'):''}}" name="user_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <div class="text_div">代理ID<span class="text_span"></span></div>
                    <input value="{{ Request::input('agent_user_id')?Request::input('agent_user_id'):''}}" name="agent_user_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <div class="text_div">订单ID<span class="text_span"></span></div>
                    <input value="{{ Request::input('order_sn')?Request::input('order_sn'):''}}" name="order_sn" type="text" class="layui-input username_input">
                </div>
            </div>
            <input type="submit" class="layui-btn" value="查询">
        </form>
            <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>代理</th>
                        <th>代理名</th>
                        <th>用户ID</th>
                        <th>用户名</th>
                        <th>投注金额</th>
                        <th>订单分润比</th>
                        <th>订单分润</th>
                        <th class="{{ Request::get('column')=='created_at'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="created_at">分润时间</th>
                        <th class="{{ Request::get('column')=='login_date'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="login_date">上次登录时间</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profit_list as $item)
                        <tr>
                            <td>{{$item['order_sn']}}</td>
                            <td>{{$item['agent_user_id']}}</td>
                            <td>{{$agent_list[$item['agent_user_id']]['info']['name']}}</td>
                            <td>{{$item['user_id']}}</td>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['order_amount']}}</td>
                            <td>{{$item['profit_percent']}}%</td>
                            <td>{{$item['profit_amount']}}</td>
                            <td>{{$item['created_at']}}</td>
                            <td>{{$item['login_date']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                {{ $profit_list->appends([
                   'gent_user_id' => Request::input('gent_user_id'),
                  'user_id' => Request::input('user_id'),
                  'order_sn' => Request::input('order_sn'),
                  'column' => Request::input('column'),
                   'sort' => Request::input('sort')
         ])->links() }}
        </div>
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

        $('.getUser_detail').click(function () {
            var user_id = $(this).prev().val()
            var url = "{{ url('manager/getUser_detail')}}?user_id="+user_id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'用户详情',
                    shadeClose:true,
                    shade:0,
                    area:['500px','600px'],
                    content:url,
                    skin:'accountOp',
                })
            })
        });
        $("#profit").click(function () {
            location.href = "{{url("/manager/agent_profit.index")}}"
        })
        $("#profit_back").click(function () {
            location.href = "{{url("manager/agent_profit.history")}}"
        })

        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
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
            location.href = '{{ url("manager/agent_profit.index") }}?date_begin={{Request::input('date_begin')}}&date_end={{Request::input('date_end')}}&agent_user_id={{ Request::input("agent_user_id") }}&user_id={{ Request::input("user_id") }}&column='+column+'&sort='+sort;
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
    });
</script>

</body>

</html>