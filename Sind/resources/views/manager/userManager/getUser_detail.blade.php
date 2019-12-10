<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="/plugins/font-awesome/css/font-awesome.css">
    <style>
        .info-box {
            height: 85px;
            background-color: white;
            background-color: #ecf0f5;
        }

        .info-box .info-box-icon {
            border-top-left-radius: 2px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 2px;
            display: block;
            float: left;
            height: 85px;
            width: 85px;
            text-align: center;
            font-size: 45px;
            line-height: 85px;
            background: rgba(0, 0, 0, 0.2);
        }

        .info-box .info-box-content {
            padding: 5px 10px;
            margin-left: 85px;
        }

        .info-box .info-box-content .info-box-text {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
        }

        .info-box .info-box-content .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 18px;
        }

        .major {
            font-weight: 10px;
            color: #01AAED;
        }

        .main {
            margin: 25px 0;
        }

        /*.main .layui-row {*/
        /*margin: 10px 0;*/
        /*}*/
    </style>
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
    .layui-form-label{
        width: 20%;

    }
    .layui-input-inline{
        width: 30%;
    }
    .layui-disabled, .layui-disabled:hover {
        color: gray!important;
        cursor: not-allowed!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <form style="margin-top: 20px;" class="layui-form layui-form-pane" action="">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>{{$user_detail[0]['username']}} - 基本信息</legend>
            <input type="hidden" id="user_id" value="{{$user_id}}">
        </fieldset>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" id="username" value="{{$user_detail[0]['username']}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">真实姓名</label>
                        <div class="layui-input-inline">
                            <input  name="user_gold" id="change_name" type="text" autocomplete="off" value="{{$user_detail[0]['info']['name']}}"  class="layui-input " >
                        </div>
                        <button type="button" class="layui-btn change_name" >修改</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-inline">
                            @if($user_detail[0]["role_id"] == 1)
                                <input disabled type="text" value="会员" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                            @else
                                <input disabled type="text" value="代理" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline">
                            <input  name="user_gold"  id="user_pwd" type="password" autocomplete="off" value="vip888"  class="layui-input " >
                        </div>
                        <button type="button" class="layui-btn user_pwd">修改</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">总余额</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$user_detail[0]["account"][0]["remaining_money"]}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">资金密码</label>
                        <div class="layui-input-inline">
                            <input  name="user_gold" id="zijin_pwd" type="password" value = '123456' autocomplete="off"   class="layui-input " >
                        </div>
                        <button type="button" class="layui-btn zijin_pwd">修改</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">冻结金额</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$user_detail[0]["account"][0]["unliquidated_money"]}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">手机号</label>
                        <div class="layui-input-inline">
                            <input name="user_gold" id="change_phone" type="text" autocomplete="off" value="{{$user_detail[0]['info']['mobile_number']}}"  class="layui-input " >
                        </div>
                        <button type="button" class="layui-btn change_phone">修改</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">注册时间</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$user_detail[0]['info']['create_time']}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">银行名称</label>
                        <div class="layui-input-inline" >
                            <select class="layui-select" id="bank_name" name="bank_name">
                                @foreach($bank as $key=>$value)
                                    @if($value["bank_name"]  == $user_detail[0]['info']['bank_name'])
                                        <option selected="selected" value="{{$value["bank_name"]}}">{{$value["bank_name"]}}</option>
                                    @else
                                        <option value="{{$value["bank_name"]}}">{{$value["bank_name"]}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        {{--<button type="button" class="layui-btn change_email">修改</button>--}}
                    </div>
                </div>

            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">注册IP</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$user_detail[0]['info']['register_ip']}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">持卡人姓名</label>
                        <div class="layui-input-inline">
                            <input name="bank_account_name"  id="bank_account_name" type="text" autocomplete="off" value="{{$user_detail[0]['info']['bank_account_name']}}"  class="layui-input " >
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">最后登陆IP</label>
                        <div class="layui-input-inline">
                            <input disabled type="text" value="{{$user_detail[0]['info']['last_login_ip']}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs6" >
                <div class="grid-demo">
                    <div class="layui-form-item" >
                        <label class="layui-form-label">银行卡号</label>
                        <div class="layui-input-inline">
                            <input  name="bank_account"  id="bank_account" type="text" autocomplete="off" value="{{$user_detail[0]['info']['bank_account']}}"   class="layui-input " >
                        </div>
                        <button type="button" class="layui-btn mod_bank">修改</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">提现倍数</label>
                        <div class="layui-input-inline">
                            <input  name="withdrawal"  id="withdrawal" type="text" autocomplete="off" value="{{$user_detail[0]['ration']}}"   class="layui-input " >
                        </div>
                        <button type="button" class="layui-btn withdrawal_update">修改</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="grid-demo grid-demo-bg1">
                    <div class="layui-form-item"  style="float: right;">
                        <label class="layui-form-label">等级</label>
                        <div class="layui-input-inline">
                            <select name="level" class="clear_val" id="level" >
                                <option value="1" <?php if($user_detail[0]["levels"] ==1) echo 'selected=""'?>>幼蚁</option>
                                <option value="2" <?php if($user_detail[0]["levels"] ==2) echo 'selected=""'?>>工蚁</option>
                                <option value="3" <?php if($user_detail[0]["levels"] ==3) echo 'selected=""'?>>兵蚁</option>
                                <option value="4" <?php if($user_detail[0]["levels"] ==4) echo 'selected=""'?>>雌蚁</option>
                                <option value="5" <?php if($user_detail[0]["levels"] ==5) echo 'selected=""'?>>雄蚁</option>
                                <option value="6" <?php if($user_detail[0]["levels"] ==6) echo 'selected=""'?>>蚁后</option>
                            </select>

                        </div>
                        <button type="button" class="layui-btn level_sumbit">修改</button>

                    </div>
                </div>
            </div>

        </div>
        @forelse($question as $key=>$value)
            <div class="layui-row">
                <div class="layui-col-xs6">
                    <div class="grid-demo grid-demo-bg1">
                        <div class="layui-form-item"  style="float: right;">
                            <label class="layui-form-label">密保问题</label>
                            <div class="layui-input-inline">
                                <input disabled type="text" value="{{$value["question"]['question']}}" lay-verify="required" autocomplete="off" class="layui-input layui-disabled">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-xs6" >
                    <div class="grid-demo">
                        <div class="layui-form-item" >
                            <label class="layui-form-label">密保答案</label>
                            <div class="layui-input-inline">
                                <input  name="user_gold"  id="change_mibao" type="text" autocomplete="off" value="{{$value["answer"]}}"  class="layui-input " >
                            </div>
                            <button type="button" data="{{$value["id"]}}" class="hange_mibao">修改</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="layui-row" style="text-align: center;font-size: 18px;color: red">用户未设置密保</div>
        @endforelse


        <input type="hidden" id="gao" value="{{$parent_fandain_msg["gao"]}}">
        <input type="hidden" id="di" value="{{$parent_fandain_msg["di"]}}">
    </form>
</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
                $ = layui.jquery,
                layer = layui.layer;

        //监听提交
        $("#user_result_detail,#user_result_detail1").click(function () {
            var user_id = "{{Request::input('user_id')}}";
            var url = "{{url("/manager/user_result_detail")}}?user_id="+user_id;
            parent.layer.open({
                type:2,
                title:'用户'+user_id+'盈亏详情',
                shadeClose:true,
                shade:0,
                area:['80%','90%'],
                content:url,
                skin:'accountOp',
            });
        });


        $("#user_history_detail,#user_history_detail1").click(function () {
            var user_id = "{{Request::input('user_id')}}";
            var url = "{{url("/manager/user_result_history_detail")}}?user_id="+user_id;
            parent.layer.open({
                type:2,
                title:'用户'+user_id+'盈亏详情',
                shadeClose:true,
                shade:0,
                area:['80%','90%'],
                content:url,
                skin:'accountOp',
            });
        });


        $("#user_withdraw_detail").click(function () {
            var user_id = "{{$user_id}}";
            var url = "{{url("/manager/user_withdraw_detail")}}?user_id="+user_id;
            parent.layer.open({
                type:2,
                title:'用户'+user_id+'提现详情',
                shadeClose:true,
                shade:0,
                area:['80%','90%'],
                content:url,
                skin:'accountOp',
            });
        });
        $("#user_recharge_detail").click(function () {
            var user_id = "{{$user_id}}";
            var url = "{{url("/manager/user_recharge_detail")}}?user_id="+user_id;
            parent.layer.open({
                type:2,
                title:'用户'+user_id+'充值详情',
                shadeClose:true,
                shade:0,
                area:['80%','90%'],
                content:url,
                skin:'accountOp',
            });
        });

        /**
         * 作用：修改等级
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".level_sumbit").click(function () {
//            var phone    = $("#change_phone").val().replace(/(^\s*)|(\s*$)/g,"");
            var user_id  = $("#user_id").val();

            var level = $("#level").val();
            if(level==""){
                $str = "确定修改等级"+level+"?";
            }else{
                $str = "确定修改等级"+level+"?";

            }
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/level_update",
                    data: {"user_id":user_id,"level":level},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });
        /**
         * 作用：修改提现倍数
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".withdrawal_update").click(function () {
//            var phone    = $("#change_phone").val().replace(/(^\s*)|(\s*$)/g,"");
            var user_id  = $("#user_id").val();

            var withdrawal = $("#withdrawal").val();
            if(withdrawal==""){
                $str = "确定修改提现倍数"+withdrawal+"?";
            }else{
                $str = "确定修改提现倍数"+withdrawal+"?";

            }
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/withdrawal_update",
                    data: {"user_id":user_id,"withdrawal":withdrawal},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });
        /**
         * 作用：修改密码
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".user_pwd").click(function () {
            var user_pwd        = $("#user_pwd").val();
            var username        = $("#username").val();
            var user_id         = $("#user_id").val();
            if(user_pwd=="" || user_pwd=="vip888"){
                user_pwd = "vip888";
                var str = "确认重置用户名为【"+username+"】此用户的密码么？重置后密码为【vip888】";
            }else{
                var reg = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,16}$/;
                if(!reg.test(user_pwd)){
                    layer.msg("密码格式错误请重新输入,密码格式由6-16位字母数字组成(除特殊字符)",{"icon":5});
                    return false;
                }
                var str = "确认修改用户名为【"+username+"】此用户的密码为【"+user_pwd+"】么？";
            }
            layer.confirm(str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/change_pwd",
                    data: {"user_id":user_id,"user_pwd":user_pwd},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });




        /**
         * 作用：修改资金密码
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".zijin_pwd").click(function () {
            var zijin_pwd       = $("#zijin_pwd").val();
            var username        = $("#username").val();
            var user_id         = $("#user_id").val();
            if(zijin_pwd=="" || zijin_pwd=="123456"){
                zijin_pwd = "123456";
                var str = "确认重置用户名为【"+username+"】此用户的密码么？重置后密码为【123456】";
            }else{
                var reg = /^\d{6}$/;
                if(!reg.test(zijin_pwd)){
                    layer.msg("资金密码格式错误请重新输入,资金密码格式由6位数字组成",{"icon":5});
                    return false;
                }
                var str = "确认修改用户名为【"+username+"】此用户的资金密码为【"+zijin_pwd+"】么？";
            }
            layer.confirm(str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/zijin_pwd",
                    data: {"user_id":user_id,"zijin_pwd":zijin_pwd},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });

        /*修改银行卡信息*/
        $(".mod_bank").click(function () {
            var bank_name       = $("#bank_name").val();
            var bank_account_name        = $("#bank_account_name").val();
            var bank_account         = $("#bank_account").val();
            var username        = $("#username").val();
            var user_id         = $("#user_id").val();
            if(bank_name=="" || bank_account_name==""||bank_account==""){
                layer.msg("请完整填写银行名称,持卡人姓名,银行卡号",{"icon":5});
                return false;
            }else{
                if(bank_account.length<8||bank_account.length>19){
                    layer.msg("请输入正确的银行卡信息",{"icon":5});
                    return false;
                }
                var str = "确认修改用户名为【"+username+"】此用户的银行卡信息么？";
            }
            layer.confirm(str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/mod_bankinfo",
                    data: {"user_id":user_id,"bank_name":bank_name,"bank_account_name":bank_account_name,"bank_account":bank_account},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });


        /**
         * 作用：修改高频彩返点
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".change_gao").click(function () {
            var old_fandain_gao = $("#old_fandain_gao").val();  /*旧返点*/
            var gao             = $("#gao").val();              /*最高返点限制*/
            var fandain_gao     = $("#change_gao").val();       /*设置的返点*/
            var username        = $("#username").val();
            var user_id         = $("#user_id").val();
            var str = "确认修改用户名为【"+username+"】此用户的高频彩返点为【"+fandain_gao+"】么？";
            if(fandain_gao-old_fandain_gao<0){
                layer.msg("高频彩返点不可低于之前返点【"+old_fandain_gao+"】",{"icon":2});
                return false;
            }
            if(fandain_gao-gao>=0){
                layer.msg("高频彩返点不可高于最高限制【"+gao+"】",{"icon":2});
                return false;
            }
            layer.confirm(str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/fandain_gao",
                    data: {"user_id":user_id,"fandain_gao":fandain_gao},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });







        /**
         * 作用：修改低频彩返点
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".change_di").click(function () {
            var old_fandain_di  = $("#old_fandain_di").val();  /*旧返点*/
            var di              = $("#di").val();              /*最高返点限制*/
            var fandain_di      = $("#change_di").val();       /*设置的返点*/
            var username        = $("#username").val();
            var user_id         = $("#user_id").val();
            var str = "确认修改用户名为【"+username+"】此用户的六合彩返点为【"+fandain_di+"】么？";
            if(fandain_di-old_fandain_di<0){
                layer.msg("六合彩返点不可低于之前返点【"+old_fandain_di+"】",{"icon":2});
                return false;
            }
            if(fandain_di-di>=0){
                layer.msg("六合彩返点不可高于最高限制【"+di+"】",{"icon":2});
                return false;
            }
            layer.confirm(str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/fandain_di",
                    data: {"user_id":user_id,"fandain_di":fandain_di},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });



        /**
         * 作用：修改真实姓名
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".change_name").click(function () {
            var name    = $("#change_name").val().replace(/(^\s*)|(\s*$)/g,"");
            var user_id  = $("#user_id").val();
            var username = $("#username").val();
            if(name==""){
                $str = "确认重置用户名为【"+username+"】此用户的真实姓名么？";
            }else{
                $str = "确认修改用户名为【"+username+"】此用户的真实姓名为【"+name+"】么？";
            }
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/change_name",
                    data: {"user_id":user_id,"name":name},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });



        /**
         * 作用：修改邮箱
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".change_email").click(function () {
            var email    = $("#change_email").val().replace(/(^\s*)|(\s*$)/g,"");
            var user_id  = $("#user_id").val();
            var username = $("#username").val();
            if(email==""){
                $str = "确认重置用户名为【"+username+"】此用户的邮箱信息么？";
            }else{
                var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$");
                if(!reg.test(email)){
                    layer.msg("邮箱格式不正确，请重新输入",{"icon":2});
                    return false;
                }
                $str = "确认修改用户名为【"+username+"】此用户的邮箱信息为【"+email+"】么？";
            }
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/change_email",
                    data: {"user_id":user_id,"email":email},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });






        /**
         * 作用：修改手机号码
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".change_phone").click(function () {
            var phone    = $("#change_phone").val().replace(/(^\s*)|(\s*$)/g,"");
            var user_id  = $("#user_id").val();
            var username = $("#username").val();
            if(phone==""){
                $str = "确认重置用户名为【"+username+"】此用户的手机号么？";
            }else{
                var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                if(!reg.test(phone)){
                    layer.msg("手机号格式不正确，请重新输入",{"icon":2});
                    return false;
                }
                $str = "确认修改用户名为【"+username+"】此用户的手机号为【"+phone+"】么？";
            }
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/change_mobile_number",
                    data: {"user_id":user_id,"phone":phone},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });

        /**
         * 作用：修改备注
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(".change_remarks").click(function () {
            var remarks   = $("#remarks").val();
            var user_id  = $("#user_id").val();
            $str = "确认修改用户备注修改【"+remarks+"】么？";
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/change_remarks",
                    data: {"user_id":user_id,"remarks":remarks},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });


        $(".change_mibao").click(function () {
            var id = $(this).attr("data");
            var answer = $("#change_mibao").val().replace(/(^\s*)|(\s*$)/g,"");
            if(answer==""){
                layer.msg("密保答案不可为空",{"icon":2});
                return false;
            }
            var $str = "确定修改此用户密保答案为【"+answer+"】么？";
            layer.confirm($str,function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/change_mibao",
                    data: {"id":id,"answer":answer},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.closeAll("loading");
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(){
                                location.reload();
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })
        });




        /**
         * 作用：修改信息
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $("#modify_user").click(function () {
            var phone_number    = $("#phone_number").val();
            var wx_number       = $("#wx_number").val();
            var email_numebr    = $("#email_number").val();
            // var id_number       = $("#id_number").val();
            var uname           = $("#uname").val();
            var profit_status   = $("input[name='profit_status']:checked").val();

            var mima            = $("#mima").val().replace(/^\s+|\s+$/g, '');
            var zijimima        = $("#zijimima").val().replace(/^\s+|\s+$/g, '');
            var yuer            = $("#yuer").val();
            var dongjie         = $("#dongjie").val();


            if(mima != ""){
                if(!/^[Za-z0-9_]{6,18}$/.test(mima)){
                    layer.alert("请输入6-18位字母数据密码格式，除特殊字符");
                    return false;
                }
            }
            if(zijimima != ""){
                if(!/^[Za-z0-9_]{6,18}$/.test(zijimima)){
                    layer.alert("请输入6-18位字母数据资金密码格式，除特殊字符");
                    return false;
                }
            }



            /*旧返点*/
            var old_fandain_gao = $("#old_fandain_gao").val();
            var old_fandain_di  = $("#old_fandain_di").val();
            /*最高返点限制*/
            var gao     = $("#gao").val();
            var di      = $("#di").val();
            /*设置的返点*/
            var fandain_gao = $("#fandain_gao").val();
            var fandain_di  = $("#fandain_di").val();

            if(fandain_gao-old_fandain_gao<0){
                layer.alert("高频彩返点不能低于之前的返点"+old_fandain_gao);
                return false;
            }
            if(fandain_di-old_fandain_di<0){
                layer.alert("六合彩彩返点不能低于之前的返点"+old_fandain_di);
                return false;
            }

            if(fandain_gao-gao>=0){
                layer.alert("高频彩返点不能高于最高设置返点"+gao);
                return false;
            }
            if(fandain_di-di>=0){
                layer.alert("六合彩返点不能高于最高设置返点"+di);
                return false;
            }



            var user_id = '{{$user_detail[0]['user_id']}}';
            var url = "{{url('manager/modify_user_ajax')}}";
            var data = {
                "uname"         :uname,
                // "id_number"     :id_number,
                "phone_number"  :phone_number,
                "wx_number"     :wx_number,
                "email_number"  :email_numebr,
                "user_id"       :user_id,
                "profit_status" :profit_status,
                "mima"          :mima,
                "zijimima"      :zijimima,
                "yuer"          :yuer,
                "dongjie"       :dongjie,
                "fandain_gao"   :fandain_gao,
                "fandain_di"    :fandain_di

            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.flag) {
                        layer.alert(res.msg, {icon: 1,shade:0},function(){
                            location.reload();
                        });
                    }
                    else{
                        layer.alert(res.msg, {icon: 2},function(){
                            location.reload();
                        });
                    }
                }
            });
        });


        /**
         * 作用：重置密码
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(document).on("click","#resetPwd",function () {
            layer.confirm("确定重置该用户的密码吗？初始密码为vip888",function () {
                var user_id = '{{$user_detail[0]['user_id']}}';
                $.ajax({
                    type:"post",
                    url:"{{route('manager.resetpwd')}}",
                    data:{"id":user_id},
                    headers:{
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success:function (res) {
                        if(res.code){
                            layer.msg(res.msg);
                            location.reload();
                            return false;
                        }
                        layer.msg(res.msg);
                    },
                    error:function (err) {
                        layer.msg("很抱歉，系统异常，请稍后再试");
                    }
                })
            })
        })




        /**
         * 作用：重置邮箱
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(document).on("click","#resetzijin_youxiang",function () {
            layer.confirm("确定重置该用户的邮箱吗？重置后邮箱将清空",function () {
                var user_id = '{{$user_detail[0]['user_id']}}';
                $.ajax({
                    type:"post",
                    url:"{{route('manager.resetyouxiang')}}",
                    data:{"id":user_id},
                    headers:{
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success:function (res) {
                        if(res.code){
                            layer.msg(res.msg);
                            location.reload();
                            return false;
                        }
                        layer.msg(res.msg);
                    },
                    error:function (err) {
                        layer.msg("很抱歉，系统异常，请稍后再试");
                    }
                })
            })
        })



        /**
         * 作用：重置资金密码
         * 作者：信
         * 时间：2018/03/28
         * 修改：暂无
         */
        $(document).on("click","#resetzijinPwd",function () {
            layer.confirm("确定重置该用户的资金密码吗？初始密码为vip999",function () {
                var user_id = '{{$user_detail[0]['user_id']}}';
                $.ajax({
                    type:"post",
                    url:"{{route('manager.resetzijinpwd')}}",
                    data:{"id":user_id},
                    headers:{
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success:function (res) {
                        if(res.code){
                            layer.msg(res.msg);
                            location.reload();
                            return false;
                        }
                        layer.msg(res.msg);
                    },
                    error:function (err) {
                        layer.msg("很抱歉，系统异常，请稍后再试");
                    }
                })
            })
        })

    });

</script>

</body>

</html>