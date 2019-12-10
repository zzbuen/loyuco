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
    .day_income_user{
        cursor: pointer;
        text-decoration: underline;
    }

</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>用户每日盈亏详情</legend>
    </fieldset>
    <div>
        <button id="go_back" class="layui-btn">返回</button>
    </div>
    <div class="layui-form">
        <table class="layui-table">
            {{--<colgroup>--}}
                {{--<col width="20%">--}}
                {{--<col width="20%">--}}
                {{--<col width="20%">--}}
                {{--<col width="20%">--}}
                {{--<col width="20%">--}}
            {{--</colgroup>--}}
            <thead>
                <tr>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>订单编号</th>
                    <td>下注总额</td>
                    <td>下注数量</td>
                    <th>盈亏</th>
                    <th>时间</th>
                </tr>
            </thead>
        <tbody>
            @foreach($day_money_list as $item)
            <tr>
                <td>{{$item['user_id']}}</td>
                <td>{{$item['info']['name']}}</td>
                <td>{{$item['order_sn']}}</td>
                <td>{{$item['bet_money']}}</td>
                <td>{{$item['bet_num']}}</td>
                @if($item['result']>=0)
                    <td style="color: red">{{$item['result']}}</td>
                @else
                    <td style="color: blue">{{$item['result']}}</td>
                @endif
                <td>{{$item['order_dateTime']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
        {{ $day_money_list->appends(['time'=>Request::input('time'),
            'user_id'=>Request::input('user_id'),
            'column' => Request::input('column'),
            'sort' => Request::input('sort'),
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
            location.href = '{{ url("manager/day_money_detail") }}?time={{Request::input('time')}}&column='+column+'&sort='+sort;
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
        $("#go_back").click(function () {
            window.history.back()
        })

    });
</script>

</body>

</html>