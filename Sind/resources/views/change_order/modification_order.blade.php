<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/plugins/font-awesome/css/font-awesome.css">
    <style>
        .info-box {
            height: 85px;
            background-color: white;
            background-color: #ecf0f5;
        }

        .info-box .info-box-icon {
            border-top-left-radius: 2px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 2px;
            display: block;
            float: left;
            height: 85px;
            width: 85px;
            text-align: center;
            font-size: 45px;
            line-height: 85px;
            background: rgba(0, 0, 0, 0.2);
        }

        .info-box .info-box-content {
            padding: 5px 10px;
            margin-left: 85px;
        }

        .info-box .info-box-content .info-box-text {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
        }

        .info-box .info-box-content .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 18px;
        }

        .major {
            font-weight: 10px;
            color: #01AAED;
        }

        .main {
            margin: 25px 0;
        }

        /*.main .layui-row {*/
            /*margin: 10px 0;*/
        /*}*/
    </style>
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
        width: 91%;
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
    .add_div{
        height: 39px;
        line-height: 39px;
        width: 99%;
        float: right;
        margin-right: 13px;
    }
    .text_div{
        height: 30px;
        width: 80px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
    .message_div {
        margin-top: 20px;
        margin-left: 30px;
        font-size: 18px;
    }
    .layui-form-label{
        width: 20%;

    }
    .layui-input-inline{
        width: 30%;
    }
    .layui-disabled, .layui-disabled:hover {
        color: gray!important;
        cursor: not-allowed!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <form style="margin-top: 20px;" class="layui-form layui-form-pane" action="">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>用户{{$data['user']['username']}} - 修改订单信息 </legend>
            <input type="hidden" id="user_id" value="{{$data['user']['username']}}">
        </fieldset>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">订单号</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" id="username" value="{{$data["order_id"]}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">期号</label>
                        <div class="layui-input-inline">
                            <input  name="user_gold" disabled type="text" autocomplete="off" value="{{$data["bet_period"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">游戏名称</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$data["odds"]["gameName"]}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">倍数</label>
                        <div class="layui-input-inline">
                            <input  name="user_gold" disabled id="change_beishu"  type="text" autocomplete="off" value="{{$data["beiShu"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">玩法</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$data["odds"]["category"]}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">投注数</label>
                        <div class="layui-input-inline">
                            <input  name="user_gold" disabled type="text"  autocomplete="off" value="{{$data["bet_num"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">子玩法</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$data["odds"]["ruleName"]}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">返点</label>
                        <div class="layui-input-inline">
                            <input name="user_gold"  disabled id="change_email" type="text" autocomplete="off" value="{{$data["fanDian"]}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">投注内容</label>
            <div class="layui-input-block">
                <textarea placeholder="请输入投注内容" class="layui-textarea" id="touzhu_val">{{$data["bet_value"]}}</textarea>
            </div>
        </div>

        <div style="width: 100%;text-align: center">
            <button type="button" class="layui-btn sure_change" data="{{$data["id"]}}">确认修改</button>
        </div>

    </form>
</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;

        /**
         * 作用：修改倍数
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".sure_change").click(function () {
            var touzhu_val      = $("#touzhu_val").val().replace(/(^\s*)|(\s*$)/g,"");
            var username    = $("#user_id").val();
            var id          = $(this).attr("data");
            touzhu_val  = touzhu_val.replace("，",",");
            var str = "确认修改用户名为【"+username+"】此用户的投注内容为【"+touzhu_val+"】么？请仔细检查修改内容是否无误，如若修改内容较大可能会导致和之前投注内容信息不一致！【请谨慎操作】";
            layer.confirm(str,function () {
                layer.confirm("请再次确认修改信息确保信息无误！您修改的内容为【"+touzhu_val+"】【请谨慎操作】",function () {
                    layer.load(1);
                    $.ajax({
                        type: "POST",
                        url: "/manager/change_order_betvalue",
                        data: {"id":id,"bet_value":touzhu_val},
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(res){
                            layer.closeAll("loading");
                            if(res.code) {
                                layer.msg(res.msg, {icon: 1},function(){
                                    location.reload();
                                });
                            }else{
                                layer.msg(res.msg, {icon: 2});
                            }
                        },
                        error:function (err) {
                            layer.closeAll("loading");
                            layer.msg("系统异常，请稍后再试", {icon: 2});
                        }
                    });
                });
            })
        });



    });

</script>

</body>

</html>