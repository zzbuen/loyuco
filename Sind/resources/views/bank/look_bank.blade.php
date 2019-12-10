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
        <legend>{{$data["username"]}} - 银行卡{{$number[$num]}}</legend>
        <input type="hidden" id="username" value="{{$data["username"]}}">
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行类型</label>
            <div class="layui-input-inline">
                <select class="layui-select" id="bank">
                    @foreach($bank as $key=>$value)
                        <option <?php if($value["id"]==$data["bank_id"])echo 'selected=""' ?> value="{{$value["id"]}}">{{$value["bank_name"]}}</option>
                    @endforeach
                </select>
            </div>
            <button  type="button" class="layui-btn fuzhi" data="{{$data["bank_name"]}}">复制</button>
            <button  type="button" id="leixing" class="layui-btn layui-btn-primary" data_name="{{$data["bank_name"]}}" data="{{$data["bank_id"]}}">修改</button>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行卡号</label>
            <div class="layui-input-inline">
                <input id="kahao" type="text" name="source_name"  value="{{$data["account"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button type="button" class="layui-btn fuzhi" data="{{$data["account"]}}">复制</button>
            <button  type="button" id="midification_account" class="layui-btn layui-btn-primary" data="{{$data["account"]}}">修改</button>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">真实姓名</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["name"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button type="button" class="layui-btn fuzhi" data="{{$data["name"]}}">复制</button>
            <button  title="禁止修改" type="button" class="layui-btn layui-btn-primary layui-disabled" data="{{$data["bank_name"]}}">修改</button>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行分支</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["bank_branch"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button type="button" class="layui-btn fuzhi" data="{{$data["bank_branch"]}}">复制</button>
            <button  title="禁止修改" type="button" class="layui-btn layui-btn-primary layui-disabled" data="{{$data["bank_name"]}}">修改</button>
        </div>

        <div class="layui-form-item" style="text-align: center">
            <input type="hidden" id="user_id" value="{{$data["user_id"]}}">
            <input type="hidden" id="bank_id" value="{{$data["id"]}}">
        </div>
    </form>
    <div style="width: 100%;text-align: center;margin-top: 5%">
        {{--<button class="layui-btn" type="button" id="sure">确定</button>--}}
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





        /**
         * 作用：修改银行类型
         * 作者：信
         * 时间：2018/06/15
         * 修改：暂无
         */
        $(document).on("click","#leixing",function () {
            /*原类型名和id*/
            var leixing_name= $(this).attr("data_name");
            var leixing_id  = $(this).attr("data");
            /*新类型名和id*/
            var leixing     = $("#bank").val();
            var leixingname = $("#bank").find("option:selected").text();
            var username    = $("#username").val();
            var user_id     = $("#user_id").val();
            var bank_id     = $("#bank_id").val();
            var str         = "确认将用户【"+username+"】的此银行卡类型【"+leixing_name+"】更改为【"+leixingname+"】吗？";
            if(leixing == leixing_id){
                layer.msg("银行类型未作修改",{"icon":2});
                return false;
            }
            layer.confirm(str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/midification_leixing",
                    data: {"leixing":leixing,"bank_id":bank_id,"user_id":user_id},
                    headers:{
                        "X-CSRF-TOKEN":"{{ csrf_token()}}"
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code){
                            layer.msg(res.msg,{"icon":1});
                            location.reload();
                        }else{
                            layer.msg(res.msg,{"icon":2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试")
                    }
                });
            });
        });








        /**
         * 作用：修改银行卡号信息
         * 作者：信
         * 时间：2018/06/15
         * 修改：暂无
         */
        $(document).on("click","#midification_account",function () {
            var yuan        = $(this).attr("data");
            var kahao       = $("#kahao").val().replace(/\s+/g, "");
            var bank_id     = $("#bank_id").val();
            var username    = $("#username").val();
            var user_id     = $("#user_id").val();
            var reg = /^([1-9]{1})(\d{14}|\d{18})$/;
            var str = "确认将用户【"+username+"】银行卡号为【"+yuan+"】的卡号信息修改为【"+kahao+"】？";
            var str2 = "请再次确认新卡号信息【"+kahao+"】【请谨慎操作】";
            if(!reg.test(kahao)){
                layer.msg("卡号信息格式不正确，请重新输入",{"icon":2});
                return false;
            }
            if(kahao == yuan){
                layer.msg("卡号未作修改",{"icon":2});
                return false;
            }
            layer.confirm(str,function () {
                layer.confirm(str2,function () {
                    layer.load(1);
                    $.ajax({
                        type: "POST",
                        url: "/manager/midification_account",
                        data: {"kahao":kahao,"bank_id":bank_id,"user_id":user_id},
                        headers:{
                            "X-CSRF-TOKEN":"{{ csrf_token()}}"
                        },
                        success: function(res){
                            layer.closeAll("loading");
                            if(res.code){
                                layer.msg(res.msg,{"icon":1});
                                location.reload();
                            }else{
                                layer.msg(res.msg,{"icon":2});
                            }
                        },
                        error:function (err) {
                            layer.closeAll("loading");
                            layer.msg("系统异常，请稍后再试")
                        }
                    });
                });
            });
        });





        /*复制功能*/
        $(document).on("click",".fuzhi",function () {
            var data = $(this).attr("data");
            var oInput = document.createElement('input');
            oInput.value = data;
            document.body.appendChild(oInput);
            oInput.select();
            document.execCommand("Copy");
            oInput.style.display='none';
            layer.msg("复制成功");
        });


        /*处理提现请求*/
        $(document).on("click","#sure",function () {
            var money = $("#money").val();
            var user_id = $("#user_id").val();
            var chuli_id = $("#chuli_id").val();
            var radio = $("input[name='choose']:checked").val();
            if(radio=="2"){
                layer.prompt({title: '请填写拒绝备注，并确认', formType: 2}, function(text, index){
                    $.ajax({
                        type: "POST",
                        url: "/manager/chuli_ajax",
                        data: {"money":money,"radio":radio,"user_id":user_id,"chuli_id":chuli_id,"beizhu":text},
                        headers:{
                            "X-CSRF-TOKEN":"{{ csrf_token()}}"
                        },
                        success: function(res){
                            layer.msg(res.msg);
                            if(res.code){
                                parent.location.reload();
                            }
                        },
                        error:function (err) {
                            layer.msg("系统异常，请稍后再试")
                        }
                    });
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: "/manager/chuli_ajax",
                    data: {"money":money,"radio":radio,"user_id":user_id,"chuli_id":chuli_id,"beizhu":"提成成功"},
                    headers:{
                        "X-CSRF-TOKEN":"{{ csrf_token()}}"
                    },
                    success: function(res){
                        layer.msg(res.msg);
                        if(res.code){
                            parent.location.reload();
                        }
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试")
                    }
                });
            }
        })

    });

</script>

</body>

</html>