<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>开奖结果页面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
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
    .tdcenter{
        text-align: center!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>开奖结果</legend>
    </fieldset>
    <table class="game_table" >
        {{--打印所有的游戏信息，选中的游戏信息标红否则不标红--}}
        @foreach($game_list as $k=>$item)
            @if($item['id'] == Request::input('game_id') || $k == 1)
                <tr id="{{$item['id']}}"><td class="game_td select_game">{{ $item['name'] }}</td></tr>
            @else
                <tr id="{{$item['id']}}"><td class="game_td">{{ $item['name'] }}</td></tr>
            @endif
        @endforeach
    </table>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">
        <div class="layui-tab-title" style="padding-bottom: 10px;">
            <ul id="category_ul" >
                <li>快捷选时：
                    <button data="1" class="kj_time layui-btn <?php if($time != 1)echo "layui-btn-primary";?>">今天</button>
                    <button data="2" class="kj_time layui-btn <?php if($time != 2)echo "layui-btn-primary";?>">昨天</button>
                    <button data="3" class="kj_time layui-btn <?php if($time != 3)echo "layui-btn-primary";?>">近三天</button>
                    <button data="7" class="kj_time layui-btn <?php if($time != 7)echo "layui-btn-primary";?>">近七天</button>
                    <button data="15" class="kj_time layui-btn <?php if($time != 15)echo "layui-btn-primary";?>">近半月</button>
                    <button data="30" class="kj_time layui-btn <?php if($time != 30)echo "layui-btn-primary";?>">近一月</button>
                </li>

                <li style="height: 40px">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label" style="color: #0C0C0C">开奖状态：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="game_status" onchange="alert(1)" >
                                        <option value="0" @if($status==0) selected @endif>全部</option>
                                        <option value="1" @if($status==1) selected @endif>未开奖</option>
                                        <option value="2" @if($status==2) selected @endif>已开奖</option>
                                        <option value="3" @if($status==3) selected @endif>开奖失败</option>
                                        <option value="4" @if($status==4) selected @endif>已退款</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="<?php if($date_range)echo $date_range;?>" class="layui-input" id="test6" placeholder=" - ">
                                </div>
                                <button id="select" class="layui-btn" style="position: relative;top: -3px;">查询</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="layui-table">
            @switch($data[0]["type"])
                {{--时时彩--}}
                @case(1)
                    <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                        <thead>
                            <tr style=" border-top: 1px solid black">
                                <th class="tdcenter">彩种</th>
                                <th class="tdcenter">类型</th>
                                <th class="tdcenter">开奖日期</th>
                                <th class="tdcenter">当天场次</th>
                                <th class="tdcenter">期号</th>
                                <th class="tdcenter">开奖结果</th>
                                <th class="tdcenter">状态</th>
                                <th class="tdcenter">开奖时间</th>
                                <th class="tdcenter">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key=>$val)
                                <tr style="border-top: 1px solid black">
                                    <td class="tdcenter">{{$val["name"]}}</td>
                                    <td class="tdcenter">{{$val["typeName"]}}</td>
                                    <td class="tdcenter"><?php  echo substr($val["periods"],0,4)."-".substr($val["periods"],4,2)."-".substr($val["periods"],6,2);?></td>
                                    <td class="tdcenter"><?php  echo substr($val["periods"],-3);?></td>
                                    <td class="tdcenter">{{$val["periods"]}}</td>
                                    <td class="tdcenter">
                                        <?php $resarr = explode(",",$val["result"]); ?>
                                        @if($resarr[0]!="")
                                            @foreach($resarr as $key=>$num)
                                                <span class="layui-badge">{{$num}}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="tdcenter">
                                        @switch($val["res_status"])
                                            @case(1)
                                                未开奖
                                            @break
                                            @case(2)
                                                已开奖
                                            @break
                                            @case(3)
                                                <label style="color: red">开奖失败</label>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="tdcenter">{{$val["kaijiang_time"]}}</td>
                                    <td class="tdcenter">
                                        <button game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="look_people layui-btn layui-btn-primary layui-btn-small">查看参与人数</button>
                                        @if($val["res_status"]!=2)
                                            <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="hand_draw layui-btn layui-btn-small">手动开奖</button>
                                        @endif
                                        @if($val["res_status"]==2||$val["res_status"]==3)
                                            <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="outPay layui-btn layui-btn-small layui-btn-danger">退款</button>
                                        @endif
                                        @if($val["is_resup"]==1&&$val["res_status"]!=2)
                                            <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="resup layui-btn layui-btn-small layui-btn-warm">取消预设</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @break

                {{--pk10--}}
                @case(2)
                <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                    <thead>
                    <tr style=" border-top: 1px solid black">
                        <th class="tdcenter">彩种</th>
                        <th class="tdcenter">类型</th>
                        <th class="tdcenter">开奖日期</th>
                        <th class="tdcenter">场次</th>
                        <th class="tdcenter">期号</th>
                        <th class="tdcenter">开奖结果</th>
                        <th class="tdcenter">状态</th>
                        <th class="tdcenter">开奖时间</th>
                        <th class="tdcenter">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key=>$val)
                        <tr style="border-top: 1px solid black">
                            <th class="tdcenter">{{$val["name"]}}</th>
                            <th class="tdcenter">{{$val["typeName"]}}</th>
                            <th class="tdcenter"><?php  echo substr($val["kaijiang_time"],0,10);?></th>
                            <th class="tdcenter"><?php  echo substr($val["periods"],-3);?></th>
                            <th class="tdcenter">{{$val["periods"]}}</th>
                            <th class="tdcenter">
                                <?php $resarr = explode(",",$val["result"]); ?>
                                @if($resarr[0]!="")
                                    @foreach($resarr as $key=>$num)
                                        <span class="layui-badge">{{$num}}</span>
                                    @endforeach
                                @endif
                            </th>
                            <th class="tdcenter">
                                @switch($val["res_status"])
                                    @case(1)
                                    未开奖
                                    @break
                                    @case(2)
                                    已开奖
                                    @break
                                    @case(3)
                                    <label style="color: red">开奖失败</label>
                                    @break
                                @endswitch
                            </th>
                            {{--<th class="tdcenter">{{date("Y-m-d H:i:s",$val["kaijiang_time"])}}</th>--}}
                            <th class="tdcenter">{{$val["kaijiang_time"]}}</th>
                            <th class="tdcenter">
                                <button game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="look_people layui-btn">查看参与人数</button>
                                @if(empty($val["result"]))
                                    <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="hand_draw layui-btn">手动开奖</button>
                                @endif
                                @if($val["res_status"]==2||$val["res_status"]==3)
                                    <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="outPay layui-btn layui-btn-small layui-btn-danger">退款</button>
                                @endif
                                @if($val["is_resup"]==1&&$val["res_status"]!=2)
                                    <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="resup layui-btn layui-btn-small layui-btn-warm">取消预设</button>
                                @endif
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @break
                {{--pk10--}}

                {{--pc蛋蛋--}}
                @case(3)
                <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                    <thead>
                    <tr style=" border-top: 1px solid black">
                        <th class="tdcenter">彩种</th>
                        <th class="tdcenter">类型</th>
                        <th class="tdcenter">开奖日期</th>
                        <th class="tdcenter">期号</th>
                        <th class="tdcenter">开奖结果</th>
                        <th class="tdcenter">状态</th>
                        <th class="tdcenter">开奖时间</th>
                        <th class="tdcenter">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key=>$val)
                        <tr style="border-top: 1px solid black">
                            <th class="tdcenter">{{$val["name"]}}</th>
                            <th class="tdcenter">{{$val["typeName"]}}</th>
                            <th class="tdcenter"><?php  echo substr($val["kaijiang_time"],0,10);?></th>
                            <th class="tdcenter">{{$val["periods"]}}</th>
                            <th class="tdcenter">
                                <?php $resarr = explode(",",$val["result"]); ?>
                                @if($resarr[0]!="")
                                    @foreach($resarr as $key=>$num)
                                        <span class="layui-badge">{{$num}}</span>
                                    @endforeach
                                @endif
                            </th>
                            <th class="tdcenter">
                                @switch($val["res_status"])
                                    @case(1)
                                    未开奖
                                    @break
                                    @case(2)
                                    已开奖
                                    @break
                                    @case(3)
                                    <label style="color: red">开奖失败</label>
                                    @break
                                @endswitch
                            </th>
                            {{--<th class="tdcenter">{{date("Y-m-d H:i:s",$val["kaijiang_time"])}}</th>--}}
                            <th class="tdcenter">{{$val["kaijiang_time"]}}</th>
                            <th class="tdcenter">
                                <button game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="look_people layui-btn">查看参与人数</button>
                                @if(empty($val["result"]))
                                    <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="hand_draw layui-btn">手动开奖</button>
                                @endif
                                @if($val["res_status"]==2||$val["res_status"]==3)
                                    <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="outPay layui-btn layui-btn-small layui-btn-danger">退款</button>
                                @endif
                                @if($val["is_resup"]==1&&$val["res_status"]!=2)
                                    <button allid="{{$val["id"]}}" game_id="{{$val["game_id"]}}" periods="{{$val["periods"]}}" class="resup layui-btn layui-btn-small layui-btn-warm">取消预设</button>
                                @endif
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @break
                {{--pc蛋蛋--}}
                @default
                <div style="width: 100%;height: 50px;line-height: 50px;text-align: center;background: gainsboro">
                    <td>暂无数据</td>
                </div>
            @endswitch
                {{$data->appends([
                    "game_id"       => Request::input('game_id'),
                    "time"          => Request::input('time'),
                    "date_range"    => Request::input('date_range'),
                    "status"        => Request::input('status')
                ])->links()}}
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



        /*日期*/
        layui.laydate.render({
            elem: '#test6'
            ,range: true
        });

        $(document).on("change","#game_status",function () {
            var $data = $(this).attr("data"),status = $("#game_status").val();
            var game_id  = "{{$game_id}}";
            location.href = "{{url('manager/draw.kaijiang') }}?game_id="+game_id+"&time="+$data+'&status='+status;
        });

        $('.outPay').click(function () {
            var game_id = $(this).attr('game_id'),allid = $(this).attr('allid'),periods = $(this).attr('periods');
            layer.confirm('确定给这期所有用户退款吗？', function () {
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                $.ajax({
                    type: "POST",
                    headers:{
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('manager.game.modify') }}",
                    data: {game_id:game_id},
                    dataType: "json",
                    success: function(res){
                        layer.close(index);
                        layer.msg(res.msg, {time:1100});
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            })
        });

        /*取消预设*/
        $('.resup').click(function () {
            var game_id = $(this).attr('game_id'),allid = $(this).attr('allid'),periods = $(this).attr('periods');
            layer.confirm('确定取消当期预设开奖结果吗？', function () {
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                $.ajax({
                    type: "POST",
                    headers:{
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('manager.game.unResup') }}",
                    data: {game_id:game_id,periods:periods},
                    dataType: "json",
                    success: function(res){
                        layer.close(index);
                        layer.msg(res.msg, {time:1100});
                        if (res.success) {
                            location.reload();
                        }
                    }
                });
            })
        });
        /**
         * 作用：游戏点击传游戏ID
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(".game_td").click(function () {
            var game_id = $(this).parent().attr('id');
            location.href = "{{url('manager/draw.kaijiang') }}?game_id="+game_id;
        });


        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data"),status = $("#game_status").val();
            var game_id  = "{{$game_id}}";
            location.href = "{{url('manager/draw.kaijiang') }}?game_id="+game_id+"&time="+$data+'&status='+status;
        });


        /**
         * 作用：日期范围
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click","#select",function () {
            var val =   $("#test6").val().replace(/(^\s*)|(\s*$)/g, ""),status = $("#game_status").val();
            if(val==""){
                return false;
            }
            var game_id  = "{{$game_id}}";
            location.href = "{{url('manager/draw.kaijiang') }}?game_id="+game_id+"&date_range="+val+"&time=100&status="+status;
        });
        

        /*时时彩手动开奖*/
        $(document).on("click",".hand_draw",function () {
            var id      =   $(this).attr("allid");
            var game_id =   $(this).attr("game_id");
            var url = "{{ url('manager/draw.chongqing')}}?id="+id+"&game_id="+game_id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'手动开奖',
                    shadeClose:true,
                    shade:0,
                    area:['500px','90%'],
                    content:url,
                    skin:'accountOp'
                })
            })
        });



        /*查看参与人数*/
        $(document).on("click",".look_people",function () {
            var game_id =   $(this).attr("game_id");
            var periods =   $(this).attr("periods");
            var url = "{{ url('manager/draw.look_people')}}?periods="+periods+"&game_id="+game_id;
            layer.open({
                type: 2,
                title:'查看参与人数',
                area: ['100%', '100%'],
                fixed: false, //不固定
                maxmin: true,
                content: url
            });
        })
        
        
    });






</script>

</body>

</html>