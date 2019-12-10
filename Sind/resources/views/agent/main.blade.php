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
        #QRcode{
            height: 100px;
            width:100px;
            max-height: 100px;
            max-width: 100px;
        }
    </style>
</head>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field">
        <legend>基本信息</legend>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#FF0000  !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">今日收入</span>
                        <span class="info-box-number">{{ $today_income }}</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">总收入</span>
                        <span class="info-box-number">{{ $info['totle_profit'] }}</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#AAAAAA !important;color:white;"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">已提现</span>
                        <span class="info-box-number">{{ $info['expend_profit'] }}</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#00a65a !important;color:white;"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">余额</span>
                        <span class="info-box-number">{{ $info['valid_profit'] }}</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#f39c12 !important;color:white;"><i class="fa fa-pie-chart" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">提成比例</span>
                        <span class="info-box-number">{{ $info['share_percent'] }}%</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#0000FF  !important;color:white;"><i class="fa fa-users" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">推广用户数</span>
                        <span class="info-box-number">{{ $share_num }}</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#FF0000 !important;color:white;"><i class="fa fa-asl-interpreting" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">邀请码</span>
                        <span class="info-box-number">{{ $invitation_num }}</span>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>团队盈亏</legend>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#FF0000  !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">今日盈亏</span>
                        <span class="info-box-number">{{ $today_settle }}</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">历史盈亏</span>
                        <span class="info-box-number">{{ $history_settle }}</span>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    {{--<fieldset class="layui-elem-field">--}}
        {{--<legend>推广信息</legend>--}}
        {{--<div class="layui-row layui-col-space15">--}}
            {{--<div class="layui-col-md4">--}}

            {{--</div>--}}
            {{--<div class="layui-col-md4" style="text-align: center">--}}
                {{--<img src="{{$agent_download['download_url']}}" alt="">--}}
                {{--<p style="color: red">您的邀请码{{$relation_code['invitation_num']}}</p>--}}
            {{--</div>--}}
            {{--<div class="layui-col-md4">--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</fieldset>--}}
    <p style="text-align: right;font-size: 12px;color:gray">此系统仅供个人学习、研究之用，禁止非法传播或用于商业用途！</p>
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