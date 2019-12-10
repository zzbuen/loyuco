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
        width: 18%;
    }
</style>

<body>
<div class="layui-everyday-list">
    <form class="layui-form layui-form-pane" action="" style="margin-left: 20%;margin-top: 20%">
        <div class="layui-form-item">
            <label class="layui-form-label">游戏名称</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{ $data['name'] }}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{ $data['typeName'] }}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">期号</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{ $data['periods'] }}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开奖结果</label>
            <div class="layui-input-inline" style="width: 250px">
                <input type="tel" minlength="2"  placeholder="01" maxlength="2"  name="source_name" value="" lay-verify="required"   autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="02" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="03" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="04" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="05" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="06" name="source_name" value="" lay-verify="required"   autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="07" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="08" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="09" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
                <input type="tel" minlength="2"  placeholder="10" name="source_name" value="" lay-verify="required"  autocomplete="off" class="layui-input input15">
            </div>
        </div>
    </form>

    <button id="mondify_ajax" class="layui-btn layui-btn-normal" style="margin-top: 20px;margin-left: 155px">确认开奖</button>
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


        $(document).on("click","#mondify_ajax",function () {
            var input15 =   $(".input15");
            var reg     =   /[0-9]{2}/;
            var result  =   "";
            for(var i=0;i<input15.length;i++){
                var val = $(input15[i]).val();
                if(val.length!==2){
                    layer.msg("请输入第"+(i+1)+"位的开奖数值，值必须是2位数，如果数值小于10请补0",{"icon":2});
                    break;
                }
                if(val == "00"){
                    layer.msg("请输入第"+(i+1)+"位的开奖数值，值不能为00",{"icon":2});
                    break;
                }
                if(!reg.test(val)){
                    layer.msg("请输入第"+(i+1)+"位的开奖数值，值不能为空且必须是正整数",{"icon":2});
                    break;
                }
                if(val>10){
                    layer.msg("请输入第"+(i+1)+"位的开奖数值，值必须小于10",{"icon":2});
                    break;
                }

                for(var j=0;j<input15.length;j++){
                    if(val == $(input15[j]).val() && i!=j){
                        layer.msg("第"+(i+1)+"位的开奖数值与第"+(j+1)+"位开奖直不可重复",{"icon":2});
                        return false;
                    }
                }

                result+=val+",";
            }
            if(i<10){
                return false;
            }
            result  =  result.substring(0,result.length-1);
            $.ajax({
                type:"post",
                url:"{{url('manager/draw.chongqing_ajax')}}",
                data:{"result":result,"id":"{{$data['id']}}","time":"{{$data['kaijiang_time']}}","game_id":"{{$data['game_id']}}"},
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success:function (res) {
                    layer.msg(res["msg"]);
                    if(res["code"]){
                        parent.location.reload();
                    }
                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试");
                }
            })

        })

    });

</script>

</body>

</html>