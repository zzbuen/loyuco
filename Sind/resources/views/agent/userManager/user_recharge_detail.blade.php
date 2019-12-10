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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>用户充值明细</legend>
    </fieldset>
    <form method="post" action="{{url('/agent/user_recharge_detail')}}?user_id={{Request::input('user_id')}}">
        {{ csrf_field() }}
        <div style="display: inline-block;margin-top: 20px;">
            <div>
                <div class="text_div" style="width: 60px;margin-left: 20px">日期<span class="text_span"></span></div>
                <div style="display: inline-block">
                    <input value="{{ Request::input('date_begin')?Request::input('date_begin'):''}}"  type="text" class="layui-input" id="date_begin" name="date_begin" style="width: 100px">
                </div> —
                <div style="display: inline-block">
                    <input value="{{ Request::input('date_end')?Request::input('date_end'):''}}" type="text" class="layui-input" id="date_end" name="date_end" style="width: 100px">
                </div>
            </div>
        </div>
        <div style="display: inline-block;margin-top: 20px;">
            <select name="trade_state" id="" class="layui-select">
                <option value="0">全部</option>
                <option value="1" @if(Request::input('trade_state')==1) selected @endif>处理中</option>
                <option value="2" @if(Request::input('trade_state')==2) selected @endif>已处理</option>
                <option value="3" @if(Request::input('trade_state')==3) selected @endif>异常</option>
                <option value="4" @if(Request::input('trade_state')==4) selected @endif>取消</option>
            </select>
        </div>
        <input type="submit" class="layui-btn" value="查询">
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>充值单号</th>
                    <th>用户账号</th>
                    <th>用户名</th>
                    <th class="{{ Request::get('column')=='trade_amount'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="trade_amount">提现金额</th>
                    <th>手续费</th>
                    <th>状态</th>
                    <th>描述</th>
                    <th class="{{ Request::get('column')=='trade_time'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="trade_time">交易发起时间</th>
                </tr>
            </thead>
        <tbody>
            @foreach($withdraw_list as $item)
            <tr>
                <td>{{$item['trade_sn']}}</td>
                <td>{{$item['user']['username']}}</td>
                <td>{{$item['info']['name']}}</td>
                <td>{{$item['trade_amount']}}</td>
                <td>{{$item['service_money']}}</td>
                @if($item['trade_state']==0)
                    <td>默认</td>
                @elseif($item['trade_state']==1)
                    <td>处理中</td>
                @elseif($item['trade_state']==2)
                    <td>已处理</td>
                @elseif($item['trade_state']==3)
                    <td>异常</td>
                @else
                    <td>取消</td>
                @endif
                <td>{{$item['trade_describe']}}</td>
                <td>{{$item['trade_time']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
        {{ $withdraw_list->appends([
       'user_id' => Request::input('user_id'),
       'date_begin' => Request::input('date_begin'),
       'date_end' => Request::input('date_end'),
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
            location.href = '{{ url("manager/user_withdraw_detail") }}?date_end={{ Request::input("date_end") }}&date_begin={{ Request::input("date_begin") }}&user_id={{ Request::input("user_id") }}&column='+column+'&sort='+sort;
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
        $(".order_detail").click(function () {
            var order_sn = $(this).prev().val();
            layer.open({
                type: 2,
                title: '订单详情',
                shadeClose: true,
                shade: 0,
                area: ['100%', '100%'],
                maxmin: true,
                content: '{{ url("manager/getOrder_detail") }}?order_sn='+order_sn,
            });
        })
    });
</script>

</body>

</html>