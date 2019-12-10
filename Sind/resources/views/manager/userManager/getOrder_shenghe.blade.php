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
    tr th{
        text-align: center!important;
    }
    tr td{
        text-align: center!important;
    }
    td button{
        margin: 0!important;
    }
    .neiron{
        max-height: 20px!important;
        max-width: 100px!important;
        overflow: hidden!important;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .look_neiron:hover{
        color: dodgerblue;
        text-decoration: underline;
    }
</style>

<body>
<div class="layui-fluid main">
    <div class="layui-tab">
        <fieldset class="layui-field-title layui-elem-field">
            <legend>今日投注 -- {{$username}}</legend>
        </fieldset>
        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>用户名</th>
                        <th>游戏名称</th>
                        <th>玩法</th>
                        <th>子玩法</th>
                        <th>期号</th>
                        <th style="min-width: 60px!important;">金额
                        </th>
                        <th class="neiron">投注内容</th>
                        <th>是否中奖</th>
                        <th>状态</th>
                        <th>投注时间</th>

                    </tr>
                </thead>
            <tbody>
                @forelse($data as $key=>$val)
                    <tr>
                        <td>{{$val["order_id"]}}</td>
                        <td>{{$val["user"]["username"]}}</td>
                        <td>{{$val["odds"]["gameName"]}}</td>
                        <td>{{$val["odds"]["category"]}}</td>
                        <td>{{$val["odds"]["ruleName"]}}</td>
                        <td>{{$val["bet_period"]}}</td>
                        <td style="min-width: 50px!important;">{{$val["bet_money"]}}</td>
                        <td data="{{$val["id"]}}" class="neiron look_neiron" title="点击查看投注内容详情">
                            <?php

                            $str = $val['bet_value'];
                            $index = strpos($str,'|');
                            if($index){
                                $wei = substr($str,0,$index);
                                $wei = str_split($wei,1);
                                foreach ($wei as $key=>$item)
                                {
                                    switch ($item)
                                    {
                                        case '0':
                                            $wei[$key] = '万';
                                            break;
                                        case '1':
                                            $wei[$key] = '千';
                                            break;
                                        case '2':
                                            $wei[$key] = '百';
                                            break;
                                        case '3':
                                            $wei[$key] = '十';
                                            break;
                                        case '4':
                                            $wei[$key] = '个';
                                            break;
                                    }
                                }
                                $wei = implode('',$wei);
                                $str = substr($str,$index);
                                $str = $wei.$str;
                                echo $str;
                            }else{
                                echo $str;
                            }

                            ?>
                        </td>
                        <td>
                            @if($val["status"])
                                @if($val["bonus"]=="0")
                                    <span style="color: green;"> 未中奖</span>
                                    @else
                                    <span style="color: red;font-weight: bold">{{$val["bonus"]}}</span>
                                @endif
                                @else
                               未开奖
                            @endif
                        </td>
                        <td>
                            @if($val["status"])
                                已开奖
                            @else
                                未开奖
                            @endif
                        </td>
                        <td> {{$val["order_dateTime"]}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12"> 暂无数据 </td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="5"> </td>
                    <td colspan="2"><span>总计投注金额：</span> {{$orderBetZ}} </td>
                    <td colspan="2"><span>总计中奖金额：</span> {{$orderBetMoney}} </td>
                    <td colspan="3"> </td>
                </tr>
            </tbody>
        </table>

        </div>
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->


</body>

</html>