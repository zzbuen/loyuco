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

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑通道</legend>
    </fieldset>
    <div class="layui-row" style="text-align: center">
        <form id="img_form_weixin"  data="set_img_weixin_src"  class="layui-form layui-form-pane" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <button class="layui-btn set_qr" type="button" data="icon" data2="set_img_weixin_src">设置icon</button><br/>
                    <div class="layui-form-item" style="text-align: center;margin-top: 10px">
                        <div class="layui-inline" >
                            <img src="{{$newrecharge["icon"]}}" class="robot_img" id="set_img_weixin_src" data="set_img_weixin" alt="icon"  placeholder="icon，点击更改" title="icon，点击更改" style="width: 20%!important;height: 20%!important;" >
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
                    <button  type="button" class="layui-btn set_qr" data="img" data2="set_img_zhifu_src">设置收款码</button><br/>
                    <div class="layui-form-item" style="text-align: center;margin-top: 10px">
                        <div class="layui-inline" >
                            <img src="{{$newrecharge["pay_img"]}}" class="robot_img" id="set_img_zhifu_src" data="set_img_zhifu" alt="收款码" title="收款码，点击更改" style="width: 20%!important;height: 20%!important;">
                            <input style="display: none" type="file" data="img_form_zhifu" id="set_img_zhifu" name="img" autocomplete="off" class="layui-btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <form class="layui-form layui-form-pane" action="">
        {{ csrf_field() }}
        <input id="re_id" type="hidden" name="id" value="{{ $newrecharge['id'] }}">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="{{ $newrecharge['name'] }}" placeholder="名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">开户行</label>
            <div class="layui-input-block">
                <input type="text" name="orgain_bank" value="{{ $newrecharge['orgain_bank'] }}"  placeholder="开户行" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">银行名</label>
            <div class="layui-input-block">
                <input type="text" name="bank_name" value="{{ $newrecharge['bank_name'] }}"  placeholder="卡号" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">卡号</label>
            <div class="layui-input-block">
                <input type="text" name="bank_id" value="{{ $newrecharge['bank_id'] }}" placeholder="卡号" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">持卡人</label>
            <div class="layui-input-block">
                <input type="text" name="bank_username" value="{{ $newrecharge['bank_username'] }}"  placeholder="持卡人" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <input type="text" name="remary" value="{{ $newrecharge['remary'] }}" lay-verify="required" placeholder="备注" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">最小金额</label>
            <div class="layui-input-block">
                <input type="text" name="min" value="{{ $newrecharge['min'] }}" lay-verify="required" placeholder="最小金额" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">最大金额</label>
            <div class="layui-input-block">
                <input type="text" name="max" value="{{ $newrecharge['max'] }}" lay-verify="required" placeholder="最大金额" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付类型</label>
            <div class="layui-input-block">
                <select class="layui-select" id="leixing" name="leixing">
                    <option value=""  <?php if($newrecharge['type']=="")echo 'selected=""'; ?> >全部</option>
                    <option value="1" <?php if($newrecharge['type']=="1")echo 'selected=""'; ?> >银联</option>
                    <option value="2" <?php if($newrecharge['type']=="2")echo 'selected=""'; ?> >支付宝</option>
                    <option value="3" <?php if($newrecharge['type']=="3")echo 'selected=""'; ?> >微信</option>
                </select>
                </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input type="number" name="order" value="{{ $newrecharge['order'] }}" lay-verify="required" placeholder="排序" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" pane="">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" {{ $newrecharge['status']==1?'checked':'' }} name="status" lay-skin="switch" lay-filter="switchTest" title="开关" lay-text="开放|关闭">
            </div>
        </div>
        <div class="layui-form-item" pane="">
            <label class="layui-form-label">是否推荐</label>
            <div class="layui-input-block">
                <input type="checkbox" {{ $newrecharge['is_hot']==1?'checked':'' }} name="is_hot" lay-skin="switch" lay-filter="switchTest" title="开关" lay-text="开放|关闭">
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">提交</button>
        </div>
    </form>

</div>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
                layer = layui.layer,
                $ = layui.jquery,
                laydate = layui.laydate;

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
        //日期时间选择器
        laydate.render({
            elem: '#test5'
            ,type: 'datetime'
        });

        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });

            console.log(data.field);
            $.ajax({
                type: "POST",
                url: "{{ route('manager.recharge_edit.index') }}",
                data: data.field,
                dataType: "json",
                success: function(res){
                    layer.close(index);
                    layer.msg(res.msg, {time:1100});
                    if (res.success) {
                        parent.location.reload();
                    }
                }
            });
            return false;
        });
        $(".set_qr").click(function () {
            var key     = $(this).attr("data");
            var re_id     = $("#re_id").val();
            var data    = $(this).attr("data2");
            var value   = $("#"+data).attr("src");
            layer.load(1);
            $.ajax({
                type: "POST",
                url: "/manager/recharge_icon",
                data: {
                    "id":re_id,
                    "key":key,
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
    });
</script>

</body>

</html>