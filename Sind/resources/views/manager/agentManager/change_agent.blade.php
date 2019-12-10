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
        <legend >{{Request::input('agent_user_id')}}修改代理信息</legend>
    </fieldset>
    <form style="margin-top: 20px;margin-left: 100px" class="layui-form layui-form-pane" action="{{url("manager/add_agent_form")}}">
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">用户名:</label>
            <div class="layui-input-inline">
                <input  disabled type="hidden" id="user_id" name="user_id" value="{{$fandian_set["user_id"]}}" lay-verify="username" autocomplete="off" class="layui-input">
                <input  disabled type="text" id="user_id" name="" value="{{$user_name["username"]}}" lay-verify="username" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">高频彩返点:</label>
            <div class="layui-input-inline">
                <input type="hidden" id="fandain"  value="{{$fandian_set["fanDian"]}}">
                <input  type="text" name="fandian" value="{{$fandian_set["fanDian"]}}" lay-verify="fandian" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="margin-left: 30%;">
            <label class="layui-form-label">六合彩返点:</label>
            <div class="layui-input-inline">
                <input type="hidden" id="befandain"  value="{{$fandian_set["bFanDian"]}}">
                <input  type="text" name="befandain" value="{{$fandian_set["bFanDian"]}}" lay-verify="befandain" autocomplete="off" class="layui-input">
            </div>
        </div>
        {{--<div class="layui-form-item">--}}
            {{--<label class="layui-form-label">日工资:</label>--}}
        {{--</div>--}}
        {{--<div class="layui-form-item">--}}
            {{--<label class="layui-form-label">日量</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="amount_day"  value="{{$wage["amount_day"]}}">--}}
                {{--<input  type="number" name="amount_day" value="{{$wage["amount_day"]}}" lay-verify="amount_day" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">活跃人数</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="amount_num"  value="{{$wage["amount_num"]}}">--}}
                {{--<input  type="number" name="amount_num" value="{{$wage["amount_num"]}}" lay-verify="amount_num" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">活跃金额</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="amount_money"  value="{{$wage["amount_money"]}}">--}}
                {{--<input  type="number" name="amount_money" value="{{$wage["amount_money"]}}" lay-verify="amount_money" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">日工资比例</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="wage_ratio"  value="{{$wage["wage_ratio"]}}">--}}
                {{--<input  type="text" name="wage_ratio" value="{{$wage["wage_ratio"]}}" lay-verify="wage_ratio" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="layui-form-item">--}}
            {{--<label class="layui-form-label">分红:</label>--}}
        {{--</div>--}}
        {{--<div class="layui-form-item">--}}
            {{--<label class="layui-form-label">总亏损</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="total"  value="{{$bonus["total"]}}">--}}
                {{--<input  type="number" name="total" value="{{$bonus["total"]}}" lay-verify="total" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">活跃人数</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="amount_num_fenhon"  value="{{$bonus["amount_num"]}}">--}}
                {{--<input  type="number" name="amount_num_fenhon" value="{{$bonus["amount_num"]}}" lay-verify="amount_num_fenhon" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">活跃金额</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="amount_money_fenhon"  value="{{$bonus["amount_money"]}}">--}}
                {{--<input  type="number" name="amount_money_fenhon" value="{{$bonus["amount_money"]}}" lay-verify="amount_money_fenhon" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
            {{--<label class="layui-form-label">分红比例</label>--}}
            {{--<div class="layui-input-inline">--}}
                {{--<input type="hidden" id="bonus_ratio"  value="{{$bonus["bonus_ratio"]}}">--}}
                {{--<input  type="text" name="bonus_ratio" value="{{$bonus["bonus_ratio"]}}" lay-verify="bonus_ratio" autocomplete="off" class="layui-input">--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="layui-form-item">--}}
            {{--<label class="layui-form-label">分红策略:</label>--}}
            {{--<div class="layui-input-inline" style="width: 500px">--}}
                {{--<input type="radio" name="bonus_type" value="1" title="半月不累计"  >--}}
                {{--<input type="radio" name="bonus_type" value="2" title="半月累计"  >--}}
            {{--</div>--}}
        {{--</div>--}}
        <input type="button" lay-submit="" lay-filter="demo1" id="sub" style="margin-left: 35%" class="layui-btn" value="确认添加">
    </form>
</div>
<input type="hidden" id="one" value="{{$data[6]["value"]}}">
<input type="hidden" id="two" value="{{$data[7]["value"]}}">
<input type="hidden" id="three" value="{{$data[8]["value"]}}">
<input type="hidden" id="four" value="{{$data[9]["value"]}}">
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

        /*反点*/
        var fandian     = $("#fandain").val();
        var befandian   = $("#befandain").val();

        /*日工资*/
        // var amount_day      = $("#amount_day").val();
        // var amount_num      = $("#amount_num").val();
        // var amount_money    = $("#amount_money").val();
        // var wage_ratio      = $("#wage_ratio").val();

        /*分红*/
        // var total               = $("#total").val();
        // var amount_num_fenhon   = $("#amount_num_fenhon").val();
        // var amount_money_fenhon = $("#amount_money_fenhon").val();
        // var bonus_ratio         = $("#bonus_ratio").val();

        form.verify({
            fandian:function (value) {
                value = value.replace(/\s+/g, "");
                if(value==""){
                    return "请填写高频彩返点";
                }
                if(value-one>0){
                    return "高频彩最高百分点不能大于"+one+"%";
                }
                if(value-fandian<0){
                    return "高频彩百分点不能小于原先的百分点"+fandian+"%";
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
                if(value-befandian<0){
                    return "六合彩百分点不能小于原先的百分点"+befandian+"%";
                }
            },
            // amount_day:function (value) {
            //     if(value == ""){
            //         return "请填写日工资日量";
            //     }
            //     if(value<0){
            //         return "日工资日量不能小于0";
            //     }
            //     if(value-amount_day<0){
            //         return "日工资日量不能小于原先的日量"+amount_day+"";
            //     }
            // },
            // total:function (value) {
            //     if(value == ""){
            //         return "请填写分红总亏损";
            //     }
            //     if(value<0){
            //         return "分红总亏损不能小于0";
            //     }
            //     if(value-total<0){
            //         return "分红总亏损不能小于原先的总亏损数值"+total+"";
            //     }
            //
            // },
            // amount_num:function (value) {
            //     if(value == ""){
            //         return "请填写日工资活跃人数";
            //     }
            //     if(value<0){
            //         return "日工资活跃人数不能小于0";
            //     }
            //     if(value-amount_num<0){
            //         return "日工资活跃人数不能小于原先的活跃人数"+amount_num+"";
            //     }
            // },
            // amount_num_fenhon:function (value) {
            //     if(value == ""){
            //         return "请填写分红活跃人数";
            //     }
            //     if(value<0){
            //         return "分红活跃人数不能小于0";
            //     }
            //     if(value-amount_num_fenhon<0){
            //         return "分红活跃人数不能小于原先的活跃人数"+amount_num_fenhon+"";
            //     }
            // },
            // amount_money:function (value) {
            //     if(value == ""){
            //         return "请填写日工资活跃金额";
            //     }
            //     if(value<0){
            //         return "日工资活跃金额不能小于0";
            //     }
            //     if(value-amount_money<0){
            //         return "日工资活跃金额不能小于原先的活跃金额"+amount_money+"";
            //     }
            // },
            // amount_money_fenhon:function (value) {
            //     if(value == ""){
            //         return "请填写分红活跃金额";
            //     }
            //     if(value<0){
            //         return "分红活跃金额不能小于0";
            //     }
            //     if(value-amount_money_fenhon<0){
            //         return "分红活跃金额不能小于原先的活跃金额"+amount_money_fenhon+"";
            //     }
            // },
            // wage_ratio:function (value) {
            //     value = value.replace(/\s+/g, "");
            //     if(value==""){
            //         return "请填写日工资比例";
            //     }
            //     if(!/^[0-9]+.?[0-9]*$/.test(value)){
            //         return "请填写正确日工资比例";
            //     }
            //     if(value-three>0){
            //         return "日工资比例不能大于"+three+"%";
            //     }
            //     if(value-wage_ratio<0){
            //         return "日工资比例不能小于原先的日工资比例"+wage_ratio+"";
            //     }
            // },
            // bonus_ratio:function (value) {
            //     value = value.replace(/\s+/g, "");
            //     if(value==""){
            //         return "请填写分红比例";
            //     }
            //     if(!/^[0-9]+.?[0-9]*$/.test(value)){
            //         return "请填写正确分红比例";
            //     }
            //     if(value-four>0){
            //         return "分红比例不能大于"+four+"%";
            //     }
            //     if(value-bonus_ratio<0){
            //         return "分红比例不能小于原先的分红比例"+bonus_ratio+"";
            //     }
            // }


        });


        //监听提交
        form.on('submit(demo1)', function(data){
            var data = data.field;
            $.ajax({
                type: "POST",
                url: "/manager/change_agent_ajax",
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