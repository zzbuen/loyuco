<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>编辑房间信息</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/wangEditor-2.0.17/dist/css/wangEditor.min.css">

</head>

<body>

<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑房间信息</legend>
    </fieldset>
    <form class="layui-form layui-form-pane" action="">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $list['id'] }}">
        <div class="layui-form-item">
            <label class="layui-form-label">最低投注</label>
            <div class="layui-input-block">
                <input type="text" name="minBet" value="{{ $list['minBet'] }}" lay-verify="required" placeholder="输入最低投注" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">最高投注</label>
            <div class="layui-input-block">
                <input type="text" name="maxBet" value="{{ $list['maxBet'] }}" lay-verify="required" placeholder="输入最高投注" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">房间门槛</label>
            <div class="layui-input-block">
                <input type="text" name="cond" value="{{ $list['conditionmin'] }}" lay-verify="required" placeholder="输入进入房间门槛" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane="">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" {{ $list['is_private']==1?'checked':'' }} name="is_private" lay-skin="switch" lay-filter="switchTest" title="开关" lay-text="加密|开放">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">房间密码</label>
            <div class="layui-input-block">
                <input type="text" name="cipher" value="{{ $list['cipher'] }}"  placeholder="房间密码(不加密不用填)" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">赔率说明</label>
            <div class="layui-input-block">
                <textarea cols="30" rows="15" name="texts" >
                    {{ $list['texts'] }}
                </textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图片内容</label>
            <div class="layui-input-inline" style="width:600px">
                <div id="editor-trigger" style="height: 650px">
                    {{ $list['html'] }}
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">提交</button>
        </div>
    </form>
</div>
<script src="/js/jquery-1.9.1.js"></script>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script type="text/javascript" src="/wangEditor-2.0.17/dist/js/wangEditor.js"></script>

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
		        var str = "{{$list['html']}}";
        str = str.replace(/&gt;/g,'>');
        str = str.replace(/&lt;/g,'<');
        str = str.replace(/&quot;/g,'"');
        // console.log(str);
        editor.$txt.html(str);
        //监听提交
        form.on('submit(demo1)', function(data) {
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            console.log(data.field);
            var html = editor.$txt.html();
            data.field.html = html;
            $.ajax({
                type: "POST",
                url: "{{ route('manager.house.edit') }}",
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

    });
</script>

</body>

</html>