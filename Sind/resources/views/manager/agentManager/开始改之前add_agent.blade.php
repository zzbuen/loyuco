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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend >{{Request::input('agent_user_id')}}添加代理</legend>
    </fieldset>
    <form style="margin-top: 20px;margin-left: 100px" class="layui-form layui-form-pane" action="{{url("manager/add_agent_form")}}">
        <div class="layui-form-item" style="margin-left: 30%;">
            <label  class="layui-form-label ">类型:</label>
            <div class="layui-input-inline">
                <input  type="radio" class="leixing" name="type" data="2" value="2" title="代理" checked="">
                {{--<input  type="radio" class="leixing" name="type" data="1" value="1" title="会员" checked="">--}}
            </div>
        </div>
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">用户名:</label>
            <div class="layui-input-inline">
                <input  type="text" id="username" name="username" value="" lay-verify="username" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">密码:</label>
            <div class="layui-input-inline">
                <input  type="password" id="password" name="password" value="" lay-verify="password" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">高频彩返点:</label>
            <div class="layui-input-inline">
                <input  type="text" id="fandian"  name="fandian" value="" lay-verify="fandian" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">六合彩返点:</label>
            <div class="layui-input-inline">
                <input  type="text" id="befandain" name="befandain" value="" lay-verify="befandain" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">日工资:</label>
            <div class="layui-input-inline" style="width: 200px">
                <input  type="radio"  name="rigongzi" data="2" value="2" title="设置" checked="">
                <input  type="radio"  name="rigongzi" data="1" value="1" title="不设置" checked="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">日量</label>
            <div class="layui-input-inline">
                <input  type="number" id="amount_day" name="amount_day" value="" lay-verify="amount_day" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">活跃人数</label>
            <div class="layui-input-inline">
                <input  type="number" id="amount_num" name="amount_num" value="" lay-verify="amount_num" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">活跃金额</label>
            <div class="layui-input-inline">
                <input  type="number" id="amount_money" name="amount_money" value="" lay-verify="amount_money" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">日工资比例</label>
            <div class="layui-input-inline">
                <input  type="text" id="wage_ratio" name="wage_ratio" value="" lay-verify="wage_ratio" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">分红:</label>
            <div class="layui-input-inline" style="width: 200px">
                <input  type="radio"  name="fenhon" data="2" value="2" title="设置" checked="">
                <input  type="radio"  name="fenhon" data="1" value="1" title="不设置" checked="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">总亏损</label>
            <div class="layui-input-inline">
                <input  type="number" id="total" name="total" value="" lay-verify="total" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">活跃人数</label>
            <div class="layui-input-inline">
                <input  type="number" id="amount_num_fenhon" name="amount_num_fenhon" value="" lay-verify="amount_num_fenhon" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">活跃金额</label>
            <div class="layui-input-inline">
                <input  type="number" id="amount_money_fenhon" name="amount_money_fenhon" value="" lay-verify="amount_money_fenhon" autocomplete="off" class="layui-input">
            </div>
            <label class="layui-form-label">分红比例</label>
            <div class="layui-input-inline">
                <input  type="text" id="bonus_ratio" name="bonus_ratio" value="" lay-verify="bonus_ratio" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分红策略:</label>
            <div class="layui-input-inline" style="width: 500px">
                <input type="radio" name="bonus_type" value="1" title="半月不累计" checked="true">
                <input type="radio" name="bonus_type" value="2" title="半月累计" checked="">
            </div>
        </div>
        <input type="button" lay-submit="" lay-filter="demo1" id="sub" style="margin-left: 35%" class="layui-btn" value="确认添加">
    </form>
</div>
<input type="hidden" id="one" value="{{$data[7]["value"]}}">
<input type="hidden" id="two" value="{{$data[8]["value"]}}">
<input type="hidden" id="three" value="{{$data[9]["value"]}}">
<input type="hidden" id="four" value="{{$data[10]["value"]}}">
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form    = layui.form,
            $       = layui.jquery,
            layer   = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        var one = $("#one").val();
        var two = $("#two").val();
        var three = $("#three").val();
        var four = $("#four").val();

        var rigongzi = $("inputp[name='rigongzi']:checked").val();

        form.verify({
            username: function(value){
                if(value.replace(/\s+/g, "").length <5 || value.replace(/\s+/g, "").length>10){
                    return '用户名范围5-10位';
                }
            },
            password:function (value) {
                if(value.replace(/\s+/g, "") == ""){
                    return "请输入密码";
                }
                if(!/^[Za-z0-9_]{6,18}$/.test(value)){
                    return "密码格式6-18除特殊字符";
                }
            },
            fandian:function (value) {
                value = value.replace(/\s+/g, "");
                if(value==""){
                    return "请填写高频彩返点";
                }
                if(value-one>0){
                    return "高频彩最高百分点不能大于"+one+"%";
                }
            },
            befandain:function (value) {
                value = value.replace(/\s+/g, "");
                if(value==""){
                    return "请填写六合彩返点";
                }
                if(value-two>0){
                    return "六合彩最高百分点不能大于"+two+"%";
                }
            },
            amount_day:function (value) {
                if(value == ""){
                    return "请填写日工资日量";
                }
                if(value<0){
                    return "日工资日量不能小于0";
                }
            },
            total:function (value) {
                if(value == ""){
                    return "请填写分红总亏损";
                }
                if(value<0){
                    return "分红总亏损不能小于0";
                }
            },
            amount_num:function (value) {
                if(value == ""){
                    return "请填写日工资活跃人数";
                }
                if(value<0){
                    return "日工资活跃人数不能小于0";
                }
            },
            amount_num_fenhon:function (value) {
                if(value == ""){
                    return "请填写分红活跃人数";
                }
                if(value<0){
                    return "分红活跃人数不能小于0";
                }
            },
            amount_money:function (value) {
                if(value == ""){
                    return "请填写日工资活跃金额";
                }
                if(value<0){
                    return "日工资活跃金额不能小于0";
                }
            },
            amount_money_fenhon:function (value) {
                if(value == ""){
                    return "请填写分红活跃金额";
                }
                if(value<0){
                    return "分红活跃金额不能小于0";
                }
            },
            wage_ratio:function (value) {
                value = value.replace(/\s+/g, "");
                if(value==""){
                    return "请填写日工资比例";
                }
                if(!/^[0-9]+.?[0-9]*$/.test(value)){
                    return "请填写正确日工资比例";
                }
                if(value-three>0){
                    return "日工资比例不能大于"+three+"%";
                }
            },
            bonus_ratio:function (value) {
                value = value.replace(/\s+/g, "");
                if(value==""){
                    return "请填写分红比例";
                }
                if(!/^[0-9]+.?[0-9]*$/.test(value)){
                    return "请填写正确分红比例";
                }
                if(value-four>0){
                    return "分红比例不能大于"+four+"%";
                }
            }


        });


        //监听提交
        form.on('submit(demo1)', function(data){
            var data = data.field;
            $.ajax({
                type: "POST",
                url: "/manager/add_agent_ajax",
                data: data,
                headers:{
                   "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.msg(res.msg);
                    if(res.code){
                        location.reload();
                    }
                }
            });
        });



    });
</script>

</body>

</html>