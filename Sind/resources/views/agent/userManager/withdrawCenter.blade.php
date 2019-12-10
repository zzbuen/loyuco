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
        width: 91%;
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
    .add_div{
        height: 39px;
        line-height: 39px;
        width: 99%;
        float: right;
        margin-right: 13px;
    }
    .text_div{
        height: 30px;
        line-height: 0px;
        display: inline-block;

    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
    .message_div {
        margin-top: 20px;
        margin-left: 30px;
        font-size: 18px;
    }
</style>

<body>
<div class="layui-fluid main">
    <div class="layui-tab">
        <form method="post" action="{{url('agent/withdrawCenter')}}">
            {{ csrf_field() }}
        <div class="form-element element-name" style="margin-top: 30px">
            <div class="text_div">提现状态<span class="text_span"></span></div>
            <select name='status' id="all_status" style="height: 30px;width: 175px;">
                <option value="">全部</option>
                <option value="1" @if(Request::input('status')==1) selected @endif>待审核</option>
                <option value="2" @if(Request::input('status')==2) selected @endif>处理中</option>
                <option value="3" @if(Request::input('status')==3) selected @endif>已付款</option>
                <option value="4" @if(Request::input('status')==4) selected @endif>已撤销</option>
            </select>
            <button class="layui-btn">查询</button>
        </div>
        </form>
    </div>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <th>提现单号</th>
                <th>提现总额</th>
                <th>提现状态</th>
                <th>汇款银行</th>
                <th>汇款账号</th>
                <th>创建时间</th>
                <th>更新时间</th>
            </thead>
            <tbody>
            @foreach($withdraw_list as $item)
                <tr>
                    <td>{{$item['withdraw_sn']}}</td>
                    <td>{{$item['amount']}}</td>
                    @if($item['status']==0)
                    <td>待审核</td>
                    @elseif($item['status']==1)
                    <td>处理中</td>
                    @elseif($item['status']==2)
                    <td>已付款</td>
                    @else
                    <td>已撤销</td>
                    @endif
                    <td>{{$item['userbank']['bankname']['bank_name']}}
                        {{$item['userbank']['bank_branch']}}支行
                    </td>
                    <td>{{$item['userbank']['account']}}</td>
                    <td>{{$item['created_at']}}</td>
                    <td>{{$item['updated_at']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $withdraw_list->appends([])->links() }}
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
        layui.use('element', function(){
            var element = layui.element;
        });
    });
</script>

</body>

</html>