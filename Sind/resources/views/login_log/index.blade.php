<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>登录日志查询</title>
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
    /*.getUser_detail{*/
        /*text-decoration: underline;*/
        /*cursor: pointer;*/
    /*}*/
    .thcenter{
        text-align: center;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>日志信息</legend>
    </fieldset>
    <div style="display: inline-block;top: 2px;position: relative;margin: 0 30px 10px 30px;">
        <div>
            <span>用户名：</span>
            <input value="{{$user_id}}" id="select_userId" placeholder="用户名查询" name="user_name" type="text" class="layui-input username_input">
        </div>
    </div>
    <input type="button" data="{{url("manager/log")}}" id="select" class="layui-btn" value="查询">
    <div class="layui-form">
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">用户账号</th>
                    <th style="text-align: center">IP</th>
                    <th style="text-align: center">登录时间</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key=>$value)
                    <tr >
                        <th style="text-align: center">{{$value["user"]["username"]}}</th>
                        <th style="text-align: center">{{$value["remote_address"]}}</th>
                        <th style="text-align: center">{{$value["create_time"]}}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{$data->appends([
       "user_id" => $user_id
    ])->links()}}
    <audio  display="none" src="/sound/excess.mp3"  id="warning_tone">111</audio>
</div>
<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;

        /*用户名查询*/
        $(document).on("click","#select",function () {
            var data = $(this).attr("data");
            var user_id = $("#select_userId").val();
            location.href = data+"?user_id="+user_id;
        });
    });
</script>

</body>

</html>