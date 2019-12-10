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
    }

</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>用户盈亏详情</legend>
    </fieldset>
    <a href="{{url('agent/stat.user.index')}}" class="layui-btn">返回</a>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col>
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th>时间</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th class="{{ Request::get('column')=='bet_money'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="bet_money">金额</th>
                    <th class="{{ Request::get('column')=='bet_num'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="bet_num">投注数量</th>
                    <th class="{{ Request::get('column')=='result'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="result">盈亏</th>
                </tr>
            </thead>
        <tbody>
            @foreach($day_money_list as $item)
            <tr class="day_income_user">
                <input type="hidden" value="{{$item['date']}}">
                <input type="hidden" value="{{$item['user_id']}}">
                <td>{{$item['date']}}</td>
                <td>{{$item['user_id']}}</td>
                <td>{{$item['info']['name']}}</td>
                <td>{{$item['bet_money']}}</td>
                <td>{{$item['bet_num']}}</td>
                @if($item['result']>=0)
                    <td style="color: red;">{{$item['result']}}</td>
                @else
                    <td style="color: blue;">{{$item['result']}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
        {{ $day_money_list->appends([
           'date_end'=>Request::input('date_end'),
           'date_begin'=>Request::input('date_begin'),
           'user_id' => Request::input('user_id'),
           'column' => Request::input('column'),
           'sort' => Request::input('sort'),])->links() }}
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;

        $(".day_income_user").click(function () {
            var user_id = $(this).children().eq(1).val();
            var time = $(this).children().eq(0).val();
            location.href = '{{url('agent/day_income_stat')}}?time='+time+'&user_id='+user_id
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
            location.href = '{{ url("agent/stat.user_detail") }}?user_id={{Request::input('user_id')}}&date_end={{$date_end}}&date_begin={{$date_begin}}&column='+column+'&sort='+sort;
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
    });
</script>

</body>

</html>