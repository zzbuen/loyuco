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
    }
</style>

<body>
<div class="layui-everyday-list">
    <form class="layui-form layui-form-pane" action="" style="margin-left: 20%;margin-top: 10%">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data['user']['username']}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">订单号</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["order_id"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">期号</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["bet_period"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">游戏名称</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["odds"]["gameName"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">玩法</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["odds"]["category"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">子玩法</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" disabled="disabled" value="{{$data["odds"]["ruleName"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="color: red">倍数</label>
            <div class="layui-input-inline">
                <input type="number" id="beiShu" name="source_name"  value="{{$data["beiShu"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="color: red">投注数</label>
            <div class="layui-input-inline">
                <input type="number" name="source_name"  id="bet_num" value="{{$data["bet_num"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="color: red">投注金额</label>
            <div class="layui-input-inline">
                <input type="number" name="source_name" id="bet_money"  value="{{$data["bet_money"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="color: red">投注号码</label>
            <div class="layui-input-inline">
                <?php $arr = explode(",",$data["bet_value"]); ?>
                @foreach($arr as $arrk=>$arrv)
                    <input type="tel"   name="source_name" value="{{$arrv}}" lay-verify="required"   autocomplete="off" class="layui-input input15">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" style="color: red">返点</label>
            <div class="layui-input-inline">
                <input type="text" name="source_name" id="fanDian" value="{{$data["fanDian"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
    </form>

    <button id="mondify_ajax" class="layui-btn layui-btn-normal" style="margin-top: 20px;margin-left: 155px">确认修改</button>
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


        /**
         * 作用：修改订单信息
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(document).on("click","#mondify_ajax",function () {
            var beishu      = $("#beiShu").val();
            var bet_num     = $("#bet_num").val();
            var bet_money   = $("#bet_money").val();
            var fanDian     = $("#fanDian").val();

            if(beishu<=0 || beishu==""){
                layer.alert("倍数不能小于基础倍数1，且不可为空");
                return false;
            }
            if(bet_num<=0 || bet_num==""){
                layer.alert("投注数不能小于基础注数1，且不可为空");
                return false;
            }
            if(bet_money<=0 || bet_money==""){
                layer.alert("投注金额不能小等于0，且不可为空");
                return false;
            }
            if(fanDian<=0 || fanDian>"{{$fandian}}"){
                layer.alert("返点比例不能小于0，且不可超过最大返点比例"+"{{$fandian}}%");
                return false;
            }

            var input15 = $(".input15");
            var reg = /^[1-9]\d*$/;
            var str = "";
            for(var i=0;i<input15.length;i++){
                var val = $(input15[i]).val();
                if(!reg.test(val) || val==""){
                    layer.alert("投注号码只能是正整数值行字符串且不可为空");
                    return false;
                }
                str+=val+",";
            }
            str = str.substring(0,str.length-1);

            /*执行请求修改*/
            $.ajax({
                type:"post",
                url:"{{url("manager/do_modification_order")}}",
                data:{
                    "beishu":beishu,
                    "bet_num":bet_num,
                    "bet_money":bet_money,
                    "fanDian":fanDian,
                    "bet_value":str,
                    "id":"{{$data["id"]}}"
                },
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success:function (res) {
                    layer.alert(res.msg);
                    if(res.code){
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