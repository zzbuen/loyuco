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
        <legend>优惠活动参与人数</legend>
    </fieldset>
    <div class="layui-form">
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">活动名称</th>
                    <th style="text-align: center">类型</th>
                    <th style="text-align: center">活动描述</th>

                    {{--<th style="text-align: center">用户ID</th>--}}
                    <th style="text-align: center">用户名</th>
                    <th style="text-align: center">金额</th>
                    <th style="text-align: center">状态</th>
                    <th style="text-align: center">开始时间</th>
                    <th style="text-align: center">结束时间</th>
                    <th style="text-align: center">参与时间</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key=>$value)
                    <tr>
                        <th style="text-align: center">{{$value["active"]["active_name"]}}</th>
                        <th style="text-align: center">
                            @if($value["active"]["active_type"] == 1)
                                充值
                            @endif
                            @if($value["active"]["active_type"] == 2)
                                消费
                            @endif
                        </th>
                        <th style="text-align: center">{{$value["active"]["active_descript"]}}</th>

                        {{--<th style="text-align: center">{{$value["user_id"]}}</th>--}}
                        <th style="text-align: center">{{$value["user"]["username"]}}</th>
                        <th style="text-align: center">{{$value["money"]}}</th>
                        <th style="text-align: center">
                            @if($value["active"]["active_status"] == 1)
                                未开始
                            @endif
                            @if($value["active"]["active_status"] == 2)
                                进行中
                            @endif
                            @if($value["active"]["active_status"] == 3)
                                已结束
                            @endif
                        </th>
                        <th style="text-align: center">{{date("Y-m-d H:i:s",$value["active"]["start_time"])}}</th>
                        <th style="text-align: center">{{date("Y-m-d H:i:s",$value["active"]["end_time"])}}</th>
                        <th style="text-align: center">{{date("Y-m-d H:i:s",$value["create_time"])}}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
        var type_id = "{{Request::input('user_type')}}";
        if(type_id) {
            if(type_id==1) {
                $("#user_type").children().eq(1).attr('selected',true)
            } else {
                $("#user_type").children().eq(2).attr('selected',true)
            }
        } else {
            $("#user_type").children().eq(0).attr('selected',true)
        }
        var leader_id = "{{Request::input('leader_id')}}";
        if(leader_id) {
            $("#leader_id option").each(function () {
                if($(this).val()==leader_id){
                    $(this).attr('selected',true)
                }
            })
        } else {
            $("#leader_id").children().eq(0).attr('selected',true)
        }
        $(".become_agent").click(function () {
            var user_id = $(this).prev().val();
            layer.open({
                type: 2,
                title: '成为代理',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '50%'],
                maxmin: true,
                content: '{{ url("manager/becomeAgent") }}?user_id='+user_id,
            });
        });








    });
</script>

</body>

</html>