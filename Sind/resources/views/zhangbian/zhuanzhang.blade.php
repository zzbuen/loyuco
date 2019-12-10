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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>转账记录</legend>
    </fieldset>
    <div class="body_right">
        <div >
            <ul id="category_ul">
                <li>
                    <label class="layui-form-label"  style="color: #0C0C0C">快捷选时：</label>
                    <button data="1" class="kj_time layui-btn <?php if($time != 1)echo "layui-btn-primary";?>">今天</button>
                    <button data="2" class="kj_time layui-btn <?php if($time != 2)echo "layui-btn-primary";?>">昨天</button>
                    <button data="3" class="kj_time layui-btn <?php if($time != 3)echo "layui-btn-primary";?>">近三天</button>
                    <button data="7" class="kj_time layui-btn <?php if($time != 7)echo "layui-btn-primary";?>">近七天</button>
                    <button data="15" class="kj_time layui-btn <?php if($time != 15)echo "layui-btn-primary";?>">近半月</button>
                    <button data="30" class="kj_time layui-btn <?php if($time != 30)echo "layui-btn-primary";?>">近一月</button>
                </li>
            </ul>
        </div>
        <div style="margin-top: 10px">
            <ul id="category_ul">
                <li style="height: 40px">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label"  style="color: #0C0C0C">用户名：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$user_id}}" id="user_id" class="layui-input" placeholder="用户名..">
                                </div>

                                <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$near_time}}" class="layui-input" id="test6" placeholder=" - ">
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
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                    <tr style=" border-top: 1px solid black">
                        <th class="tdcenter">用户名</th>
                        <th class="tdcenter">转出账户</th>
                        <th class="tdcenter">转入账户</th>
                        <th class="tdcenter">操作金额</th>
                        <th class="tdcenter">彩票账户余额</th>
                        <th class="tdcenter">AG账户余额</th>
                        <th class="tdcenter">BBIN账户余额</th>
                        <th class="tdcenter">操作时间</th>
                        <th class="tdcenter">备注</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key=>$value)
                        <tr style=" border-top: 1px solid black">
                            <th class="tdcenter">{{$value["user"]["username"]}}</th>
                            <th class="tdcenter">{{$value["chu"]}}</th>
                            <th class="tdcenter">{{$value["jin"]}}</th>
                            <th class="tdcenter">{{$value["money"]}}</th>
                            <th class="tdcenter">
                                @if($value["type"]==1)
                                {{$value["before_money"]}}
                                @endif
                                @if($value["type2"]==1)
                                    {{$value["before_money2"]}}
                                @endif
                            </th>
                            <th class="tdcenter">
                                @if($value["type"]==2)
                                    {{$value["before_money"]}}
                                @endif
                                @if($value["type2"]==2)
                                    {{$value["before_money2"]}}
                                @endif
                            </th>
                            <th class="tdcenter">
                                @if($value["type"]==3)
                                    {{$value["before_money"]}}
                                @endif
                                @if($value["type2"]==3)
                                    {{$value["before_money2"]}}
                                @endif
                            </th>
                            <th class="tdcenter">{{date("Y-m-d H:i:s",$value["created_at"])}}</th>
                            <th class="tdcenter">{{$value["remarks"]}}</th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{--{{$data->appends([--}}
                {{--"time"      => $time,--}}
                {{--"user_id"   => $user_id,--}}
                {{--"val"       => $val,--}}
                {{--"select_status" => $status,--}}
            {{--])->links()}}--}}
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
            $("#select_status").val("");
            form.render();
        });


        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/agent_zhangbian') }}?time="+$data;
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
            var user_id =   $("#user_id").val().replace(/(^\s*)|(\s*$)/g, "");
            var select_status = $("#select_status").val();
            location.href = "{{url('manager/agent_zhangbian') }}?val="+val+"&user_id="+user_id+"&select_status="+select_status;
        });
        


        
    });





</script>

</body>

</html>