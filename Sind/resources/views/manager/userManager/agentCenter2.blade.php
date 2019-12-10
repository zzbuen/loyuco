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
        margin-left: 10px;
    }
    .text_div1{
        height: 30px;
        line-height: 0px;
        margin-left: 10px;
    }
    .text_span{
        display: inline-block ;
    }
    .username_input{
        width: 150px;
        display: inline-block;
    }
    .agent_children,.group_settle,.total_detail{
        color:#009688;
        text-decoration:underline;
    }
    .agent_children:hover,.total_detail:hover,.agent_detail,.second_agent,.group_settle{
        text-decoration:underline;
        cursor: pointer;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>代理列表</legend>
    </fieldset>
    <form method="post" action="{{ url("manager/agentCenter.index") }}">
        {{ csrf_field() }}
        <div style="display: inline-block">
            <div>
                <div class="text_div">用户名<span class="text_span"></span></div>
                <input value="{{$user_id}}" name="user_id" type="text" class="layui-input username_input">
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <button type="button" class="layui-btn" id="add_agent">+添加账号</button>
    </form>
    <div class="layui-table">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md2">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">本月代理总盈亏</span>
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
                        <span class="info-box-text">本月代理总投注金额</span>
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
                        <span class="info-box-text">本月代理总中奖金额</span>
                        <span class="info-box-number"><span style="color: red;">{{number_format($reqq["winning"],2)}}</span></span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#78341a !important;color:white;"><i class="fa fa-usd" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">本月代理总充值</span>
                        <span class="info-box-number"><span style="color: red;">{{number_format($reqq["recharge"],2)}}</span></span>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="info-box">
                    <span class="info-box-icon" style="background-color:#00c0ef !important;color:white;"><i class="fa fa-heartbeat" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">本月代理总提现金额</span>
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
                        <span class="info-box-text">本月代理总投注数量</span>
                        <span class="info-box-number"><span style="color: red;">{{$reqq["betNum"]}}</span></span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="layui-form">
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
                <th>操作</th>
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
        {{$data->links()}}
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
                $ = layui.jquery,
                layer = layui.layer;


        /**
         * 作用：禁用/启用代理
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(".jinyong,.qiyong").click(function () {
            var $user_id = $(this).attr("data");
            var $state   = $(this).attr("state");
            layer.confirm("确定执行此操作吗？",function () {
                $.ajax({
                    type:"post",
                    url:"{{url("manager/agent_state")}}",
                    data:{"user_id":$user_id,"state":$state},
                    headers:{
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success:function (res) {
                        layer.msg(res.msg);
                        if(res.code){
                            location.reload();
                        }
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试")
                    }
                })
            });

        });


        /**
         * 作用：添加代理
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click","#add_agent",function () {
            layer.open({
                type: 2,
                title: '添加代理',
                shadeClose: true,
                shade: 0.8,
                area: ['100%', '100%'],
                maxmin: true,
                content: '{{ url("manager/add_agent")}}'
            });
        });



        /**
         * 作用：修改代理信息
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".change",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/change_agent")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '修改代理信息',
                shadeClose: true,
                shade: 0.8,
                area: ['100%', '100%'],
                maxmin: true,
                content:url
            });
        });







        /**
         * 作用：签订日工资契约
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".qianding_gongzi",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/qianding_gongzi")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '签订日工资契约',
                shadeClose: true,
                shade: 0.8,
                area: ["500px", '600px'],
                maxmin: true,
                content:url
            });
        })


        /**
         * 作用：签订分红契约
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".qianding_fenhon",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/qianding_fenhon")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '签订分红契约',
                shadeClose: true,
                shade: 0.8,
                area: ["500px", '600px'],
                maxmin: true,
                content:url
            });
        })



        /**
         * 作用：修改分红契约
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".change_fenhon",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/change_fenhon")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '修改分红契约',
                shadeClose: true,
                shade: 0.8,
                area: ["500px", '600px'],
                maxmin: true,
                content:url
            });
        })


        /**
         * 作用：修改日工资契约
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".change_gongzi",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/change_gongzi")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '修改日工资契约',
                shadeClose: true,
                shade: 0.8,
                area: ["500px", '600px'],
                maxmin: true,
                content:url
            });
        });


        /**
         * 作用：查看日工资契约
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".look_gongzi",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/look_gongzi")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '查看日工资契约',
                shadeClose: true,
                shade: 0.8,
                area: ["500px", '600px'],
                maxmin: true,
                content:url
            });
        });


        /**
         * 作用：查看日工资契约
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".look_fenhon",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/look_fenhon")}}"+"?user_id="+$user_id;
            layer.open({
                type: 2,
                title: '查看日工资契约',
                shadeClose: true,
                shade: 0.8,
                area: ["500px", '600px'],
                maxmin: true,
                content:url
            });
        })


        /**
         * 作用：给一级代理下的发消息
         * 作者：信
         * 时间：2018/04/19
         * 修改：暂无
         */
        $(document).on("click",".send_xiaoxi",function () {
            var $user_id = $(this).attr("data");
            var url = "{{ url("manager/send_xiaoxi")}}"+"?user_id="+$user_id;
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

        $(".modAgent").click(function(){
            var user_id = $(this).attr("data");
            layer.prompt({title: '请输入您要更换的一级代理名称', formType: 3}, function(pass, index){
                $.ajax({
                    type: "POST",
                    url: "{{route("manager.modAgent_one")}}",
                    data: {agentId:pass,userId:user_id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(index){
                                location.reload();
                            });
                        }
                        else{
                            layer.msg(res.msg, {icon: 2},function(index){
                                location.reload();
                            });
                        }
                        layer.closeAll("loading");
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                        layer.closeAll("loading");
                    }
                });
                layer.close(index);
            });
        });


    });
</script>

</body>

</html>