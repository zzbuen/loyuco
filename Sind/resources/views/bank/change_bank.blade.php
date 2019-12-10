<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link href="http://www.jq22.com/jquery/bootstrap-3.3.4.css" rel="stylesheet">
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
</style>

<body>
<div class="layui-everyday-list">
    <div class="form-element element-name">
        <div class="layui-form-item" style="margin-top: 30px" >
            <label class="layui-form-label" style="width: 30%">持卡人姓名:</label>
            <div class="layui-input-inline" style="width: 57%">
                <input type="text" name="bank_account_name" class="layui-input" id="bank_account_name" value="{{$user_bank["bank_account_name"]}}"/>
            </div>
        </div>

        <div class="layui-form-item" >
            <label class="layui-form-label" style="width: 30%">银行卡号:</label>
            <div class="layui-input-inline" style="width: 57%">
                <input type="text" name="bank_account" class="layui-input" id="bank_account" value="{{$user_bank["bank_account"]}}"/>
            </div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label" style="width: 30%">支行:</label>
            <div class="layui-input-inline" style="width: 57%">
                <input type="text" name="bank_details" class="layui-input" id="bank_details" value="{{$user_bank["bank_details"]}}"/>
            </div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label" style="width: 30%">银行:</label>
            <div class="layui-input-inline"  style="width: 57%">
                <select class="layui-select" id="select" style="width: 100%">
                    @foreach($bank as $key=>$value)
                        @if($value["bank_name"]  == $user_bank["bank_name"])
                            <option selected="selected" value="{{$value["bank_name"]}}">{{$value["bank_name"]}}</option>
                            @else
                            <option value="{{$value["bank_name"]}}">{{$value["bank_name"]}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <button id="mondify_ajax" class="layui-btn layui-btn" style="margin-top: 20px;margin-left: 156px">确认修改</button>
    </div>
</div>
<input type="hidden" id="change_id" value="{{$user_bank["id"]}}">
<input type="hidden" id="user_id" value="{{$user_bank["user_id"]}}">
<input type="hidden" id="bank_branch" value="{{$user_bank["bank_branch"]}}">
<input type="hidden"   id="yuan_kahao" value="{{$user_bank["account"]}}"/>


<script src="http://www.jq22.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://www.jq22.com/jquery/bootstrap-3.3.4.js"></script>
<script src="/plugins/layui/layui.js"></script>
<script src="/js/xin.js"></script>
<script src="/jQuerydiqu/js/distpicker.data.js"></script>
<script src="/jQuerydiqu/js/distpicker.js"></script>
{{--<script src="/jQuerydiqu/js/main.js"></script>--}}
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
        var bank_branch  = $("#bank_branch").val().split(" ");

        $("#distpicker3").distpicker({
            province: bank_branch[0],
            city: bank_branch[1],
            district: bank_branch[2]
        });

        $("#mondify_ajax").click(function () {
            var bank_name = $("#select").val();
            var bank_account = $("#bank_account").val().replace(/\s+/g, "");
            var bank_details = $("#bank_details").val();
            var bank_account_name = $("#bank_account_name").val();
            var id      = $("#change_id").val();
            var user_id = $("#user_id").val();
            var reg     = /^([1-9]{1})(\d{14}|\d{18})$/;
            var yuan_kahao = $("#yuan_kahao").val();

            if(bank_name==""){
                layer.alert("请输入真实姓名");
                return false;
            }
            if(!reg.test(bank_account)){
                layer.alert("请输入正确银行卡号");
                return false;
            }
            $.ajax({
                type: "POST",
                url: "/manager/change_bank_ajax",
                data: {
                    "id":id,
                    "bank_account":bank_account,
                    "bank_name":bank_name,
                    "bank_account_name":bank_account_name,
                    "user_id":user_id,
                    "bank_details":bank_details,
                    "yuan_kahao":yuan_kahao
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.code){
                        layer.msg(res.msg,{"icon":1})
                        parent.location.reload();
                        return false;
                    }
                    layer.msg(res.msg,{"icon":2})
                }
            });
        })
    });

</script>

</body>

</html>