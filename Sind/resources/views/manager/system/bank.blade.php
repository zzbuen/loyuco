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
        <legend>支持银行</legend>
    </fieldset>
    <div style="width: 100%">
        <button id="add_bank" class="layui-btn">+添加银行</button>
    </div>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
            <tr>
                <th style="text-align: center">银行名称</th>
                <th style="text-align: center">操作</th>
            </tr>
            </thead>
            <tbody>
                @foreach($bank_list as $item)
                    <tr>
                        <td style="text-align: center">{{$item['bank_name']}}</td>
                        <td style="text-align: center">
                            <input type="hidden" value="{{$item['id']}}">
                            <button class="layui-btn layui-btn-small del_bank">删除银行</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
        @if ($errors->has('error'))
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
        $('#add_bank').click(function () {
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.prompt({ title: '添加银行',value: ''},function(value, index, elem){
                    var data = {bank_name:value};
                    var url = "{{url("/manager/System.add_bank")}}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(res){
                            if(res.flag){
                                layer.alert(res.msg, {icon: 1},function () {
                                    location.reload();
                                })
                            } else{
                                layer.alert(res.msg, {icon: 2},function () {
                                    location.reload();
                                })
                            }
                        }
                    });
                });
            })
        });
        $(".del_bank").click(function () {
            var id = $(this).prev().val();
            layer.confirm('确认删除银行？', {
                btn: ['确定', '取消'], //可以无限个按钮
                btn1: function(index, layero){
                    var url = '{{url("/manager/del_bank_ajax")}}';
                    var data = {id:id};
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(res){
                            if(res.flag){
                                layer.alert(res.msg, {icon: 1},function () {
                                    location.reload();
                                })
                            } else{
                                layer.alert(res.msg, {icon: 2},function () {
                                    location.reload();
                                })
                            }
                        }
                    });
                },
                btn2: function(index, layero){
                    location.reload();
                }
            })
        })
    });

</script>
</div>
</body>

</html>