<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>平台报表统计</title>
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
    }

    .major {
        font-weight: 100;
        color: #01AAED;
    }

    .main {
        margin-top: 25px;
    }

    .main .layui-row {
        margin: 10px 0;
    }
    .game_td{
        height: 37px;
        line-height: 37px;
        width: 100px;
        text-align: center;
        border-bottom: 1px solid #e2e2e2;
        cursor: pointer;

    }
    .game_table{
        width: 8%;
        border-right: 1px solid #e2e2e2;
        float: left;
    }
    .last_game_td{
        border-bottom: none;

    }
    .body_right{
        height: 750px;
    }
    .category_show{
        height: 60px;
        width: 100%;
    }
    .category_li{
        float: left;
        line-height: 60px;
        height: 60px;
        width: 105px;
        text-align: center;
        margin-left: 10px;
    }
    .select_game {
        color:red;
    }
    .tdcenter{
        text-align: center!important;
    }
    tr td,th{
        text-align: center!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>平台报表统计</legend>
    </fieldset>
    <div class="layui-row layui-col-space15">
        <input type="hidden" id="now_game" value="">
            <div class="body_right">
                <div class="layui-form" >
                    <table class="layui-table">
                        <thead>
                            <tr>
                                <th class=" sorting" sort="daymoney">时间</th>
                                <th class=" sorting" sort="server_charge">总投注</th>
                                <th class=" sorting" sort="share_profit">总派奖</th>
                                <th class=" sorting" sort="share_profit">盈利</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderList as $key=>$value)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$value['bet_money']}}</td>
                                    <td>{{$value['bonus']}}</td>
                                    <td class=" sorting" sort="server_charge">
                                        @if(number_format($value['bet_money']-$value['bonus'],2)>0)
                                            <span style="color: green">{{number_format($value['bet_money']-$value['bonus'],2)}}</span>
                                        @else
                                            <span style="color: red">{{number_format($value['bet_money']-$value['bonus'],2)}}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="layui-table" style="margin-top: 20px">
                        <thead>
                        <tr>
                            <th class=" sorting" sort="daymoney">用户总数</th>
                            <th class=" sorting" sort="server_charge">今日注册数</th>
                            <th class=" sorting" sort="share_profit">代理数</th>
                            <th class=" sorting" sort="share_profit">会员数</th>
                            <th class=" sorting" sort="share_profit">余额总数</th>
                        </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>{{$userList[0]}}</td>
                                <td>{{$userList[1]}}</td>
                                <td>{{$userList[2]}}</td>
                                <td>{{$userList[3]}}</td>
                                <td>{{$userList[4]}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                {{--{{$show_data->appends([--}}
                    {{--"time"  => $time,--}}
                    {{--"val"   => $val,--}}
                {{--])->links()}}--}}
                <h1 style="font-size: 20px;margin-left: 20px;font-weight: 100">游戏今日总投注</h1>
                <div class="layui-table">
                    <div class="layui-row layui-col-space15">
                        <div style="width:18%;display: inline-block;margin-left: 20px">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">重庆时时彩</span>
                                    <span class="info-box-number">
                                       <span style="color: blue;">{{number_format($data[0],2)}}</span>
                            </span>
                                </div>
                            </div>
                        </div>
                        <div style="width:18%;display: inline-block">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-battery-4" aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">北京快乐8</span>
                                    <span class="info-box-number">
                                    <span style="color: blue;">{{number_format($data[1],2)}}</span>
                            </span>
                                </div>
                            </div>
                        </div>
                        <div  style="width:18%;display: inline-block">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">幸运飞艇</span>
                                    <span style="color: blue;">{{number_format($data[2],2)}}</span>
                                </div>
                            </div>
                        </div>
                        <div  style="width:18%;display: inline-block">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-sheqel " aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">PC蛋蛋</span>
                                    <span style="color: blue;">{{number_format($data[3],2)}}</span>
                                </div>
                            </div>
                        </div>
                        <div  style="width:18%;display: inline-block">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-battery-2 " aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">加拿大28</span>
                                    <span style="color: blue;">{{number_format($data[4],2)}}</span>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.ajax({
                type: "POST",
                url: "/manager/secure",
                data: data.field,
                dataType: "json",
                success: function(res){
                    layer.close(index);
                    layer.msg(res.msg, {time:1100});
                    if (res.success) {
                        location.reload();
                    }
                }
            });
            return false;
        });

        if($(".select_game").length>1){
            $($(".select_game")[0]).removeClass('select_game');
        }
        if($(".layui-this").length>1){
            $($(".layui-this")[0]).removeClass('layui-this');
        }

        var game_td_length = $(".game_td").length-1;
        $($(".game_td")[game_td_length]).addClass('last_game_td');


        /*日期*/
        layui.laydate.render({
            elem: '#test6'
            ,range: true
        });




        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/stat.income.index') }}?time="+$data;
        });


        /**
         * 作用：日期范围
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click","#select",function () {
            var val     =   $("#test6").val().replace(/(^\s*)|(\s*$)/g, "");
            location.href = "{{url('manager/stat.income.index') }}?val="+val;
        });
        


        
    });





</script>

</body>

</html>