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
        <legend>优惠活动</legend>
    </fieldset>
    {{--<form method="post" action="{{url('manager/getUser.index')}}">--}}
        {{--{{ csrf_field() }}--}}
        {{--<div style="display: inline-block">--}}
            {{--<div>--}}
                {{--<span>用户ID：</span>--}}
                {{--<input value="{{$user_id}}" placeholder="用户ID查询.." name="user_name" type="text" class="layui-input username_input">--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<input type="submit" class="layui-btn" value="查询">--}}
        {{--<a href="" class="layui-btn" style="float: right">刷新</a>--}}
    {{--</form>--}}
    <div class="layui-form">
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">创建者</th>
                    <th style="text-align: center">领取者</th>
                    <th style="text-align: center">充值金额要求</th>
                    <th style="text-align: center">奖励金额</th>
                    <th style="text-align: center">累计充值</th>
                    <th style="text-align: center">状态</th>
                    <th style="text-align: center">开始时间</th>
                    <th style="text-align: center">结束时间</th>
                    {{--<th style="text-align: center">操作</th>--}}
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key=>$value)
                    <tr>
                        <td style="text-align: center">{{$value["id"]}}</td>

                        <td style="text-align: center">{{$value["parent"]["username"]}}</td>
                        <td style="text-align: center">{{$value["user"]["username"]}}</td>
                        <td style="text-align: center">{{$value["chongzhi_money"]}}</td>
                        <td style="text-align: center">{{$value["jiangli_money"]}}</td>
                        <td style="text-align: center">{{$value["total_chongzhi"]}}</td>
                        <td style="text-align: center">
                            @if($value["status"] == 1)
                                已开始
                            @endif
                            @if($value["status"] == 3)
                                已领取
                            @endif
                            @if($value["status"] == 4)
                                已结束
                            @endif
                        </td>
                        <td style="text-align: center">{{date("Y-m-d H:i:s",$value["start_time"])}}</td>
                        <td style="text-align: center">{{date("Y-m-d H:i:s",$value["end_time"])}}</td>
                        {{--<td style="text-align: center">--}}
                            {{--<button class="layui-btn look_user" data="{{$value["id"]}}">查看参与人数</button>--}}
                        {{--</td>--}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{$data->links()}}
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


        /**
         * 作用：查看活动参与人数
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".look_user",function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '活动参与人数',
                shadeClose: true,
                shade: 0.8,
                area: ['100%', '100%'],
                maxmin: true,
                content: '{{ url("manager/active_user") }}?id='+id,
            });
        });





    });
</script>

</body>

</html>