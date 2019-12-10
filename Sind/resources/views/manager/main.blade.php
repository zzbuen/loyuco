<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="./plugins/layui/css/layui.css" media="all">
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
        .layui-table tr th{
            text-align: center;
        }
        .layui-table tr td{
            border: none;
            text-align: center;
        }

        #showul ul li{
            float: left;
            width: 15%;
            height: 3.5rem;
            margin-top: 5px;
            margin-left: 5px;
            line-height: 3.5rem;
            padding-left: 1rem;
        }
        .all{
            height: 50px;
            line-height: 50px;
            font-size: 18px;
            color:white;
            background:rgb(36,197,253)
        }
    </style>
</head>

<body>
<div class="layui-fluid main">
    {{--<ul class="layui-nav" style="background: RGB(0,157,217)">--}}
        {{--<li class="layui-nav-item">--}}
            {{--<a style="color: white;font-size: 1.3rem">盈亏统计</a>--}}
        {{--</li>--}}
    {{--</ul>--}}
    <blockquote class="layui-elem-quote all" >
        盈亏统计
    </blockquote>

    <div class="layui-form">
        <table class="layui-table">
            {{--<colgroup>--}}
                {{--<col width="150">--}}
                {{--<col width="150">--}}
                {{--<col width="200">--}}
                {{--<col>--}}
            {{--</colgroup>--}}
            <thead>
            <tr>
                <th>今日统计</th>
                <th>昨日统计</th>
                <th>累计统计</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td  >盈余：{{number_format($today_data["yinyu"],2)}}</td>
                <td  >盈余：{{number_format($yseterday_data["yinyu"],2)}}</td>
                <td  >盈余：{{number_format($all_data["yinyu"],2)}}</td>

            </tr>
            <tr>
                <td>投注额：{{number_format($today_data["betting"],2)}}</td>
                <td>投注额：{{number_format($yseterday_data["betting"],2)}}</td>
                <td>投注额：{{number_format($all_data["betting"],2)}}</td>
            </tr>
            <tr>
                <td>充值：{{number_format($today_data["recharge"],2)}}</td>
                <td>充值：{{number_format($yseterday_data["recharge"],2)}}</td>
                <td>充值：{{number_format($all_data["recharge"],2)}}</td>
            </tr>
            </tbody>
        </table>
    </div>



    {{--用户统计--}}
    {{--<ul class="layui-nav" style="background: RGB(0,157,217);">--}}
        {{--<li class="layui-nav-item">--}}
            {{--<a style="color: white;font-size: 1.3rem">用户统计</a>--}}
        {{--</li>--}}
    {{--</ul>--}}
    <blockquote class="layui-elem-quote all">
        用户统计
    </blockquote>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="200">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>用户总数</th>
                <th>代理人数</th>
                <th>会员人数</th>
                <th>昨日注册人数</th>
                <th>今日注册人数</th>
                <th>今日投注总额</th>
                <th>今日中奖总额</th>
                <th>今日充值总额</th>
                <th>今日提现总额</th>
                <th>当前在线人数</th>
                <th>余额总数</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$user_count}}</td>
                <td>{{$agent_number}}</td>
                <td>{{$huiyuan_number}}</td>
                <td>{{$yesterdat_people_number}}</td>
                <td>{{$toady_people_number}}</td>
                <td>{{number_format($user_today_data["betting"],2)}}</td>
                <td>{{number_format($user_today_data["winning"],2)}}</td>
                <td>{{number_format($user_today_data["recharge"],2)}}</td>
                <td>{{number_format($user_today_data["withdrawals"],2)}}</td>
                <td>{{number_format($zaixian_people)}}</td>
                <td>{{number_format($yuer,2)}}</td>
            </tr>
            </tbody>


        </table>
    </div>


    {{--彩种投注金额统计--}}
    {{--<ul class="layui-nav" style="background: RGB(0,157,217);">--}}
        {{--<li class="layui-nav-item">--}}
            {{--<a style="color: white;font-size: 1.3rem">彩种投注金额统计</a>--}}
        {{--</li>--}}
    {{--</ul>--}}
    <blockquote class="layui-elem-quote all">
        彩种投注金额统计
    </blockquote>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="200">
                <col>
            </colgroup>
            <div id="showul">
                <ul>
                    @foreach($game_data as $key=>$value)
                        <li>{{$value["name"]}}：{{number_format($value["money"],2)}}</li>
                    @endforeach
                </ul>
            </div>

        </table>
    </div>

    {{--<p style="text-align: right;font-size: 12px;color:gray">此系统仅供个人学习、研究之用，禁止非法传播或用于商业用途！</p>--}}
</div>
<script src="./plugins/layui/layui.js"></script>
<script>
    layui.use('jquery', function() {
        var $ = layui.jquery;
        $('#test').on('click', function() {
            parent.message.show({
                skin: 'cyan'
            });
        });
    });
</script>
</body>

</html>