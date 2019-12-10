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
        width: 100px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_div1{
        height: 30px;
        width: 75px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
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
        <legend>系统消息</legend>
    </fieldset>
    <form method="post" action="{{url('manager/xitongxiaoxi')}}">
        {{ csrf_field() }}
        <div class="layui-inline">
            <label class="layui-form-label">接收者</label>
            <div class="layui-input-inline">
                <input placeholder="请输入接收者" id="user_name" value="{{$user_name}}" name="user_name" type="text" class="layui-input username_input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <select class="layui-select" id="status" name="status">
                    <option value=""  <?php if($status=="")echo 'selected=""' ?>>全部</option>
                    <option value="0" <?php if($status=="0")echo 'selected=""' ?>>未读</option>
                    <option value="1" <?php if($status=="1")echo 'selected=""' ?>>已读</option>
                </select>
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <input type="button" id="clear" class="layui-btn" value="清空选择">
        {{--<input type="button" id="duoxuan_delete" class="layui-btn layui-btn-danger" value="多选删除">--}}
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    {{--<th style="width: 5%!important;">--}}
                        {{--<input type="checkbox" name="like[write]" id="quanxuan" lay-filter="quanxuan" title="全选">--}}
                    {{--</th>--}}
                    <th>序号</th>
                    <th>接收者</th>
                    <th>级别</th>
                    <th>联系方式</th>
                    <th>时间</th>
                    <th>消息标题</th>
                    <th>消息内容</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        {{--<td>--}}
                            {{--<input type="checkbox" class="danxuan" value="{{$item['id']}}" name="like">--}}
                        {{--</td>--}}
                        <td>{{$item['id']}}</td>
                        <td>{{$item['user_id']}}</td>
                        <td>
                            @if($item['user']["role_id"] == 1)
                                会员
                                @else
                                代理
                            @endif
                        </td>
                        <td>{{$item['phone']}}</td>
                        <td>{{$item['create_time']}}</td>
                        <td>{{$item['title']}}</td>
                        <td>{{$item['content']}}</td>
                        <td>
                            @if($item['status']=="0")
                                    未读
                                @else
                                    <span style="color: red">已读</span>
                            @endif
                        <td>
                            <button data="{{$item['id']}}" class="layui-btn layui-btn-small layui-btn-primary look">查看</button>
                            <input type="hidden" value="{{$item['id']}}"/>
                            {{--<button data="{{$item['id']}}" class="layui-btn layui-btn-small back">回复</button>--}}
                            {{--<button data="{{$item['id']}}" class="layui-btn layui-btn-small layui-btn-danger delete">删除</button>--}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">暂无数据</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{$data->links()}}
    </div>
    <audio  display="none" src="/sound/wuya.mp3"  id="warning_tone">111</audio>
    <input type="hidden" value="0" id="quesiton_back_id">
</div>


<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
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


        /*
        * 作用：回复
        * 作者：
        * 修改：信
        * 时间：2018/04/12
        * */
        $(".back").click(function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '反馈回复',
                shadeClose: true,
                shade: 0,
                area: ['65%', '85%'],
                maxmin: true,
                content: '{{ url("manager/replay_qurestion") }}?id='+id,
            });
        });



        /*
        * 作用：查看
        * 作者：
        * 修改：信
        * 时间：2018/04/12
        * */
        $(".look").click(function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '查看系统消息',
                shadeClose: true,
                shade: 0,
                area: ['65%', '85%'],
                maxmin: true,
                content: '{{ url("manager/look_xitongxiaoxi") }}?id='+id,
            });
        });



        /*清空选择*/
        $(document).on("click","#clear",function () {
            $("#user_name").val("");
            $("#status").val("");
        });









        /*查看问题反馈*/
        function look_fankui($id) {
            layer.open({
                type: 2,
                title: '查看问题反馈',
                shadeClose: true,
                shade: 0,
                area: ['65%', '85%'],
                maxmin: true,
                content: '{{ url("manager/look_wenti") }}?id='+$id,
            });
        }



        /*
        * 作用：删除
        * 作者：信
        * 修改：暂无
        * 时间：2018/04/12
        * */
        $(document).on("click",".delete",function () {
            var id = $(this).attr("data");
            layer.confirm("确定删除此反馈吗？",function () {
                $.ajax({
                    type: "POST",
                    url: "{{url('manager/delete_question')}}" ,
                    data: {"id":id},
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.alert(res.msg);
                        if(res.code){
                            location.reload();
                        }
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试")
                    }
                });
            });
        });





    });
</script>

</body>

</html>