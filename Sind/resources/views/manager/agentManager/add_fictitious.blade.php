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
        <legend >{{Request::input('agent_user_id')}}添加虚拟账户</legend>
    </fieldset>
    <form style="margin-top: 20px;margin-left: 100px" class="layui-form layui-form-pane" action="{{url("manager/add_agent_form")}}">
        <div class="layui-form-item" style="margin-left: 30%;">
            <label  class="layui-form-label ">类型:</label>
            <div class="layui-input-inline">
                <input  lay-filter="leixing" type="radio" class="leixing" name="type" data="2" value="2" title="代理" checked="">
                <input  lay-filter="leixing" type="radio" class="leixing" name="type" data="1" value="1" title="会员" >
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
                <input  type="password" id="password" name="password" value="vip888" lay-verify="password" autocomplete="off" class="layui-input">
            </div>
            (默认：vip888)
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
                if(!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,12}$/.test(value)){
                    return "密码格式6-12位字母数字组合格式除特殊字符";
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
            }

        });


        /*类型选择*/
        form.on('radio(leixing)', function(data){
            if(data.value == 2){
                $("#daili_show").show();
            }
            if(data.value == 1){
                $("#daili_show").hide();
            }
        });

        /*分红选择*/
        form.on('radio(fenhon)', function(data){
            if(data.value == 2){
                $("#fenhon_show").show();
            }
            if(data.value == 1){
                $("#fenhon_show").hide();
            }
        });

        /*工资选择*/
        form.on('radio(gongzi)', function(data){
            if(data.value == 2){
                $("#gongzi_show").show();
            }
            if(data.value == 1){
                $("#gongzi_show").hide();
            }
        });



        //监听提交
        form.on('submit(demo1)', function(data){
            var data = data.field;
            // var rigongzi_set = $("input[name='rigongzi']:checked").val();
            // var fenhon_set   = $("input[name='fenhon']:checked").val();
            // var leixing      = $("input[name='type']:checked").val();
            // /*日工资所有参数*/
            // var amount_day  = $("#amount_day").val().replace(/(^\s*)|(\s*$)/g,"");
            // var amount_num  = $("#amount_num").val().replace(/(^\s*)|(\s*$)/g,"");
            // var amount_money = $("#amount_money").val().replace(/(^\s*)|(\s*$)/g,"");
            // var wage_ratio   = $("#wage_ratio").val().replace(/(^\s*)|(\s*$)/g,"");
            // /*分红所有参数*/
            // var total  = $("#total").val().replace(/(^\s*)|(\s*$)/g,"");
            // var amount_num_fenhon  = $("#amount_num_fenhon").val().replace(/(^\s*)|(\s*$)/g,"");
            // var amount_money_fenhon = $("#amount_money_fenhon").val().replace(/(^\s*)|(\s*$)/g,"");
            // var bonus_ratio   = $("#bonus_ratio").val().replace(/(^\s*)|(\s*$)/g,"");
            //
            //
            // if(leixing == 2){
            //     /*日工资限制*/
            //     if(rigongzi_set == 2){
            //         /*日量*/
            //         if(amount_day<0 || amount_day==""){
            //             layer.msg("日工资日量不可小于0");
            //             return false;
            //         }
            //         /*活跃人数*/
            //         if(amount_num<0 || amount_num==""){
            //             layer.msg("日工资活跃人数不可小于0");
            //             return false;
            //         }
            //         /*活跃金额*/
            //         if(amount_money<0 || amount_money==""){
            //             layer.msg("日工资活跃金额不可小于0");
            //             return false;
            //         }
            //         /*日工资比例*/
            //         if(!/^[0-9]+.?[0-9]*$/.test(wage_ratio)){
            //             layer.msg("请填写正确日工资比例");
            //             return false;
            //         }
            //         if(wage_ratio<0 || wage_ratio==""){
            //             layer.msg("日工资比例不可小于0");
            //             return false;
            //         }
            //         if(wage_ratio-three>0){
            //             layer.msg("日工资比例不能大于"+three+"%");
            //             return false;
            //         }
            //     }
            //
            //
            //
            //     /*分红限制*/
            //     if(fenhon_set == 2){
            //         /*分红日量*/
            //         if(total<0 || total==""){
            //             layer.msg("分红总亏损不可小于0");
            //             return false;
            //         }
            //         /*分红活跃人数*/
            //         if(amount_num_fenhon<0 || amount_num_fenhon==""){
            //             layer.msg("分红活跃人数不可小于0");
            //             return false;
            //         }
            //         /*分红活跃金额*/
            //         if(amount_money_fenhon<0 || amount_money_fenhon==""){
            //             layer.msg("分红活跃金额不可小于0");
            //             return false;
            //         }
            //         /*分红比例*/
            //         if(!/^[0-9]+.?[0-9]*$/.test(bonus_ratio)){
            //             layer.msg("请填写正确分红比例");
            //             return false;
            //         }
            //         if(bonus_ratio<0 || bonus_ratio==""){
            //             layer.msg("分红比例不可小于0");
            //             return false;
            //         }
            //         if(bonus_ratio-four>0){
            //             layer.msg("分红比例不能大于"+four+"%");
            //             return false;
            //         }
            //     }
            // }
            //

            $.ajax({
                type: "POST",
                url: "/manager/add_fictitious_ajax",
                data: data,
                headers:{
                   "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.alert(res.msg,function (index) {
                        if(res.code){
                            var username = $("#username").val();
                            var password = $("#password").val();
                            var oInput = document.createElement('input');
                            oInput.value = "账号："+username+'   '+"密码："+password;
                            document.body.appendChild(oInput);
                            oInput.select();
                            document.execCommand("Copy");
                            oInput.style.display='none';
                            layer.msg("复制成功",function () {
                                location.reload();
                            });
                        }else{
                            layer.close(index)
                        }
                    });
                }
            });
        });



    });
</script>

</body>

</html>