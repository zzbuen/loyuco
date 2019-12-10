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
        width: 8%;
        border-right: 1px solid #e2e2e2;
        float: left;
    }
    .last_game_td{
        border-bottom: none;

    }
    .body_right{

        height: 750px;
        width: 91%;
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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>彩票分类管理</legend>
    </fieldset>
    <table class="game_table">
        {{--打印所有的游戏信息，选中的游戏信息标红否则不标红--}}
        @foreach($game_list as $k=>$item)
            @if($item['id'] == Request::input('game_id') || $k == 1)
                @if($item['id']<10)
                    <tr id="{{'g0'.$item['id'] }}"><td class="game_td select_game">{{ $item['name'] }}</td></tr>
                    @else
                    <tr id="{{'g'.$item['id'] }}"><td class="game_td select_game">{{ $item['name'] }}</td></tr>
                @endif
            @else
                @if($item['id']<10)
                    <tr id="{{'g0'.$item['id'] }}"><td class="game_td">{{ $item['name'] }}</td></tr>
                    @else
                    <tr id="{{'g'.$item['id'] }}"><td class="game_td">{{ $item['name'] }}</td></tr>
                @endif
            @endif
        @endforeach
    </table>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">
        <div class="layui-table">
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                    <tr style="border-top: 1px solid black">
                        <th style="text-align: center">游戏</th>
                        <th style="text-align: center">玩法</th>
                        <th style="text-align: center">操作 </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category as $key=>$value)
                        <tr style="border-top: 1px solid black">
                            <th style="text-align: center">{{$value["gameName"]}}</th>
                            <th style="text-align: center">{{$value["category"]}}</th>
                            <th style="text-align: center">
                                @if($value["status"] == 1)
                                    <button class="layui-btn layui-btn-small jinyon" cate_id="{{$value["cateId"]}}" game_id="{{$value["gameId"]}}" type_id="{{$value["typeId"]}}">禁用</button>
                                    @else
                                    <button class="layui-btn qiyong layui-btn-small layui-btn-danger" cate_id="{{$value["cateId"]}}" game_id="{{$value["gameId"]}}" type_id="{{$value["typeId"]}}">启用</button>
                                @endif
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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

        if($(".select_game").length>1){
            $($(".select_game")[0]).removeClass('select_game');
        }
        if($(".layui-this").length>1){
            $($(".layui-this")[0]).removeClass('layui-this');
        }

       var game_td_length = $(".game_td").length-1;
       $($(".game_td")[game_td_length]).addClass('last_game_td');
       $(".game_td").click(function () {
           var game_id = $(this).parent().attr('id');
           var game_id = game_id.substr(1);
           location.href = "{{url('manager/fenleiguanli.index') }}?game_id="+game_id;
       });


        /**
         * 作用：禁用分类
         * 时间：2018/04/30
         * 作者：信
         * 修改：暂无
         */
        $(document).on("click",".jinyon",function () {
            var game_id = $(this).attr("game_id");
            var type_id = $(this).attr("type_id");
            var cate_id = $(this).attr("cate_id");
            $.ajax({
                type:"post",
                data:{
                    "game_id":game_id,
                    "type_id":type_id,
                    "cate_id":cate_id,
                },
                url:"{{url("manager/jinyon_fenlei")}}",
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success:function (res) {
                    layer.msg(res.msg);
                    if(res.code){
                        location.reload();
                    }
                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            })
        })



        /**
         * 作用：禁用分类
         * 时间：2018/04/30
         * 作者：信
         * 修改：暂无
         */
        $(document).on("click",".qiyong",function () {
            var game_id = $(this).attr("game_id");
            var type_id = $(this).attr("type_id");
            var cate_id = $(this).attr("cate_id");
            $.ajax({
                type:"post",
                data:{
                    "game_id":game_id,
                    "type_id":type_id,
                    "cate_id":cate_id,
                },
                url:"{{url("manager/qiyon_fenlei")}}",
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success:function (res) {
                    layer.msg(res.msg);
                    if(res.code){
                        location.reload();
                    }
                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            })
        })


    });

</script>

</body>

</html>