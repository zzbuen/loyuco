<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>赔率</title>
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
    tr th{
        text-align: center!important;
    }
    tr td{
        text-align: center!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>彩票赔率配置</legend>
    </fieldset>
    <table class="game_table">
        @foreach($game_list as $k=>$item)
            @if($item['id'] == Request::input('game_id') || $k == 1)
                @if($item['id']>=10)
                    <tr id="{{'g'.$item['id'] }}"><td class="game_td select_game">{{ $item['name'] }}</td></tr>
                    @else
                    <tr id="{{'g0'.$item['id'] }}"><td class="game_td select_game">{{ $item['name'] }}</td></tr>
                @endif
            @else
                @if($item['id']>=10)
                    <tr id="{{'g'.$item['id'] }}"><td class="game_td">{{ $item['name'] }}</td></tr>
                    @else
                    <tr id="{{'g0'.$item['id'] }}"><td class="game_td">{{ $item['name'] }}</td></tr>
                @endif
            @endif
        @endforeach
    </table>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">
        <div class="layui-tab-title">
            <ul id="category_ul">
                @foreach($category_list as $k=>$item)
                    <input type="hidden" value={{$item["cateId"]}}>
                    @if($item["cateId"] == Request::input('category_id') || $k == 0)
                        <li class="layui-this">{{$item['category'] }}</li>
                    @else
                        <li>{{$item['category'] }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="layui-table">
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                    <tr style="border-top: 1px solid black">
                        <th>玩法</th>
                        <th>初级房</th>
                        <th>中级房</th>
                        <th>高级房</th>
                        <th>尊贵房</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($odds_list as $item)
                        <tr>
                            <td>{{$item["ruleName"] }}</td>
                            <td>{{$item["odds1"]}}</td>
                            <td>{{$item["odds2"]}}</td>
                            <td>{{$item["odds3"]}}</td>
                            <td>{{$item["odds4"]}}</td>
                            <td><input type="hidden" value="{{$item["id"]}}"><a class="modify_odd layui-btn layui-btn-small">编辑</a></td>
                            </tr>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
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
           location.href = "{{url('manager/odds.index') }}?game_id="+game_id;
       })

        $("#category_ul li").click(function () {
            var game_id = $("#now_game").val();
            var category_id = $(this).prev().val();
            location.href = "{{url('manager/odds.index') }}?game_id="+game_id+"&category_id="+category_id;
        })


        /*修改玩法赔率*/
        $(".modify_odd").click(function () {
            var odd_id = $(this).prev().val();
            var url = "{{ url('manager/modify_odd')}}?odd_id="+odd_id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'修改赔率',
                    shadeClose:true,
                    shade:0,
                    area:['400px','500px'],
                    content:url,
                    skin:'accountOp'
                })
            })
        });
    });

</script>

</body>

</html>