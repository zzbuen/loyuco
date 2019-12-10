<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>修改赔率</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
</head>
<body>
<div class="layui-everyday-list">
    <div class="form-element element-name">
        <div class="layui-form-item" style="margin-top: 100px" >
            <label class="layui-form-label" style="width: 25%">初级房赔率:</label>
            <div class="layui-input-inline">
                <input type="number" name="modify_odd" class="layui-input" id="new_odd" value="{{$old_odd['odds1']}}" style="width: 70%;"/>
            </div>
            <label class="layui-form-label" style="width: 25%">中级房赔率:</label>
            <div class="layui-input-inline">
                <input type="number" name="modify_odd" class="layui-input" id="new_odd2" value="{{$old_odd['odds2']}}" style="width: 70%;"/>
            </div>
            <label class="layui-form-label" style="width: 25%">高级房赔率:</label>
            <div class="layui-input-inline">
                <input type="number" name="modify_odd" class="layui-input" id="new_odd3" value="{{$old_odd['odds3']}}" style="width: 70%;"/>
            </div>
            <label class="layui-form-label" style="width: 25%">VIP房赔率:</label>
            <div class="layui-input-inline">
                <input type="number" name="modify_odd" class="layui-input" id="new_odd4" value="{{$old_odd['odds4']}}" style="width: 70%;"/>
            </div>
        </div>
        <button id="mondify_ajax" class="layui-btn layui-btn" style="margin-top: 20px;margin-left: 156px">确认修改</button>
    </div>
</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/js/xin.js"></script>
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

        $("#mondify_ajax").click(function () {
            var new_odd = $("#new_odd").val().replace(/(^\s*)|(\s*$)/g, ""),
             new_odd2 = $("#new_odd2").val().replace(/(^\s*)|(\s*$)/g, ""),
             new_odd3 = $("#new_odd3").val().replace(/(^\s*)|(\s*$)/g, ""),
             new_odd4 = $("#new_odd4").val().replace(/(^\s*)|(\s*$)/g, "");
            if(new_odd<=0 || new_odd2<=0 || new_odd3<=0){
                layer.msg("很抱歉，您输入赔率有误，值不能为空且必须大于0",{icon: 2});
                return false;
            }
            if(new_odd>1000 || new_odd2>1000 || new_odd3>1000){
                layer.msg("赔率禁止大于4位数",{icon: 2});
                return false;
            }
            var odd_id = "{{$odd_id}}";
            var data = {new_odd:new_odd,new_odd2:new_odd2,new_odd3:new_odd3,odd_id:odd_id,new_odd4:new_odd4};
            $.ajax({
                type: "POST",
                url: "/manager/mondify_ajax",
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res==1) {
                        layer.alert('修改成功', function(index){
                            window.parent.location.reload();
                        });
                    }
                    else{
                        layer.alert('修改失败', function(index){
                            window.parent.location.reload();
                        });
                    }
                }
            });
        })
    });

</script>

</body>

</html>