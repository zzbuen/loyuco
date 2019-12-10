<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>盈亏报表</title>
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
        <legend>盈亏报表</legend>
    </fieldset>
    <div class="layui-row layui-col-space15">
        <input type="hidden" id="now_game" value="">
        <div class="body_right">
            <div style="margin-bottom: 10px">
                <ul id="category_ul">
                    <li>


                        <div class="layui-inline">
                            <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{$val}}" class="layui-input" id="test6" placeholder=" - ">
                            </div>
                            <button id="select" class="layui-btn" style="margin-left: 30px">查询</button>
                            <button id="clear" class="layui-btn">清空选择</button>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="layui-table">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总盈亏
                                    {{--<br/>(总中奖-总投注+总彩金)--}}
                                </span>
                                <span class="info-box-number">
                                    <span style="color: red;">{{number_format($reqq["total"],2)}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总投注金额</span>
                                <span class="info-box-number">
                                    <span style="color: red;">{{number_format($reqq["betting"],2)}}</span>
                                 </span>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:green !important;color:white;"><i class="fa fa-usd" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总中奖金额</span>
                                <span class="info-box-number"><span style="color: red;">{{number_format($reqq["winning"],2)}}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:#78341a !important;color:white;"><i class="fa fa-usd" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总充值</span>
                                <span class="info-box-number"><span style="color: red;">{{number_format($reqq["recharge"],2)}}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总提现金额</span>
                                <span class="info-box-number">
                                    <span style="color: red;">{{number_format($reqq["withdrawals"],2)}}</span>
                                 </span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:gainsboro !important;color:white;"><i class="fa fa-bars" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总彩金数</span>
                                <span class="info-box-number"><span style="color: red;">{{$reqq["bonus"]}}</span></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="layui-form" >
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>用户名</th>
                        <th>代理级别</th>
                        {{--<th>下级代理</th>--}}
                        <th>上级代理</th>
                        <th>团队人数(不包括自己)</th>
                        <th>团队余额(不包括自己)</th>
                        <th>注册时间</th>
                        <th>操作</th>h>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key=>$value)
                        <tr>
                            <td>
                                {{$value["username"]}}
                            </td>
                            <td>
                                {{$value['dl_level']}}级代理

                            </td>
                            {{--<td style="width: 30%;word-break:break-all;word-wrap:break-all;">{{$value["all_username"]}}</td>--}}
                            <td>
                                @if($value["userinfo"]["parent_user_id"] == 0)
                                    <span style="color: red">系统</span>
                                @else
                                    {{$value["parent_user_id"]}}
                                @endif
                            </td>
                            <td>{{$value["teamNum"]}}</td>
                            <td>{{$value["money"]}}</td>
                            <td>{{$value["userinfo"]["create_time"]}}</td>
                            <td>
                                @if($value["dl_level"] == 2)
                                    <button data="{{$value["user_id"]}}" class="layui-btn-primary layui-btn-small modAgent">更换一级代理</button>
                                @endif
                                <button data="{{$value["user_id"]}}" class="layui-btn-primary layui-btn-small ykdili">团队盈亏</button>

                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            {{$data->appends([
                "time" =>$time,
                "val"   => $val,
            ])->links()}}
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

        $("#clear").click(function () {
            $("#user_id").val("");
            $("#test6").val("");
        })


        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/stat.plat.index') }}?time="+$data;
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
            location.href = "{{url('manager/agentCenter2.index') }}?val="+val+'&time=x';
        });

        $(document).on("click",".ykdili",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/teamStat_hou")}}"+"?agent_id="+$user_id;
            layer.open({
                type: 2,
                title: '消息发送',
                shadeClose: true,
                shade: 0.8,
                area: ["60%", '80%'],
                maxmin: true,
                content:url
            });
        })


    });





</script>

</body>

</html>