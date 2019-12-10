<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <style>
        input[type=number] {
            -moz-appearance:textfield;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body>
<div style="margin: 15px;">
    <form class="layui-form" id="inport_form">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ Request::input('id') }}">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">开奖号码</label>
                <div class="layui-input-block">
                @for($i=0; $i<$length; $i++)
                    <input type="tel" style="width: 40px;margin-right: 10px; display: inline-block;margin-bottom: 10px;" maxlength="2" name="draw[]" placeholder="" required  lay-verify="required" autocomplete="off" class="layui-input">
                @endfor
                </div>
            </div>
        </div>
        <div  style="display: flex; justify-content: center; -webkit-justify-content: center;">
            <button type="submit" class="layui-btn">提交</button>
        </div>
    </form>
</div>
</body>
<script type="text/javascript" src="/plugins/layui/layui.js"></script>
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var $ = layui.jquery,
            layer = layui.layer;

        /**
         * 开奖结果验证数字
         */
        $('[type="tel"]').keyup(function (event) {
            var reg = /^[0-9]*$/;
            if(!reg.test($(this).val())){
               layer.msg("请输入数字");
                $(this).val("");
            }
        });



        //监听提交
        $('#inport_form').on('submit', function(e) {
            var index = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            e.preventDefault();
            $.ajax({
                url: "{{ url("/manager/draw.fixed")}}",
                type: "POST",
                data:  new FormData(this),
                dataType:'json',
                async: false,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    layer.close(index);
                    layer.msg(data.msg);
                    if (data.success) {
                        window.parent.location.reload();
                    }
                }
            });
        });
    });
</script>
</html>