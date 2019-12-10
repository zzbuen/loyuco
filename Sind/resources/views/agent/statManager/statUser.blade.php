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
    .user_id{
        cursor: pointer;
    }

</style>

<body>
<div class="layui-fluid main">
    <ul class="layui-tab-title">
        <li id="profit" {{ Request::input('history')?'':'class=layui-this' }}>今日盈亏</li>
        <li id="profit_history" {{ Request::input('history')?'class=layui-this':'' }} >历史盈亏</li>
    </ul>
    <form method="post" action="{{url('agent/stat.user.index')}}?&back={{Request::input('back')}}&agent_id={{Request::input('agent_id')}}" style="margin-top: 25px;">
        {{ csrf_field() }}
        <div style="display: inline-block;margin-top: 20px;">
            @if(Request::input('history'))
            <input type="hidden" name="history" value="1">
            <div style="display: inline-block;">
                <div class="text_div" style="margin-left: 20px">日期<span class="text_span"></span></div>
                <div style="display: inline-block">
                    <input value="{{ Request::input('date_begin')?Request::input('date_begin'):substr($date_begin,0,10)}}"  type="text" class="layui-input" id="date_begin" name="date_begin" style="width: 100px">
                </div> —
                <div style="display: inline-block">
                    <input value="{{ Request::input('date_end')?Request::input('date_end'):substr($date_end,0,10)}}" type="text" class="layui-input" id="date_end" name="date_end" style="width: 100px">
                </div>
            </div>
            @endif
            <div style="display: inline-block;margin-left: 50px">
                <div class="text_div">用户ID<span class="text_span"></span></div>
                <input value="{{ Request::input('user_id')?Request::input('user_id'):''}}" name="user_id" type="text" class="layui-input username_input">
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <a href="" class="layui-btn" style="float: right;margin-top: 20px;margin-right: 10px;">刷新</a>
        @if(Request::input('back'))
        <a href="javascript:history.go(-1)" class="layui-btn" style="float: right;margin-top: 20px">返回</a>
        @endif
        <div style="display: inline-block"></div>
        <div class="layui-col-md2" style="margin-top: -0.5%">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">总盈亏</span>
                    <span class="info-box-number">
                        @if($all_income[0]['all_income']>=0)
                            <span style="color: red;">{{number_format($all_income[0]['all_income'], 2)}}</span>
                        @else
                            <span style="color: blue;">{{number_format($all_income[0]['all_income'], 2)}}</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="layui-col-md2" style="margin-top: -0.5%">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:#00a65a !important;color:white;"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">总投注金额</span>
                    <span class="info-box-number">{{ number_format($all_income[0]['bet_money'], 2) }}</span>
                </div>
            </div>
        </div>
        <div class="layui-col-md2" style="margin-top: -0.5%">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">总投注数量</span>
                    <span class="info-box-number">{{ $all_income[0]['bet_num'] }}</span>
                </div>
            </div>
        </div>
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="20%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th class="{{ Request::get('column')=='bet_money'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="bet_money">投注金额</th>
                    <th class="{{ Request::get('column')=='bet_num'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="bet_num">投注数量</th>
                    <th class="{{ Request::get('column')=='result'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="result">盈亏</th>
                </tr>
            </thead>
        <tbody>
            @foreach($user_daily_list as $item)
            <tr class="user_id">
                <td>
                    {{$item['user_id']}}
                </td>
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
        {{ $user_daily_list->appends([
            'user_name' => Request::input('user_name'),
            'column' => Request::input('column'),
            'sort' => Request::input('sort'),
            'date_begin' => Request::input('date_begin'),
            'date_end' => Request::input('date_end'),
            'history' => Request::input('history'),
            'back' => Request::input('back'),
            'agent_id' => Request::input('agent_id'),
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

        $("#profit").click(function () {
            location.href = "{{url("/agent/stat.user.index")}}?back={{Request::input('back')}}&agent_id={{Request::input('agent_id')}}"
        })
        $("#profit_history").click(function () {
            location.href = "{{url("/agent/stat.user.index")}}?history=1&back={{Request::input('back')}}&agent_id={{Request::input('agent_id')}}"
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
            location.href = '{{ url("agent/stat.user.index") }}?date_end={{Request::input('date_end')}}&date_begin={{Request::input('date_begin')}}&column='+column+'&sort='+sort+'&history={{Request::input('history')}}&back={{Request::input('back')}}&agent_id={{Request::input('agent_id')}}';
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif
        @if (Request::input('history'))
        $(".user_id").click(function () {
            var user_id = $(this).children().eq(0).text();
            var date_begin = "{{ Request::input('date_end')?Request::input('date_end'):substr($date_end,0,10)}}"
            var date_end = "{{ Request::input('date_end')?Request::input('date_end'):substr($date_end,0,10)}}"
            location.href ='{{ url("agent/stat.user_detail") }}?user_id='+user_id+'&date_end={{ Request::input('date_end')?Request::input('date_end'):substr($date_end,0,10)}}&date_begin={{ Request::input('date_begin')?Request::input('date_begin'):substr($date_end,0,10)}}&history=1';
        })
        @else
        $(".user_id").click(function () {
            var user_id = $(this).children().eq(0).text();
            location.href ='{{ url("agent/getOrder") }}?back=1&user_id='+user_id;
        })
        @endif
    });
</script>

</body>

</html>