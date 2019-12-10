<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
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
        width: 100%;

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
        <legend>{{$game_name["name"]}}第{{$periods}}期参与人数信息</legend>
    </fieldset>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">
        <div class="layui-table">
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                    <tr style=" border-top: 1px solid black">
                        <th class="tdcenter">彩种</th>
                        <th class="tdcenter">类型</th>
                        <th class="tdcenter">玩法</th>
                        <th class="tdcenter">投注名称</th>
                        <th class="tdcenter">订单号</th>
                        <th class="tdcenter">用户名称</th>
                        <th class="tdcenter">下注金额</th>
                        <th class="tdcenter">下注数量</th>
                        <th class="tdcenter">投注号码</th>
                        <th class="tdcenter">订单时间</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($data))
                        <tr style="border:none">
                            <td colspan="12">赞无数据信息</td>
                        </tr>
                        @else
                        @foreach($data as $key=>$val)
                            <tr style="border:none">
                                <td  class="tdcenter">{{$val["gameName"]}}</td>
                                <td  class="tdcenter">{{$val["typeName"]}}</td>
                                <td  class="tdcenter">{{$val["category"]}}</td>
                                <td  class="tdcenter">{{$val["ruleName"]}}</td>
                                <td  class="tdcenter">{{$val["order_id"]}}</td>
                                <td  class="tdcenter">{{$val["userName"]}}</td>
                                <td  class="tdcenter">{{$val["bet_money"]}}</td>
                                <td  class="tdcenter">{{$val["bet_num"]}}</td>
                                <td  class="tdcenter">
                                    <?php $res = explode(",",$val["bet_value"]);?>
                                    @foreach($res as $k=>$v)
                                        <span class="layui-badge">{{$v}}</span>
                                    @endforeach
                                </td>
                                <td  class="tdcenter">{{$val["order_dateTime"]}}</td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
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



        
    });





</script>

</body>

</html>