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
</style>

<body>
<div class="layui-fluid main">
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li id="order"  class="layui-this">日工资</li>
            <li id="order_back" >分红</li>
        </ul>
        <div class="layui-tab-title" style="margin-top: 30px">
            <ul id="category_ul">
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
                                <label class="layui-form-label"  style="color: #0C0C0C">用户名：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$user_id}}" id="user_id" class="layui-input" placeholder="用户名..">
                                </div>

                                <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$near_time}}" class="layui-input" id="test6" placeholder=" - ">
                                </div>
                                <button id="select" class="layui-btn">查询</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        {{--<th>用户ID</th>--}}
                        <th>用户名</th>
                        {{--<th>签约代理ID</th>--}}
                        <th>签约代理</th>
                        <th>签约时间</th>
                        <th>发放时间</th>
                        <th>销售量</th>
                        <th>日工资比</th>
                        <th>应发工资</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($data as $key=>$value)
                    <tr>
                        {{--<th>{{$value["user_id"]}}</th>--}}
                        <th>{{$value["user"]["username"]}}</th>
                        {{--<th>{{$value["daili"]["user_id"]}}</th>--}}
                        <th>{{$value["daili"]["username"]}}</th>
                        <th>{{$value["wage"]["create_time"]}}</th>
                        <th>{{$value["create_time"]}}</th>
                        <th>{{$value["wage_money"]}}</th>
                        <th>{{$value["wage"]["wage_ratio"]}}%</th>
                        <th>{{$value["wage_amount"]}}</th>
                        <th>
                            @if($value["status"])
                                已发放
                                @else
                                未发放
                            @endif
                        </th>
                        <th>
                            <button data="{{$value["id"]}}" class="layui-btn look_detail">查看详情</button>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
            {{$data->appends([
                "time"      => Request::input("time"),
                "user_id"   => Request::input("user_id"),
                "val"       => Request::input("val"),
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

        /*日期*/
        layui.laydate.render({
            elem: '#test6'
            ,range: true
        });

        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/user_qiyue') }}?time="+$data;
        });


        /**
         * 作用：日期范围
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click","#select",function () {
            var val     =   $("#test6").val().replace(/(^\s*)|(\s*$)/g, "");
            var user_id =   $("#user_id").val().replace(/(^\s*)|(\s*$)/g, "");
            location.href = "{{url('manager/user_qiyue') }}?val="+val+"&user_id="+user_id;
        });


        /**
         * 作用：分红
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click","#order_back",function () {
            location.href = "{{url('manager/user_qiyue_fenhon') }}";
        });


        /**
         * 作用：日工资
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".look_detail",function () {
           var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '日工资详情',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.gongzi.detail") }}?id='+id,
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