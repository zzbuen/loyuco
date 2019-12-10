<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>支付设置</title>
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
            font-weight: 100;
            color: #01AAED;
        }

        .main {
            margin-top: 25px;
        }

        .main .layui-row {
            margin: 10px 0;
        }
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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>支付设置</legend>
    </fieldset>
    <form id="uploadForm" style="margin-top: 20px;" class="layui-form layui-form-pane" >
        @foreach($data as $key=>$value)
            @if($value["key"]!="wx_qr" && $value["key"]!="zfb_qr")
                <div class="layui-form-item" style="margin-left: 40%">
                    <label class="layui-form-label" style="width: 15%">{{$value["name"]}}:</label>
                    <div class="layui-input-inline">
                        <input type="text" name="" value="{{$value["value"]}}" lay-verify="required" autocomplete="off" class="layui-input val{{$value["key"]}}">
                    </div>
                    <button data="{{$value["key"]}}" data2="{{$value["name"]}}" class="layui-btn change_shoukuan">确认修改</button>
                </div>
            @endif
        @endforeach
    </form>
    <div class="layui-row" style="text-align: center">
        <form id="img_form_weixin"  data="set_img_weixin_src"  class="layui-form layui-form-pane" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <button class="layui-btn set_qr" type="button" data="wx_qr" data2="set_img_weixin_src">设置微信</button><br/>
                    <div class="layui-form-item" style="text-align: center;margin-top: 10px">
                        <div class="layui-inline" >
                            <img src="{{$data[1]["value"]}}" class="robot_img" id="set_img_weixin_src" data="set_img_weixin" alt="微信收款码" title="微信收款码，点击更改" style="width: 20%!important;height: 20%!important;" >
                            <input style="display: none" type="file" data="img_form_weixin" id="set_img_weixin" name="img" autocomplete="off" class="layui-btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form id="img_form_zhifu" data="set_img_zhifu_src" class="layui-form layui-form-pane" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <button  type="button" class="layui-btn set_qr" data="zfb_qr" data2="set_img_zhifu_src">设置支付宝</button><br/>
                    <div class="layui-form-item" style="text-align: center;margin-top: 10px">
                        <div class="layui-inline" >
                            <img src="{{$data[3]["value"]}}" class="robot_img" id="set_img_zhifu_src" data="set_img_zhifu" alt="支付宝收款码" title="支付宝收款码，点击更改" style="width: 20%!important;height: 20%!important;">
                            <input style="display: none" type="file" data="img_form_zhifu" id="set_img_zhifu" name="img" autocomplete="off" class="layui-btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
        laydate = layui.laydate;

        laydate.render({
            elem: '#daily_start',
            type: 'time'
        });
        laydate.render({
            elem: '#daily_end',
            type: 'time'
        });
        @if ($errors->has('error'))
        alert(213);
        layer.msg("{{ $errors->first('error') }}");
        @endif

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
        $("#modify").click(function () {
            $("#uploadForm").trigger('submit');
        });
        $("#uploadForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url: "{{url('manager/system.index')}}",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if(data.flag){
                        layer.alert(data.msg, {icon: 1})
                    } else{
                        layer.alert(data.msg, {icon: 2})
                    }
                },
                error: function(){}
            });
        });



        /**
         * 作用：修改收款设置
         * 作者：信
         * 时间：2018/04/16
         * 修改：暂无
         */
        $(document).on("click",".change_shoukuan",function () {
            var id      = $(this).attr("data");
            var val     = $(".val"+id).val().replace(/\s+/g, "");
            var name    = $(this).attr("data2");
            var reg     = /^([1-9]{1})(\d{14}|\d{18})$/;

            if(val == ""){
                layer.alert("请输入"+name+"信息");
                return false;
            }
            if(id == 3){
                if(!reg.test(val)){
                    layer.alert("请输入正确的银行卡账号信息");
                    return false;
                }
            }


            layer.confirm("请您确认您需要修改的信息，"+name+"：</br>"+val+"",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/change_shoukuan",
                    data: {
                        "id":id,
                        "val":val,
                    },
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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



        /*点击图片调用选择文件按钮*/
        $(".robot_img").click(function () {
            var data = $(this).attr("data");
            $("#"+data).click();
        });

        /*选择文件后调用form*/
        $("#set_img_weixin,#set_img_zhifu").change(function () {
            if($(this).val()==""){
                return false;
            }
            var data = $(this).attr("data");
            $("#"+data).trigger('submit');
            return false;
        });




        /**
         * 作用：图片上传
         * 作者：信
         * 时间：2018/05/25
         */
        $("#img_form_weixin,#img_form_zhifu").on('submit', function(e){
            var data = $(this).attr("data");
            e.preventDefault();
            layer.load(1);
            $.ajax({
                type: "POST",
                data:  new FormData(this),
                url: "/manager/set_img",
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.code){
                        $("#"+data).attr("src",res.msg);
                    }else{
                        layer.msg(res.msg,{"icon":2})
                    }
                },
                error:function (err) {
                    layer.msg("头像选择失败,请确保文件小于8M,请再次尝试",{"icon":2})
                }
            });
            setTimeout(function(){
                layer.closeAll('loading');
            }, 2000);
        });





        $(".set_qr").click(function () {
            var key     = $(this).attr("data");
            var data    = $(this).attr("data2");
            var value   = $("#"+data).attr("src");
            layer.load(1);
            $.ajax({
                type: "POST",
                url: "/manager/change_shoukuan",
                data: {
                    "id":key,
                    "val":value,
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    layer.closeAll('loading');
                    if(res.code){
                        layer.msg(res.msg,{"icon":1});
                        location.reload();
                    }else{
                        layer.msg(res.msg,{"icon":2})
                    }
                },
                error:function (err) {
                    layer.closeAll('loading');
                    layer.msg("系统异常，修改失败",{"icon":2})
                }
            });
        })



    });

</script>

</body>

</html>