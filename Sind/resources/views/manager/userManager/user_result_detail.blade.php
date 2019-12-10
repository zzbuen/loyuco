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
    <link rel="stylesheet" type="text/css" href="/plugins/font-awesome/css/font-awesome.css">
</head>
<style>
    .info-box {
        height: 85px;
        background-color: white;
        background-color: #ecf0f5;
    }

    .info-box .info-box-icon {
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        display: block;
        float: left;
        height: 85px;
        width: 85px;
        text-align: center;
        font-size: 45px;
        line-height: 85px;
        background: rgba(0, 0, 0, 0.2);
    }

    .info-box .info-box-content {
        padding: 5px 10px;
        margin-left: 85px;
    }

    .info-box .info-box-content .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: uppercase;
    }

    .info-box .info-box-content .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }

    .major {
        font-weight: 10px;
        color: #01AAED;
    }

    .main {
        margin-top: 25px;
    }

    .main .layui-row {
        margin: 10px 0;
    }
</style>
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
        <legend>用户盈亏明细</legend>
    </fieldset>
    <form method="post" action="{{url('/manager/user_result_detail')}}?user_id={{Request::input('user_id')}}">
        {{ csrf_field() }}
        <div style="display: inline-block;margin-left: 20px">
            <div>
                <div class="text_div">购买期数<span class="text_span"></span></div>
                <input value="{{ Request::input('bet_period')?Request::input('bet_period'):''}}" name="bet_period" type="text" class="layui-input username_input">
            </div>
        </div>
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
        <input type="submit" class="layui-btn" value="查询">
        <div class="layui-col-md2 layui-col-sm2 layui-col-xs2" style="display: inline-block;float: right">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">盈亏总计</span>
                    <span style="font-size: 18px">{{$result_sum}}</span>
                </div>
            </div>
        </div>
        <div class="layui-col-md2 layui-col-sm2 layui-col-xs2" style="display: inline-block;float: right">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">投注总计</span>
                    <span style="font-size: 18px">{{$order_sum}}</span>
                </div>
            </div>
        </div>
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th class="{{ Request::get('column')=='bet_money'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="bet_money">下注金额</th>
                    <th class="{{ Request::get('column')=='result'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="result">玩家盈亏</th>
                    <th>押注</th>
                    <th>下注数量</th>
                    <th>投注名称</th>
                    <th>期数</th>
                    <th>购买日期</th>
                    <th>操作</th>
                </tr>
            </thead>
        <tbody>
            @foreach($order_list as $item)
            <tr>
                <td>{{$item['order_sn']}}</td>
                <td>{{$item['user_id']}}</td>
                <td>{{$item['info']['name']}}</td>
                <td>{{$item['bet_money']}}</td>
                @if($item['result']>=0)
                    <td style="color: red;">{{$item['result']}}</td>
                @else
                    <td style="color: blue;">{{$item['result']}}</td>
                @endif
                <td>{{$item["show_name"]}}</td>
                <td>{{$item['bet_num']}}</td>
                <td>{{$item['bet_name']}}</td>
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
       'user_id' => Request::input('user_id'),
       'order_sn' => Request::input('order_sn'),
       'date_begin' => Request::input('date_begin'),
       'date_end' => Request::input('date_end'),
        'bet_period' => Request::input('bet_period'),
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
            location.href = '{{ url("manager/user_result_detail") }}?bet_period={{ Request::input("bet_period") }}&date_end={{ Request::input("date_end") }}&date_begin={{ Request::input("date_begin") }}&user_id={{ Request::input("user_id") }}&order_sn={{ Request::input("order_sn") }}&column='+column+'&sort='+sort;
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