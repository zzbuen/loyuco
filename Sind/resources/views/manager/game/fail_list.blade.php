<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>开奖失败记录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
</head>
<style>
    tr th,td{
        text-align: center!important;
    }
</style>
<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>开奖失败列表</legend>
    </fieldset>
    <div style="margin-top: 30px">
        <ul id="category_ul">
            <li>
                <label class="layui-form-label"  style="color: #0C0C0C">快捷选时：</label>
                <button data="1" class="kj_time layui-btn <?php if($time != 1)echo "layui-btn-primary";?>">今天</button>
                <button data="2" class="kj_time layui-btn <?php if($time != 2)echo "layui-btn-primary";?>">昨天</button>
                <button data="3" class="kj_time layui-btn <?php if($time != 3)echo "layui-btn-primary";?>">近三天</button>
                <button data="7" class="kj_time layui-btn <?php if($time != 7)echo "layui-btn-primary";?>">近七天</button>
                <button data="15" class="kj_time layui-btn <?php if($time != 15)echo "layui-btn-primary";?>">近半月</button>
                <button data="30" class="kj_time layui-btn <?php if($time != 30)echo "layui-btn-primary";?>">近一月</button>
            </li>
        </ul>
    </div>
    <div style="margin-top: 10px">
        <ul id="category_ul">
            <li style="height: 40px">
                <div class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label"  style="color: #0C0C0C">游戏名称：</label>
                            <div class="layui-input-inline">
                                <select class="layui-select" id="game_id">
                                    <option value="">全部</option>
                                    @foreach($game as $key=>$value)
                                        <option value="{{$value["id"]}}">{{$value["name"]}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="layui-form-label" style="color: #0C0C0C">期数：</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{$period_number}}" class="layui-input"  id="period_number" placeholder="请输入期数">
                            </div>

                            <label class="layui-form-label" style="color: #0C0C0C">开奖时间：</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{$near_time}}" class="layui-input" id="test6" placeholder=" - ">
                            </div>
                            <button id="select" class="layui-btn">查询</button>
                            <button id="clear" class="layui-btn">清空选择</button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th>游戏名称</th>
                    <th>期数</th>
                    <th>开始售卖时间</th>
                    <th>结束售卖时间</th>
                    <th>开奖时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($fail_list as $item)
                <tr>
                    <td>{{ $item['game']['name'] }}</td>
                    <td>{{ $item['period_number'] }}</td>
                    <td>{{ $item['start_time'] }}</td>
                    <td>{{ $item['bet_end_time'] }}</td>
                    <td>{{ $item['draw_time'] }}</td>
                    <td>
                        <button class="layui-btn config-btn layui-btn-small" config_id="{{ $item['id'] }}">手动开奖</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$fail_list->appends([
        "game_id"   => $game_id,
        "period_number" => $period_number,
        "time"      => $time,
        "near_time" => $near_time
    ])->links()}}
</div>
<audio  display="none" src="/sound/littleyellowpeople.wav"  id="warning_tone">111</audio>
<script src="/plugins/layui/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;


        /*日期*/
        layui.laydate.render({
            elem: '#test6'
            ,range: true
        });

        /**
         * 查询
         */
        $("#select").click(function () {
            var game_id = $("#game_id").val();
            var near_time = $("#test6").val();
            var period_number = $("#period_number").val();
            location.href = "/manager/draw.index?game_id="+game_id+"&period_number="+period_number+"&near_time="+near_time;
        });


        /**
         * 清空选择
         */
        $("#clear").click(function () {
            $("#game_id").val("");
            $("#test6").val("");
            $("#period_number").val("");
        });



        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/draw.index') }}?time="+$data;
        });






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

        $('.config-btn').click(function () {
            var id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '修复开奖结果',
                shadeClose: true,
                shade: 0.8,
                area: ['700px', '250px'],
                maxmin: true,
                content: '{{ url('/manager/draw.fixed') }}?id='+id,
            });
        });

        var t =setInterval(function () {
            $.ajax({
                type: "POST",
                url: "{{url('/manager/draw.index')}}" ,
                data: '231',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.success) {
                        document.getElementById("warning_tone").play();
                        layer.alert('有彩票开奖失败啦！！！', {icon: 1,offset: 'rb',shade:0});
                    }
                }
            });
        },10000);
    });
</script>

</body>

</html>