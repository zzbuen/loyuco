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
    /*.getUser_detail{*/
        /*text-decoration: underline;*/
        /*cursor: pointer;*/
    /*}*/
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>用户管理</legend>
    </fieldset>
    <form method="post" action="{{url('agent/getUser')}}">
        {{ csrf_field() }}
        <div style="display: inline-block">
            <div>
                <span>用户账号</span>
                <input value="{{ Request::input('user_name')?Request::input('user_name'):''}}" name="user_name" type="text" class="layui-input username_input">
            </div>
        </div>
        <div style="display: inline-block;margin-left: 30px">
            <div>
                <span>用户状态</span>
                <select class="layui-select" name="leader_id" id="leader_id" style="width: 100px">
                    <option value="0">全部</option>
                    <option value="1">可登录</option>
                    <option value="2">禁用</option>
                </select>
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <a href="" class="layui-btn" style="float: right">刷新</a>
    </form>
    <div class="layui-form">
        <table class="layui-table">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">会员类型</th>
                    <th style="text-align: center">用户名</th>
                    <th style="text-align: center">上级</th>

                    <th style="text-align: center">可用余额</th>

                    <th style="text-align: center">用户状态</th>
                    <th style="text-align: center">最后登录时间</th>
                    <th style="text-align: center">操作</th>
                </tr>
            </thead>
            <tbody>
            @forelse($data as $key=>$val)
                <tr>
                    <td>
                        @if($val["role_id"]==2)
                        代理
                        @else
                        会员
                        @endif
                    </td>
                    <td>{{$val["zhanghao"]}}</td>
                    <td>
                        @if($val["parent_user_id"]=='0'||$val["parent_user_id"]=='admin')
                            <span style="color: dodgerblue">系统</span>
                        @else
                            {{$val["parent_user_id"]}}
                        @endif
                    </td>

                    <td>{{number_format($val["remaining_money"],2)}}</td>
                 
                    <td>
                        @if($val["status"])
                            可登录
                        @else
                            <span style="color: red">禁用</span>
                        @endif
                    </td>
                    <td>{{$val["last_login_time"]}}</td>
                 <td><button data="{{$val["user_id"]}}" class="layui-btn-primary layui-btn-small getUser_detail">详情</button></td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">暂无数据</td>
                </tr>
            @endforelse

            </tbody>
        </table>
        {{ $data->appends([
           'user_name' => Request::input('user_name'),
           'user_type' => Request::input('user_type'),
           'column' => Request::input('column'),
           'sort' => Request::input('sort')
       ])->links() }}
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
        var leader_id = "{{Request::input('leader_id')}}";
        if(leader_id) {
            $("#leader_id option").each(function () {
                if($(this).val()==leader_id){
                    $(this).attr('selected',true)
                }
            })
        } else {
            $("#leader_id").children().eq(0).attr('selected',true)
        }
        $(".become_agent").click(function () {
            var user_id = $(this).prev().val();
            layer.open({
                type: 2,
                title: '成为代理',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '50%'],
                maxmin: true,
                content: '{{ url("agent/becomeAgent") }}?user_id='+user_id
            });
        });
        $('.getUser_detail').click(function () {
            var user_id = $(this).attr('data');
            var url = "{{ url('agent/getUser_detail')}}?user_id="+user_id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'用户详情',
                    shadeClose:true,
                    shade:0,
                    area:['720px','70%'],
                    content:url,
                    skin:'accountOp'
                })
            })
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
            location.href = '{{ url("agent/getUser") }}?leader_id={{ Request::input("leader_id") }}&column='+column+'&sort='+sort;
        })
    });
</script>

</body>

</html>