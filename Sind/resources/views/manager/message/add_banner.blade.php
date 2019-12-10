<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>新增轮播</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/wangEditor-2.0.17/dist/css/wangEditor.min.css">
</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>新增轮播</legend>
    </fieldset>
    <form method="post" class="layui-form layui-form-pane" action="" enctype="multipart/form-data">
    {{--<form class="layui-form layui-form-pane" action="">--}}

    {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">概要</label>
            <div class="layui-input-block">
                <input type="text" name="content" value="" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">发布时间</label>
            <div class="layui-input-block">
                <input type="text" name="show_time" id="test5" value="" lay-verify="required" placeholder="yyyy-MM-dd HH:mm:ss" autocomplete="off" class="layui-input">
            </div>
        </div>
        {{--<input type="file" name="file_img">--}}

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">图片上传</label>
            <div class="layui-input-block">
                <input type="file" name="file_img">
            </div>

        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">图片内容</label>
            <div class="layui-input-inline" style="width:600px">
                <div id="editor-trigger" style="height: 650px">
                </div>
            </div>
        </div>
     <input id="html" type="hidden" value="" name="html">
        {{--<div class="layui-form-item" pane="">--}}
            {{--<label class="layui-form-label">状态</label>--}}
            {{--<div class="layui-input-block">--}}
                {{--<input type="checkbox" checked="" name="status" lay-skin="switch" lay-filter="switchTest" title="开关" lay-text="开放|关闭">--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="layui-form-item">
            {{--<button type="submit" class="layui-btn " lay-submit="" lay-filter="">新增</button>--}}
            <button class="layui-btn" lay-submit="" lay-filter="demo1">新增</button>
        </div>
    </form>
</div>
<script src="/js/jquery-1.9.1.js"></script>
<script src="/plugins/layui/layui.js"></script>
<script type="text/javascript" src="/wangEditor-2.0.17/dist/js/wangEditor.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery,
            laydate = layui.laydate;

        //日期时间选择器
        laydate.render({
            elem: '#test5'
            ,type: 'datetime'
        });

        var editor = new wangEditor('editor-trigger');
        editor.config.uploadImgUrl = '/upload';
        editor.create();
        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            var html = editor.$txt.html();

            $("#html").val(html);
            data.field.html = html;
           var formData = new FormData($(".layui-form-pane"));
            $.ajax({
                type: "POST",
                url: "{{ route('manager.banner.add') }}",
                data:  formData,
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

    });
</script>

</body>

</html>