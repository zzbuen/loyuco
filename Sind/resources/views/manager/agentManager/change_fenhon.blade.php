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
                <input type="text" name="source_name" disabled="disabled" value="{{$username["username"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属上级</label>
            <div class="layui-input-inline">
                @if(!$parent_user_id)
                    <input type="text" name="source_name" disabled="disabled" value="系统" lay-verify="required"  autocomplete="off" class="layui-input">
                @else
                    <input type="text" name="source_name" disabled="disabled" value="{{$parent_user_id}}" lay-verify="required"  autocomplete="off" class="layui-input">
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">总亏损</label>
            <div class="layui-input-inline">
                <input type="number" id="riliang" name="source_name" value="{{$old_fenhon["total"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                <input type="hidden" id="riliang_hidden" name="source_name" value="{{$old_fenhon["total"]}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">活跃人数</label>
            <div class="layui-input-inline">
                <input type="number" id="renshu" name="source_name"  value="{{$old_fenhon["amount_num"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                <input type="hidden" id="renshu_hidden" name="source_name" value="{{$old_fenhon["amount_num"]}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">活跃金额</label>
            <div class="layui-input-inline">
                <input type="number" id="jiner" name="source_name"   value="{{$old_fenhon["amount_money"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
                <input type="hidden" id="jiner_hidden" name="source_name" value="{{$old_fenhon["amount_money"]}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分红比例</label>
            <div class="layui-input-inline">
                <input id="old_fenhon" type="hidden" value="{{$old_fenhon["bonus_ratio"]}}">
                <input type="number" id="bili" name="source_name" value="{{$old_fenhon["bonus_ratio"]}}" lay-verify="required"  autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分红策略</label>
            <div class="layui-input-inline">
                <input type="radio" name="celue" value="1" title="半月不累计" <?php if($old_fenhon["bonus_type"]==1)echo "checked=''";?> >
                <input type="radio" name="celue" value="2" title="半月累计" <?php if($old_fenhon["bonus_type"]==2)echo "checked=''";?>>
            </div>
        </div>
        <input id="wage_limit" type="hidden" value="{{$fenhon}}">
        <input id="user_id" type="hidden" value="{{$user_id}}">
        <input id="parent_user_id" type="hidden" value="{{$parent_user_id}}">
        <button id="qingding" type="button" class="layui-btn">签订</button>
    </form>

</div>

<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;



        /**
         * 签订日工资契约
         */
        $(document).on("click","#qingding",function () {
            var wage_limit  = $("#wage_limit").val();
            var user_id     = $("#user_id").val();
            var parent_user_id     = $("#parent_user_id").val();
            var celue       = $("input[type='radio']:checked").val();
            var old_fenhon  = $("#old_fenhon").val();
            var riliang     = $("#riliang").val().replace(/(^\s*)|(\s*$)/g,"");
            var renshu      = $("#renshu").val().replace(/(^\s*)|(\s*$)/g,"");
            var jiner       = $("#jiner").val().replace(/(^\s*)|(\s*$)/g,"");
            var bili        = $("#bili").val().replace(/(^\s*)|(\s*$)/g,"");

            var riliang_hidden    = $("#riliang_hidden").val();
            var renshu_hidden    = $("#renshu_hidden").val();
            var jiner_hidden    = $("#jiner_hidden").val();

            if(riliang<0 || riliang==""){
                layer.msg("总亏损不可小于0哦");
                return false;
            }
            if(riliang - riliang_hidden>0){
                layer.msg("总亏损不可高于之前的总亏损数据"+riliang_hidden);
                return false;
            }
            if(renshu<0 || renshu==""){
                layer.msg("人数不可小于0哦");
                return false;
            }
            if(renshu - renshu_hidden>0){
                layer.msg("人数不可高于之前的人数"+renshu_hidden);
                return false;
            }
            if(jiner<0 || jiner==""){
                layer.msg("金额不可小于0哦");
                return false;
            }
            if(jiner - jiner_hidden>0){
                layer.msg("金额不可高于之前的金额数"+jiner_hidden);
                return false;
            }
            if(bili-0>wage_limit){
                layer.msg("分红比例不能超过"+wage_limit+"%");
                return false;
            }
            if(bili-0<0){
                layer.msg("分红比例不能小于0哦亲");
                return false;
            }
            if(old_fenhon>bili){
                layer.msg("分红比例不能小于之前的"+old_fenhon+"%哦亲");
                return false;
            }
            $.ajax({
                type:"post",
                url:"{{url('manager/ajax_change_fenhon')}}",
                data:{
                    "user_id":user_id,
                    "riliang":riliang,
                    "renshu":renshu,
                    "jiner":jiner,
                    "parent_user_id":parent_user_id,
                    "bili":bili,
                    "celue":celue
                },
                 headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success:function (res) {
                    layer.alert(res.msg,function () {
                        if(res.code){
                            parent.location.reload();
                        }
                        layer.close(layer.index);
                    });
                },
                error:function (err) {
                    layer.msg("系统异常,请稍后再试")
                }
            })





        })

    });

</script>

</body>

</html>