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
        width: 80px;
        line-height: 0px;
        display: inline-block;
        text-align: justify;

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
        <ul class="layui-tab-title">
            <li id="user_withdraw_verify" class="layui-this">待审核</li>
            <li id="user_withdraw_paying">处理中</li>
            <li id="user_withdraw_payed">已提现</li>
            <li id="user_withdraw_back">已拒绝</li>
        </ul>
        <div class="layui-tab-content" style="">
            <div class="layui-tab-item layui-show">
                {{--<div style="padding: 10px;border: 1px solid gainsboro;width: 100%;margin-left: -11px;">--}}
                    {{--<button class="layui-btn" id="user_verify_excel">导出</button>--}}
                {{--</div>--}}
                <div class="layui-form">
                    <table class="layui-table">
                        <thead>
                            <tr>
                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>交易编号</th>
                            <th>描述</th>
                            <th>提现金额</th>
                            <th>手续费</th>
                            <th>实际到账</th>
                            <th>汇款银行</th>
                            <th>汇款账号</th>
                            <th>时间</th>
                            <th>操作</th>
                            </tr>
                        </thead>
                       <tbody>
                       <tr>
                           @foreach($user_withdraw_list as $item)
                           <tr>
                               <td>{{$item['user_id']}}</td>
                               <td>{{$item['info']['name']}}</td>
                               <td>{{$item['trade_sn']}}</td>
                               <td>{{$item['trade_describe']}}</td>
                               <td>{{$item['trade_amount']}}</td>
                               <td>{{$item['service_money']}}</td>
                               <td>{{$item['real_money']}}</td>
                               <td>{{$item['userbank']['bankname']['bank_name']}}
                                   {{$item['userbank']['bank_branch']}}支行
                               </td>
                               <td>{{$item['userbank']['account']}}</td>
                               <td>{{$item['updated_at']}}</td>
                               <td>
                                   <input type="hidden" value="{{$item['trade_sn']}}">
                                   <button class="layui-btn change_back">拒绝</button>
                                   {{--<button class="layui-btn change_paying">转处理</button>--}}
                               </td>
                           </tr>
                           @endforeach
                       </tr>
                       </tbody>
                    </table>
                    {{ $user_withdraw_list->appends([])->links() }}
                </div>
            </div>
        </div>
    </div>
    <audio  display="none" src="/sound/waring_sound.mp3"  id="warning_tone">111</audio>
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
        $("#user_withdraw_verify").click(function () {
            location.href = '{{url("manager/user_withdraw_verify.index") }}'
        })
        $("#user_withdraw_paying").click(function () {
            location.href = '{{url("manager/user_withdraw_paying") }}'
        })
        $("#user_withdraw_payed").click(function () {
            location.href = '{{url("manager/user_withdraw_payed") }}'
        })
        $("#user_withdraw_back").click(function () {
            location.href = '{{url("manager/user_withdraw_back") }}'
        })
       $("#payment_excel").click(function () {
           window.open('{{ url("manager/payment_excel") }}');
           location.reload();
       })
        $("#user_verify_excel").click(function () {
            window.open('{{ url("manager/user_verify_excel") }}');
            location.reload();
        })
        $(".change_back").click(function () {
            var trade_sn = $(this).parent().children().eq(0).val();
            var user_id = $(this).parent().parent().eq(0).text();
            layer.prompt({
                formType: 2,
                value: '',
                title: '拒绝原因',
                area: ['300px', '350px'] //自定义文本域宽高
            }, function(value, index, elem){
                var data = {trade_sn:trade_sn,reson:value,user_id:user_id};//得到value
                layer.close(index);
                $.ajax({
                    type: "POST",
                    url: "{{url("/manager/user_change_back_ajax")}}" ,
                    data: data,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.flag) {
                            layer.alert(res.msg, {icon: 1},function(index){
                                location.reload();
                            });
                        }
                        else {
                            layer.alert(res.msg, {icon: 2}, function (index) {
                                location.reload();
                            });
                        }
                    }
                });
            });

        })
        $(".change_paying").click(function () {
            var trade_sn = $(this).parent().children().eq(0).val();
            var data = {trade_sn:trade_sn,status:1}
            user_change_status_ajax(data)
        })
        function user_change_status_ajax(data){
            $.ajax({
                type: "POST",
                url: "{{url("/manager/user_change_status_ajax")}}" ,
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.flag) {
                        layer.alert(res.msg, {icon: 1},function(index){
                            location.reload();
                        });
                    }
                    else {
                        layer.alert(res.msg, {icon: 2}, function (index) {
                            location.reload();
                        });
                    }
                }
            });
        }
        var t =setInterval(function () {
            $.ajax({
                type: "POST",
                url: "{{url('manager/warning_tone_ajax')}}" ,
                data: '231',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.flag) {
                        document.getElementById("warning_tone").play();
                        layer.alert('有'+res.msg+'人提现啦！！！', {icon: 1,offset: 'rb',shade:0});
                    }
                }
            });
        },5000)
    })
</script>
</body>
</html>