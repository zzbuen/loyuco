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
        <legend>发送消息</legend>
    </fieldset>
    <div class="layui-form">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-inline">
                <input  placeholder="请输入标题" id="biaoti" value="" type="text" name="username" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea  id="neiron" name="title" placeholder="请输入内容" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">接收者</label>
            <div class="layui-input-block">
                <ul>
                    <li>
                        <button class="layui-btn layui-btn-mini" id="quanxuan">全选</button>
                        <button class="layui-btn layui-btn-mini" id="fanxuan">反选</button>
                        <button class="layui-btn layui-btn-mini layui-btn-warm" id="qingkong">清空选择</button>
                    </li>
                    @foreach($data as $key=>$value)
                        <li style="width: 20%;float: left">
                            <input class="username" value="{{$value}}" type="checkbox" name="like[]" lay-skin="primary" title="{{$value}}">
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <button style="margin-left: 10%" class="layui-btn" id="sure_fason">确认发送</button>

</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_begin' //指定元素
            });
        });
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_end' //指定元素
            });
        });

        $("#quanxuan").click(function () {
            $(".username").prop('checked', true);
            form.render();
        });

        /*反选*/
        $("#fanxuan").click(function () {
            $checkbox  = $(".username");
            for(var i=0;i<$checkbox.length;i++){
                 $flag = $($checkbox[i]).prop('checked');
                 if($flag){
                     $($checkbox[i]).prop('checked', "");
                 }else{
                     $($checkbox[i]).prop('checked', true);
                 }
                form.render();
            }

        });


        /*清空选择*/
        $("#qingkong").click(function () {
            $(".username").prop('checked', "");
            form.render();
        });






        /*
        * 作用：发送消息
        * 作者：信
        * 修改：暂无
        * 时间：2018/04/12
        * */
        $(document).on("click","#sure_fason",function () {
            var $biaoti = $("#biaoti").val().replace(/(^\s*)|(\s*$)/g,"");
            var $neiron = $("#neiron").val().replace(/(^\s*)|(\s*$)/g,"");
            if($biaoti=="" || $neiron==""){
                layer.msg("请将信息填写完整",{"icon":2})
                return false;
            }
            var $length =  $('input:checked').length;
            if($length==0){
                layer.msg("请添加接收者",{"icon":2})
                return false;
            }
            $checkbox  = $(".username");
            $str = "";
            for(var i=0;i<$checkbox.length;i++){
                $flag = $($checkbox[i]).prop('checked');
                if($flag){
                    $val = $($checkbox[i]).val();
                    $str+=","+$val;
                }
            }
            layer.confirm("请确认消息内容是否无误？",function () {
                var index = layer.load(1, {
                    shade: [0.1,'#fff']
                });
                $.ajax({
                    type: "POST",
                    url: "{{url('manager/send_xiaoxi_ajax')}}" ,
                    data: {
                        "biaoti":$biaoti,
                        "neiron":$neiron,
                        "str":$str
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code){
                            layer.close(index);
                            layer.msg(res.msg,{"icon":1});
                            location.reload();
                        }else{
                            layer.close(index);
                            layer.msg(res.msg,{"icon":2});
                        }
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试")
                    },beforeSend: function() {
                        layer.msg("消息发送中");
                    }
                });
            });
        });





    });
</script>

</body>

</html>