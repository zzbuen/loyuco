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
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>IP限制列表</legend>
    </fieldset>

    <div style="display: inline-block">
        <div>
            <span>IP查询：</span>
            <input value="{{$ip}}" placeholder="请输入IP" id="select_ip" name="user_name" type="text" class="layui-input username_input">
        </div>
    </div>
    <input type="hidden" id="index_url" value="{{url("manager/save_select_index")}}">
    <input type="button" id="select" class="layui-btn" value="查询">
    <button class="layui-btn" id="add_ip">添加IP限制</button>
    <div class="layui-tab">
        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IP</th>
                        <th>状态</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($data as $key=>$value)
                    <tr>
                        <th>{{$value["id"]}}</th>
                        <th>{{$value["ip"]}}</th>
                        <th>
                            @if($value["delete_time"] == 0)
                                正常
                                @else
                                已删除
                            @endif
                        <th>{{date("Y-m-d H:i:s",$value["create_time"])}}</th>
                        <th>
                            @if($value["delete_time"] == 0)
                                <button data="{{$value["id"]}}" class="layui-btn layui-btn-small delete">删除</button>
                            @else
                                <button data="{{$value["id"]}}" class="layui-btn layui-btn-small layui-btn-danger huifu">恢复</button>
                            @endif
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
            {{$data->links()}}
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
         * 作用：删除IP限制
         * 作者：信
         * 时间：2018/04/17
         * 修改：暂无
         */
        $(document).on("click",".delete",function () {
           var id = $(this).attr("data");
           layer.confirm("确定删除此IP限制吗？",function () {
               $.ajax({
                   type: "POST",
                   url: "/manager/delete_ip",
                   data: {"id":id},
                   dataType: "json",
                   headers: {
                       'X-CSRF-TOKEN': '{{ csrf_token() }}'
                   },
                   success: function(res){
                       layer.alert(res.msg);
                       if(res.code){
                           location.reload();
                       }
                   }
               });
           });
        });


        /**
         * 作用：恢复IP限制
         * 作者：信
         * 时间：2018/04/17
         * 修改：暂无
         */
        $(document).on("click",".huifu",function () {
            var id = $(this).attr("data");
            layer.confirm("确定恢复此IP限制吗？",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/huifu_ip",
                    data: {"id":id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.alert(res.msg);
                        if(res.code){
                            location.reload();
                        }
                    }
                });
            });
        });


        /**
         * 作用：IP查询
         * 作者：信
         * 时间：2018/04/17
         * 修改：暂无
         */
        $(document).on("click","#select",function () {
           var url = $("#index_url").val();
           var ip   = $("#select_ip").val();
           url = url+"?ip="+ip;
           location.href = url;
        });


        /**
         * 作用：添加IP限制
         * 作者：信
         * 时间：2018/04/17
         * 修改：暂无
         */
        $(document).on("click","#add_ip",function () {
            layer.open({
                type: 2,
                title: '添加IP限制',
                shadeClose: true,
                shade: 0.8,
                area: ['500px', '500px'],
                maxmin: true,
                content: '{{ url("manager/add_ip") }}',
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