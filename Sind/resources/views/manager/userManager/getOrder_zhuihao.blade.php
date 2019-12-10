<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
</head>
<style>
    .text_div{
        height: 30px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_div1{
        height: 30px;
        line-height: 0px;
        display: inline-block;

    }
    .text_span{
        display: inline-block ;
    }
    .username_input{
        width: 150px;
        display: inline-block;
    }
    tr th,td{
        text-align: center!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <div class="layui-tab">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>追号记录</legend>
        </fieldset>
        <form method="post" action="{{url('manager/zhuihao')}}" style="margin-top: 25px;">
            {{ csrf_field() }}
            <div style="display: inline-block">
                <div>
                    <div class="text_div">订单号<span class="text_span"></span></div>
                    <input placeholder="请输入订单号" value="{{Request::input('order_id')}}" name="order_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 20px">
                <div>
                    <div class="text_div">用户名<span class="text_span"></span></div>
                    <input placeholder="请输入用户名" value="{{Request::input('user_id')}}" name="user_id" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 20px">
                <div>
                    <div class="text_div">购买期数<span class="text_span"></span></div>
                    <input placeholder="请输入购买期数" value="{{Request::input('bet_period')}}" name="bet_period" type="text" class="layui-input username_input">
                </div>
            </div>
            <div style="display: inline-block;margin-left: 30px">
                <div>
                    <span>游戏</span>
                    <select class="layui-select" name="game_id" id="game_id" style="width: 150px">
                        <option value="">全部</option>
                        @foreach($game_list as $item)
                            <option @if($item['id']==Request::input('game_id')) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="submit" class="layui-btn" value="查询">
            <a href="" class="layui-btn" style="float: right">刷新</a>
        </form>
        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>用户名</th>
                        <th>游戏名称</th>
                        <th>游戏类型</th>
                        <th>玩法</th>
                        <th>子玩法</th>
                        <th>期号</th>
                        <th>投注金额</th>
                        <th>投注内容</th>
                        <th>追号期数</th>
                        <th>中奖金额</th>
                        <th>状态</th>
                        <th>下单时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($data as $key=>$val)
                    <tr>
                        <td>{{$val["order_id"]}}</td>
                        <td>{{$val["user"]["username"]}}</td>
                        <td>{{$val["odds"]["gameName"]}}</td>
                        <td>{{$val["odds"]["type"]}}</td>
                        <td>{{$val["odds"]["category"]}}</td>
                        <td>{{$val["odds"]["ruleName"]}}</td>
                        <td>{{$val["bet_period"]}}</td>
                        <td>{{$val["bet_money"]}}</td>
                        <td>
                            <?php

                            $str = $val['bet_value'];
                            $index = strpos($str,'|');
                            if($index){
                                $wei = substr($str,0,$index);
                                $wei = str_split($wei,1);
                                foreach ($wei as $key=>$item)
                                {
                                    switch ($item)
                                    {
                                        case '0':
                                            $wei[$key] = '万';
                                            break;
                                        case '1':
                                            $wei[$key] = '千';
                                            break;
                                        case '2':
                                            $wei[$key] = '百';
                                            break;
                                        case '3':
                                            $wei[$key] = '十';
                                            break;
                                        case '4':
                                            $wei[$key] = '个';
                                            break;
                                    }
                                }
                                $wei = implode('',$wei);
                                $str = substr($str,$index);
                                $str = $wei.$str;
                                echo $str;
                            }else{
                                echo $str;
                            }

                            ?>

                        </td>
                        <td>{{$val["zhuiHao"]}}</td>
                        <td>
                            @if($val["status"])
                                {{$val["bonus"]}}
                            @endif
                        </td>
                        <td>
                            @if($val["status"])
                                已开奖
                            @else
                                未开奖
                            @endif
                        </td>
                        <td> {{$val["order_dateTime"]}}</td>
                        <td>
                            <button data="{{$val["id"]}}" class="layui-btn layui-btn-small layui-btn-primary order_detail">详情</button>
                            @if(!$val["status"])
                                <button data="{{$val["id"]}}" class="layui-btn layui-btn-small modification">修改</button>
                                <button data="{{$val["id"]}}" class="layui-btn layui-btn-small layui-btn-danger backout">撤单</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$data->appends([
            "game_id"   =>  Request::input("game_id"),
            "user_id"   => Request::input("user_id"),
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

        $('.config-btn').click(function () {
            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '公告编辑',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.announcement.edit") }}?id='+config_id,
            });
        });

        /**
         * 作用：撤单
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(document).on("click",".backout",function () {
           var id = $(this).attr("data");
           layer.confirm("确定撤销此订单吗?",function () {
               $.ajax({
                   type:"post",
                   url:"{{url("manager/backout_order")}}",
                   data:{"id":id},
                   headers:{
                       'X-CSRF-TOKEN': '{{ csrf_token() }}'
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


        /**
         * 作用：修改
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(document).on("click",".modification",function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '修改订单信息',
                shadeClose: true,
                shade: 0,
                area: ['500px', '800px'],
                maxmin: true,
                content: '{{ url("manager/modification_order") }}?id='+id,
            });
        });






        /**
         * 作用：订单详情
         * 作者：信
         * 修改：暂无
         * 时间：2018/04/10
         */
        $(".order_detail").click(function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '订单详情',
                shadeClose: true,
                shade: 0,
                area: ['70%', '90%'],
                maxmin: true,
                content: '{{ url("manager/getOrder_detail") }}?id='+id,
            });
        });




        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_begin' //指定元素
            });
        });
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_end' //指定元素
            });
        });
        $(".sorting").click(function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            var column = $(this).attr('sort');
            if($(this).hasClass("sorting_desc")){
                var sort = 'asc';
            }else {
                var sort = 'desc';
            }
            location.href = '{{ url("manager/getOrder.index") }}?order_id={{Request::input('order_id')}}&game_id={{Request::input('game_id')}}&bet_period={{ Request::input("bet_period") }}&date_end={{ Request::input("date_end") }}&date_begin={{ Request::input("date_begin") }}&user_id={{ Request::input("user_id") }}&order_sn={{ Request::input("order_sn") }}&column='+column+'&sort='+sort;
        })
        @if ($errors->has('error'))
        layer.msg("{{ $errors->first('error') }}");
        @endif



    });
</script>

</body>

</html>