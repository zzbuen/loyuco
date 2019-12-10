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
    <form class="layui-form layui-form-pane" action="" style=" margin-top: 10%">
        <div class="layui-form-item juzhon">
            <button  type="button" class="layui-btn orders" data="{{$data["user"]["username"]}}">查看所有订单</button>
            <button  type="button" class="layui-btn betList" data="{{$data["user"]["username"]}}">查看投注记录</button>
            <button  type="button" class="layui-btn moneyList" data="{{$data["user"]["username"]}}">查看资金记录</button>

        </div>

        <div class="layui-form-item juzhon">
            <label class="layui-form-label">用户账号</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["user"]["username"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行类型</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["bank_name"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button  type="button" class="layui-btn fuzhi" data="{{$data["bank_name"]}}">复制</button>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">用户姓名</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["bank_account_name"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button type="button" class="layui-btn fuzhi" data="{{$data["bank_account_name"]}}">复制</button>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">银行账号</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["bank_account"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button type="button" class="layui-btn fuzhi" data="{{$data["bank_account"]}}">复制</button>
        </div>
        <div class="layui-form-item juzhon">
            <label class="layui-form-label">提现金额</label>
            <div class="layui-input-inline">
                <input type="text" id="money" name="source_name" disabled="disabled" value="{{$data["trade_amount"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
            <button type="button" class="layui-btn fuzhi" data="{{$data["trade_amount"]}}">复制</button>
        </div>
        <div class="layui-form-item juzhon">
            <div class="layui-input-block" style="margin-left: 0px">
                <input type="radio" name="choose" value="1" title="提取成功(扣除冻结款)" checked="">
            </div>
        </div>
        <div class="layui-form-item juzhon">
            <div class="layui-input-block" style="margin-left: 0px">
                <input type="radio" name="choose" value="2" title="提取失败(返还冻结款)">
            </div>
        </div>
        <div class="layui-form-item" style="text-align: center">
            <input type="hidden" id="user_id" value="{{$data["user_id"]}}">
            <input type="hidden" id="chuli_id" value="{{$data["id"]}}">
            <input type="hidden" id="trade_sn" value="{{$data["trade_sn"]}}">
        </div>
    </form>
    <div style="width: 100%;text-align: center">
        <button class="layui-btn" type="button" id="sure">确定</button>
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
                async:false,
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
            var trade_sn = $("#trade_sn").val();
            var radio = $("input[name='choose']:checked").val();

            if(radio=="2"){
                layer.prompt({title: '请填写拒绝备注，并确认', formType: 2}, function(text, index){
                    var index = layer.load(1, {
                        shade: [0.1,'#fff'] //0.1透明度的白色背景
                    });
                    $.ajax({
                        type: "POST",
                        url: "/manager/chuli_ajax",
                        data: {"money":money,"radio":radio,"user_id":user_id,"chuli_id":chuli_id,"beizhu":text,'trade_sn':trade_sn},
                        headers:{
                            "X-CSRF-TOKEN":"{{ csrf_token()}}"
                        },
                        async:false,
                        success: function(res){
                            layer.close(index);
                            layer.msg(res.msg);
                            if(res.code){
                                parent.location.reload();
                            }
                        },
                        error:function (err) {
                            layer.close(index);
                            layer.msg("系统异常，请稍后再试")
                        }
                    });
                });
            }else{
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                $.ajax({
                    type: "POST",
                    url: "/manager/chuli_ajax",
                    data: {"money":money,"radio":radio,"user_id":user_id,"chuli_id":chuli_id,"beizhu":"提现成功",'trade_sn':trade_sn},
                    headers:{
                        "X-CSRF-TOKEN":"{{ csrf_token()}}"
                    },
                    async:false,
                    success: function(res){
                        layer.close(index);
                        layer.msg(res.msg);
                        if(res.code){
                            parent.location.reload();
                        }
                    },
                    error:function (err) {
                        layer.close(index);
                        layer.msg("系统异常，请稍后再试")
                    }
                });
            }
        })
        /**
         * 作用：处理
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click",".orders",function () {
            var data = $(this).attr("data");
            var url = "{{ url('manager/user_shenghe')}}";
            url     = url+"?username="+data;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'查看所有订单',
                    shadeClose:true,
                    shade:0,
                    area:['90%','100%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        });
        $(document).on("click",".betList",function () {
            var data = $(this).attr("data");
            var url = "{{ url('manager/user_betOrder_shenghe')}}";
            url     = url+"?user_id="+data;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'查看投注记录',
                    shadeClose:true,
                    shade:0,
                    area:['90%','100%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        })
        $(document).on("click",".moneyList",function () {
            var data = $(this).attr("data");
            var url = "{{ url('manager/user_moneyList_shenghe')}}";
            url     = url+"?user_id="+data;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'查看资金记录',
                    shadeClose:true,
                    shade:0,
                    area:['90%','100%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        })
    });

</script>

</body>

</html>