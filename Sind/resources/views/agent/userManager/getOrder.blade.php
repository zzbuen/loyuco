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
        text-align: justify;

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
            <li id="order"  class="layui-this">今日投注</li>
            <li id="order_back" >历史投注</li>
            @if(Request::input('back'))
                <li id="backurl" ><span class="layui-btn layui-btn-small">返回</span></li>
            @endif
        </ul>
        <form method="post" action="{{url('/agent/getOrder')}}" style="margin-top: 25px;">
            {{ csrf_field() }}
            <div style="display: inline-block">
                <div>
                    <div class="text_div">订单ID<span class="text_span"></span></div>
                    <input value="{{ Request::input('order_id')?Request::input('order_id'):''}}" name="order_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block">
                <div>
                    <div class="text_div">订单编号<span class="text_span"></span></div>
                    <input value="{{ Request::input('order_sn')?Request::input('order_sn'):''}}" name="order_sn" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 20px">
                <div>
                    <div class="text_div">用户ID<span class="text_span"></span></div>
                    <input value="{{ Request::input('user_id')?Request::input('user_id'):''}}" name="user_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 20px">
                <div>
                    <div class="text_div">购买期数<span class="text_span"></span></div>
                    <input value="{{ Request::input('bet_period')?Request::input('bet_period'):''}}" name="bet_period" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <span>游戏</span>
                    <select class="layui-select" name="game_id" id="game_id" style="width: 150px">
                        <option value="">全部</option>
                        @foreach($game_list as $item)
                            <option @if($item['id']==Request::input('game_id')) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="submit" class="layui-btn" value="查询">
            <a href="" class="layui-btn" style="float: right">刷新</a>
        </form>
        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>订单ID</th>
                        <th>订单编号</th>
                        <th>用户ID</th>
                        <th>用户名</th>
                        <th>游戏</th>
                        <th class="{{ Request::get('column')=='bet_money'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="bet_money">下注金额</th>
                        <th>下注数量</th>
                        <th class="{{ Request::get('column')=='result'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="result">玩家盈亏</th>
                        <th>押注</th>
                        <th>期数</th>
                        <th>购买日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($order_list as $item)
                <tr>
                    <td>{{$item['id']}}</td>
                    <td>{{$item['order_sn']}}</td>
                    <td>{{$item['user_id']}}</td>
                    <td>{{$item['info']['name']}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['bet_money']}}</td>
                    <td>{{$item['bet_num']}}</td>
                    @if($item['result']>=0)
                        <td style="color: red;">{{$item['result']}}</td>
                    @else
                        <td style="color: blue;">{{$item['result']}}</td>
                    @endif
                    <td>{{$item['show_name']}}</td>
                    <td>{{$item['bet_period']}}</td>
                    <td>{{$item['order_dateTime']}}</td>
                    <td>
                        <input type="hidden" value="{{$item['order_sn']}}">
                        <a class="layui-btn order_detail">详情</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
            {{ $order_list->appends([
            'order_id' => Request::input('order_id'),
            'game_id' =>  Request::input('game_id'),
           'user_id' => Request::input('user_id'),
           'order_sn' => Request::input('order_sn'),
           'date_begin' => Request::input('date_begin'),
           'date_end' => Request::input('date_end'),
            'bet_period' => Request::input('bet_period'),
           'column' => Request::input('column'),
           'back' => Request::input('back'),
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
        $("#order").click(function () {
            location.href = "{{url("/agent/getOrder")}}?user_id={{Request::input('user_id')}}&back={{Request::input('back')}}"
        })
        $("#order_back").click(function () {
            location.href = "{{url("/agent/getOrder_back")}}?user_id={{Request::input('user_id')}}&back={{Request::input('back')}}"
        })
        $("#backurl").click(function () {
            history.go(-1);
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
            location.href = '{{ url("agent/getOrder.index") }}?order_id={{Request::input('order_id')}}&game_id={{Request::input('game_id')}}&bet_period={{ Request::input("bet_period") }}&date_end={{ Request::input("date_end") }}&date_begin={{ Request::input("date_begin") }}&user_id={{ Request::input("user_id") }}&order_sn={{ Request::input("order_sn") }}&column='+column+'&sort='+sort;
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
                area: ['720px', '390px'],
                maxmin: true,
                content: '{{ url("agent/getOrder_detail") }}?order_sn='+order_sn,
            });
        })
    });
</script>

</body>

</html>