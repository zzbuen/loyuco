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
            <div class="layui-input-inline" style="width: 300px;">
                <input  lay-filter="leixing" type="radio" class="leixing" name="type" data="2" value="2" title="二级代理" checked="">
                <input  lay-filter="leixing" type="radio" class="leixing" name="type" data="1" value="1" title="会员" >
                <input  lay-filter="leixing" type="radio" class="leixing" name="type" data="3" value="3" title="虚拟账号" >
                <input  lay-filter="leixing" type="radio" class="leixing" name="type" data="4" value="4" title="一级代理">
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


        });


        //监听提交
        form.on('submit(demo1)', function(data){
            var data = data.field;
            var leixing      = $("input[name='type']:checked").val();

            $.ajax({
                type: "POST",
                url: "/manager/add_agent_ajax",
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