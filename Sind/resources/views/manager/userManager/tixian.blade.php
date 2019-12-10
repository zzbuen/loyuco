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
        height: 750px;
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
        <legend>提现记录</legend>
    </fieldset>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">
        <div >
            <ul id="category_ul">
                <li><label class="layui-form-label"  style="color: #0C0C0C">快捷选时：</label>
                    <button data="1" class="kj_time layui-btn <?php if($time!=1) echo "layui-btn-primary";?>">今天</button>
                    <button data="2" class="kj_time layui-btn <?php if($time!=2) echo "layui-btn-primary";?>">昨天</button>
                    <button data="3" class="kj_time layui-btn <?php if($time!=3) echo "layui-btn-primary";?>">近三天</button>
                    <button data="7" class="kj_time layui-btn <?php if($time!=7) echo "layui-btn-primary";?>">近七天</button>
                    <button data="15" class="kj_time layui-btn <?php if($time!=15) echo "layui-btn-primary";?>">近半月</button>
                    <button data="30" class="kj_time layui-btn <?php if($time!=30) echo "layui-btn-primary";?>">近一月</button>
                </li>
            </ul>
        </div>


        <div style="margin-top: 10px">
            <ul id="category_ul">
                <li style="height: 40px">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label"  style="color: #0C0C0C">用户名：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$user_name}}" id="user_id" class="layui-input" placeholder="请输入用户名">
                                </div>
                                <label class="layui-form-label"  style="color: #0C0C0C">状态：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="zhuangtai">
                                        <option value="">全部</option>
                                        <option value="0" <?php if($zhuangtai=="0")echo 'selected=""' ?>>正在申请</option>
                                        <option value="2" <?php if($zhuangtai=="2")echo 'selected=""' ?>>提现成功</option>
                                        <option value="4" <?php if($zhuangtai=="4")echo 'selected=""' ?>>提现失败</option>
                                    </select>
                                </div>
                                <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$val}}" class="layui-input" id="test6" placeholder=" - ">
                                </div>
                                <button id="select" class="layui-btn">查询</button>
                                <button id="clear" class="layui-btn">清空选择</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="layui-table">
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                    <tr style=" border-top: 1px solid black">
                        <th class="tdcenter">订单号</th>
                        <th class="tdcenter">用户名</th>
                        <th class="tdcenter">上级</th>
                        <th class="tdcenter">账户余额</th>
                        <th class="tdcenter">提现金额</th>
                        <th class="tdcenter">收款银行</th>
                        <th class="tdcenter">收款账号</th>
                        <th class="tdcenter">收款人</th>
                        <th class="tdcenter">开户行</th>
                        <th class="tdcenter">备注</th>
                        <th class="tdcenter">申请时间</th>
                        <th class="tdcenter">处理时间</th>
                        <th class="tdcenter">IP</th>
                        <th class="tdcenter">状态</th>
                        <th class="tdcenter">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user_withdraw_list as $key=>$value)
                        <tr style=" border-top: 1px solid black">
                            <td class="tdcenter">{{$value["trade_sn"]}}</td>
                            <td class="tdcenter">{{$value["user"]["username"]}}</td>
                            <td class="tdcenter">
                                @if($value["info"]["parent_user_id"]=="0")
                                    <span style="color: red">系统</span>
                                    @else
                                    {{$value["info"]["shangji"]["username"]}}
                                @endif
                            </td>
                            <td class="tdcenter">{{$value["bet_money"]}}</td>
                            <td class="tdcenter">{{$value["trade_amount"]}}</td>

                            <td class="tdcenter">{{$value["bank_name"]}}</td>
                            <td class="tdcenter">{{$value["bank_account"]}}</td>
                            <td class="tdcenter">{{$value["bank_account_name"]}}</td>
                            <td class="tdcenter">{{$value["bank_details"]}}</td>

                            <td class="tdcenter">{{$value["trade_remarks"]}}</td>
                            <td class="tdcenter">{{$value["created_at"]}}</td>
                            <td class="tdcenter">
                                @if($value["trade_state"] == '2'||$value["trade_state"] == '4')
                                {{$value["updated_at"]}}
                                @endif
                            </td>
                            <th class="tdcenter">{{$value["ip"]}}
                            </th>
                            <td class="tdcenter">
                                @switch($value["trade_state"])
                                    @case(0)
                                    @case(1)
                                        申请中
                                    @break;
                                    @case(2)
                                        <span style="color: green">提现成功</span>
                                    @break;
                                    @case(3)
                                    异常
                                    @break;
                                    @case(4)
                                        <span style="color: red">提现失败</span>
                                    @break;
                                @endswitch
                            </td>
                            <td class="tdcenter">
                                @switch($value["trade_state"])
                                    @case(0)
                                    @case(1)
                                    <button data="{{$value["id"]}}" class="layui-btn layui-btn-small chuli">处理</button>
                                    @break;
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" style="text-align: center">暂无数据</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{$user_withdraw_list->appends([
                "time"          => $time,
                "user_id"     => $user_name,
                "val"           => $val,
                "zhuangtai"     => $zhuangtai
            ])->links()}}
        </div>
    </div>

</div>
<audio  id="audio_mp3"   loop="loop">
    <source src="/audio/ding.mp3" type="audio/mpeg">
    <source src="/audio/ding.ogg" type="audio/ogg">
    <embed height="100" width="100" src="/audio/ding.mp3" />
</audio>

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


        /*日期*/
        layui.laydate.render({
            elem: '#test6'
            ,range: true
        });



        /**
         * 作用：清除选择
         * 作者：信
         * 时间：2018/06/14
         * 修改：暂无
         */
        $("#clear").click(function () {
            $("#user_id").val("");
            $("#test6").val("");
            $("#zhuangtai").val("");
            form.render();
        });



        /*提现处理*/
        function chuli($id) {
            var url = "{{ url('manager/chuli')}}";
            url     = url+"?id="+$id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'提现处理',
                    shadeClose:true,
                    shade:0,
                    area:['500px','600px'],
                    content:url,
                    skin:'accountOp',
                })
            })
        }


        var audio = document.getElementById('audio_mp3');

        /*实时提示消息*/
        {{--setInterval(function () {--}}
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url: "{{url('manager/tixian_tishi')}}" ,--}}
                {{--dataType: "json",--}}
                {{--headers: {--}}
                    {{--'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
                {{--},--}}
                {{--success: function(res){--}}
                    {{--if(res.code) {--}}
                        {{--var id = res.id;--}}
                        {{--layer.alert('有人提现题啦', {icon: 1,offset: 'rb',shade:0},function () {--}}
                            {{--chuli(id);--}}
                        {{--});--}}
                        {{--audio.play();--}}
                    {{--}else{--}}
                        {{--audio.pause();--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--},1000);--}}



        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data       = $(this).attr("data");
            var user_id     = $("#user_id").val();
            var zhuangtai   = $("#zhuangtai").val();
            location.href = "{{url('manager/user_withdraw_verify.index') }}?time="+$data+"&user_id="+user_id+"&zhuangtai="+zhuangtai;
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
            var zhuangtai   = $("#zhuangtai").val();
            location.href = "{{url('manager/user_withdraw_verify.index') }}?val="+val+"&user_id="+user_id+"&zhuangtai="+zhuangtai;
        });




        /**
         * 作用：处理
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click",".chuli",function () {
            var data = $(this).attr("data");
            var url = "{{ url('manager/chuli')}}";
            url     = url+"?id="+data;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'提现处理',
                    shadeClose:true,
                    shade:0,
                    area:['80%','80%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        })



        
    });





</script>

</body>

</html>