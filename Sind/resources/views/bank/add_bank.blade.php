<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    {{--<link href="http://www.jq22.com/jquery/bootstrap-3.3.4.css" rel="stylesheet">--}}
    <script src="http://libs.baidu.com/html5shiv/3.7/html5shiv.min.js"></script>
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
        width: 100px;
        border-right: 1px solid #e2e2e2;
        float: left;
    }
    .last_game_td{
        border-bottom: none;

    }
    .body_right{

        height: 750px;
        width: 1300px;
        margin-left: 140px;

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
    .text_div{
        height: 30px;
        width: 100px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;
        margin-left: 30px;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
    .input15{
        display: inline-block ;
    }
    .juzhon{
        display:flex;
        justify-content:center;
    }
</style>

<body>
<div class="layui-everyday-list">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>{{$user["username"]}} - 添加新银行卡</legend>
        <input type="hidden" value="{{$user["user_id"]}}" id="user_id">
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行类型</label>
            <div class="layui-input-inline">
                <select class="layui-select" id="select">
                    @foreach($bank as $key=>$value)
                        <option value="{{$value["id"]}}">{{$value["bank_name"]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行卡号</label>
            <div class="layui-input-inline">
                <input id="kahao" type="text" name="source_name"  value="" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行分支</label>
            <div class="layui-input-inline">
                <div id="distpicker1">
                    <select class="layui-select" id="sheng"></select>
                    <select class="layui-select" id="shi"></select>
                    <select class="layui-select" id="qu"></select>
                </div>
            </div>
        </div>

        <div class="layui-form-item" style="text-align: center">
            <button type="button" class="layui-btn" id="add">确认添加</button>
        </div>
    </form>
    <div style="width: 100%;text-align: center;margin-top: 5%">
        {{--<button class="layui-btn" type="button" id="sure">确定</button>--}}
    </div>

</div>

<script src="/js/jquery-1.9.1.js"></script>
<script src="http://www.jq22.com/jquery/bootstrap-3.3.4.js"></script>
<script src="/plugins/layui/layui.js"></script>
<script src="/js/xin.js"></script>
<script src="/jQuerydiqu/js/distpicker.data.js"></script>
<script src="/jQuerydiqu/js/distpicker.js"></script>
<script src="/jQuerydiqu/js/main.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;

        $("#distpicker1").distpicker();




        /**
         * 作用：添加新银行卡号
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $("#add").click(function () {
            var bank_id = $("#select").val();
            var account = $("#kahao").val().replace(/\s+/g, "");
            var user_id = $("#user_id").val();
            var reg     = /^([1-9]{1})(\d{14}|\d{18})$/;
            /*支行信息*/
            var sheng = $("#sheng").val();
            var shi = $("#shi").val();
            var qu = $("#qu").val();
            if(!sheng || !shi || !qu){
                layer.alert("请将支行信息填写完整");
                return false;
            }
            var zhihang = sheng+" "+shi+" "+qu;
            if(!reg.test(account)){
                layer.alert("请输入正确银行卡号");
                return false;
            }
            layer.load(1);
            $.ajax({
                type: "POST",
                url: "/manager/add_bank_ajax",
                data: {
                    "user_id":user_id,
                    "account":account,
                    "bank_id":bank_id,
                    "bank_branch":zhihang,
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    layer.closeAll("loading");
                    if(res.code){
                        layer.msg(res.msg,{"icon":1})
                        parent.location.reload();
                        return false;
                    }
                    layer.msg(res.msg,{"icon":2})
                },error:function (err) {
                    layer.closeAll("loading");
                    layer.msg("系统异常,请稍后再试",{"icon":2})
                }
            });
        })

    });

</script>

</body>

</html>