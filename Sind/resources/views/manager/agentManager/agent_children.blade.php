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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>{{Request::input('agent_user_id')}}下级用户</legend>
    </fieldset>
    <form method="post" action="{{url('manager/agent_children')}}?leader_id={{Request::input('leader_id')}}">
        {{ csrf_field() }}
        <div style="display: inline-block">
            <div>
                <div class="text_div">用户ID<span class="text_span"></span></div>
                <input value="{{ Request::input('user_id')?Request::input('user_id'):''}}" name="user_id" type="text" class="layui-input username_input">
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <input type="button" class="layui-btn" id="back_agent" value="返回代理列表">
    </form>
        <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>用户ID</th>
                    <th>用户账号</th>
                    <th>所属推广</th>
                    <th class="{{ Request::get('column')=='order_resule'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="order_resule">玩家盈亏</th>
                    <th>是否代理</th>
                    <th class="{{ Request::get('column')=='remaining_money'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="remaining_money">账户金额</th>
                    <th class="{{ Request::get('column')=='unliquidated_money'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="unliquidated_money">未结金额</th>
                    <th class="{{ Request::get('column')=='order_bet'?(Request::get('sort')=='asc'?'sorting_asc':'sorting_desc'):'sorting_both' }} sorting" sort="order_bet">总投注金额</th>
                    <th>联系方式</th>
                    <th>注册时间</th>
                    <th>登陆时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            @foreach($user_list as $item)
                <tr>
                    <td>{{$item['user_id']}}</td>
                    <td>{{$item['username']}}</td>
                    @if($item['parent_user_id'])
                    <td>{{$parent_userinfo[$item['parent_user_id']]['name']}}</td>
                    @else
                    <td>系统</td>
                    @endif
                    <td>{{$item['order_resule']}}</td>
                    @if($item['agent'])
                    <td>是</td>
                    @else
                    <td>否</td>
                    @endif
                    @if($item['remaining_money'])
                    <td>{{$item['remaining_money']}}</td>
                    @else
                    <td></td>
                    @endif
                    @if($item['unliquidated_money'])
                        <td>{{$item['unliquidated_money']}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$item['order_bet']}}</td>
                    <td>{{$item['mobile_number']}}</td>
                    <td>{{$item['create_time']}}</td>
                    @if(isset($login_list[$item['user_id']]))
                        <td>{{$login_list[$item['user_id']]['login_date']}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>
                        <input type="hidden" value="{{$item['user_id']}}"><a class="layui-btn getUser_detail">更多信息</a>
                        @if($item['parent_user_id'])
                            @if($parent_userinfo[$item['parent_user_id']]['name']=='系统')
                                @if($item['agent'])
                                <input type="hidden" value="{{$item['user_id']}}">
                                <a disabled="" class="layui-btn layui-btn-disabled">指定代理</a>
                                @else
                                <input type="hidden" value="{{$item['user_id']}}">
                                <a class="layui-btn become_agent">指定代理</a>
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $user_list->appends([
           'user_name' => Request::input('user_name'),
           'user_type' => Request::input('user_type'),
           'column' => Request::input('column'),
            'sort' => Request::input('sort')
       ])->links() }}
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
                area: ['40%', '60%'],
                maxmin: true,
                content: '{{ route("manager.announcement.edit") }}?id='+config_id,
            });
        });

        $('.getUser_detail').click(function () {
            var user_id = $(this).prev().val()
            var url = "{{ url('manager/getUser_detail')}}?user_id="+user_id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'用户详情',
                    shadeClose:true,
                    shade:0,
                    area:['40%','60%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        });

        var type_id = "{{Request::input('user_type')}}";
        if(type_id) {
            if(type_id==1) {
                $("#user_type").children().eq(1).attr('selected',true)
            } else {
                $("#user_type").children().eq(2).attr('selected',true)
            }
        } else {
            $("#user_type").children().eq(0).attr('selected',true)
        }
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
                area: ['60%', '50%'],
                maxmin: true,
                content: '{{ url("manager/becomeAgent") }}?user_id='+user_id,
            });
        })
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
            location.href = '{{ url("manager/agent_children") }}?leader_id={{Request::input('leader_id')}}&user_id={{Request::input('user_id')}}&user_type={{ Request::input("user_type") }}&leader_id={{ Request::input("leader_id") }}&column='+column+'&sort='+sort;
        })
        $("#back_agent").click(function () {
            location.href = '{{url('manager/agentCenter.index') }}';
        })
    });
</script>

</body>

</html>