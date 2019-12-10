<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>用户盈亏</title>
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
        <legend>用户盈亏</legend>
    </fieldset>
    <input type="hidden" id="agent_id" value="{{$agent_id}}">
    <div class="layui-row layui-col-space15">
        <input type="hidden" id="now_game" value="">
        <div class="body_right">
            <div>
                <ul id="category_ul">
                    <li>
                        <label class="layui-form-label" style="color: #0C0C0C">快捷选时：</label>
                        <button data="1" class="kj_time layui-btn <?php if($time!=1 ) echo "layui-btn-primary";?>">今天</button>
                        <button data="2" class="kj_time layui-btn <?php if($time!=2) echo "layui-btn-primary";?>">昨天</button>
                        <button data="3" class="kj_time layui-btn <?php if($time!=3 ) echo "layui-btn-primary";?>">近三天</button>
                        <button data="7" class="kj_time layui-btn <?php if($time!=7 ) echo "layui-btn-primary";?>">近七天</button>
                        <button data="15" class="kj_time layui-btn <?php if($time!=15) echo "layui-btn-primary";?>">近半月</button>
                        <button data="30" class="kj_time layui-btn <?php if($time!=30 ) echo "layui-btn-primary";?>">近一月</button>
                    </li>
                </ul>
            </div>
            <div style="margin-top: 10px" >
                <ul id="category_ul">
                    <li style="height: 40px">
                        <div class="layui-form">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="color: #0C0C0C">用户名：</label>
                                    <div class="layui-input-inline">
                                        <input type="text" value="{{$user_id}}" class="layui-input" id="user_id" placeholder="请输入用户名">
                                    </div>
                                    <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                                    <div class="layui-input-inline">
                                        <input type="text" value="{{$val}}" class="layui-input" id="test6" placeholder=" - ">
                                    </div>
                                    <button id="select" class="layui-btn">查询</button>
                                    <button id="clear" class="layui-btn">清空选择</button>
                                </div>
                            </div>
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
                                <span class="info-box-text">总盈亏</span>
                                <span class="info-box-number">
                                    <span style="color: red;">{{number_format($data["total"],2)}}</span>
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
                                    <span style="color: red;">{{number_format($data["betting"],2)}}</span>
                                 </span>
                            </div>
                        </div>
                    </div>

                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:green !important;color:white;"><i class="fa fa-usd" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总中奖金额</span>
                                <span class="info-box-number"><span style="color: red;">{{number_format($data["winning"],2)}}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:#78341a !important;color:white;"><i class="fa fa-usd" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总充值</span>
                                <span class="info-box-number"><span style="color: red;">{{number_format($data["recharge"],2)}}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总提现金额</span>
                                <span class="info-box-number">
                                    <span style="color: red;">{{number_format($data["withdrawals"],2)}}</span>
                                 </span>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md2">
                        <div class="info-box">
                            <span class="info-box-icon" style="background-color:gainsboro !important;color:white;"><i class="fa fa-bars" aria-hidden="true"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">总投注数量</span>
                                <span class="info-box-number"><span style="color: red;">{{$data["betNum"]}}</span></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="layui-form" >
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th class=" sorting" sort="daymoney">用户名</th>
                        <th class=" sorting" sort="server_charge">余额</th>
                        <th class=" sorting" sort="server_charge">投注金额</th>
                        <th class=" sorting" sort="share_profit">投注数量</th>
                        <th class=" sorting" sort="share_profit">充值金额</th>
                        <th class=" sorting" sort="share_profit">提现金额</th>
                        <th class=" sorting" sort="share_profit">彩金总额</th>
                        <th class=" sorting" sort="pure_profit">中奖金额</th>
                        <th class=" sorting" sort="pure_profit">彩票盈亏</th>
                        <th class=" sorting" sort="pure_profit">总盈亏</th>
                        <th class=" sorting" sort="pure_profit">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_data as $key=>$value)
                        <tr>
                            <td class=" sorting" sort="daymoney">{{$value["username"]}}</td>
                            <td class=" sorting" sort="server_charge">{{$value["remaining_money"]}}</td>
                            <td class=" sorting" sort="server_charge">{{$value["betting"]}}</td>
                            <td class=" sorting" sort="share_profit">{{$value["betNum"]}}</td>
                            <td class=" sorting" sort="share_profit">{{$value["recharge"]}}</td>
                            <td class=" sorting" sort="share_profit">{{$value["withdrawals"]}}</td>
                            <td class=" sorting" sort="pure_profit">{{$value["bonus"]}}</td>
                            <td class=" sorting" sort="pure_profit">{{$value["winning"]}}</td>
                            <td class=" sorting" sort="pure_profit">{{$value['winning'] - $value["betting"]}}</td>
                            <td class=" sorting" sort="pure_profit">
                                @if($value["recharge"]-$value["withdrawals"]<0)
                                    <span style="color: red">{{$value["recharge"]-$value["withdrawals"]}}</span>
                                @else
                                    {{$value["recharge"]-$value["withdrawals"]}}
                                @endif
                            </td>

                            <td class=" sorting" sort="pure_profit">
                                @if($value["role_id"] == 2 )
                                    <button data="{{$value["user_id"]}}" class="layui-btn-primary layui-btn-small ykdili">查看下级</button>
                                @endif
                                    <button data="{{$value["user_id"]}}" data2="{{$value["username"]}}"  class="layui-btn-primary layui-btn-small recond">查看投注记录</button>

                            </td>
                        </tr>
                    @endforeach
                    @if(count($all_data)==0)
                        <tr>
                            <td colspan="10">暂无数据</td>
                        </tr>
                    @else
                        <tr>
                            <td class=" sorting" sort="daymoney">合计</td>
                            <td class=" sorting" sort="server_charge">{{$data["remaining_money"]}}</td>
                            <td class=" sorting" sort="server_charge">{{$data["betting"]}}</td>
                            <td class=" sorting" sort="share_profit">{{$data["betNum"]}}</td>
                            <td class=" sorting" sort="share_profit">{{$data["recharge"]}}</td>
                            <td class=" sorting" sort="share_profit">{{$data["withdrawals"]}}</td>
                            <td class=" sorting" sort="pure_profit">{{$data["bonus"]}}</td>
                            <td class=" sorting" sort="pure_profit">{{$data["winning"]}}</td>
                            <td class=" sorting" sort="pure_profit">{{$data['winning'] - $data["betting"]}}</td>
                            <td class=" sorting" sort="pure_profit">
                                @if($data["recharge"]-$data["withdrawals"]<0)
                                    <span style="color: red">{{$data["recharge"]-$data["withdrawals"]}}</span>
                                @else
                                    {{$data["recharge"]-$data["withdrawals"]}}
                                @endif
                            </td>

                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            @if($type==2)
                {{$all_data->appends([
                    "time" =>$time,
                    "val"   => $val,
                    "user_id"=>$user_id,
                ])->links()}}
            @endif
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
        $(document).on("click",".ykdili",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/teamStat_hou")}}"+"?agent_id="+$user_id;
            layer.open({
                type: 2,
                title: '查看下级',
                shadeClose: true,
                shade: 0.8,
                area: ["60%", '80%'],
                maxmin: true,
                content:url
            });
        })
        $(document).on("click",".recond",function () {
            var $user_id = $(this).attr("data2");
            var url = "{{ url("agent/getOrder")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '查看投注记录',
                shadeClose: true,
                shade: 0.8,
                area: ["60%", '80%'],
                maxmin: true,
                content:url
            });
        })
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
            var $user_id = $("#agent_id").val()

            location.href = "{{url('manager/teamStat_hou') }}?time="+$data+"&agent_id="+$user_id;
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
            var user_id = $("#user_id").val().replace(/(^\s*)|(\s*$)/g, "");
            var $aagnet = $("#agent_id").val()

            location.href = "{{url('manager/teamStat_hou') }}?val="+val+"&agent_id="+$aagnet;
        });




    });





</script>

</body>

</html>