<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>用户管理</title>
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
        line-height: 0;
        display: inline-block;
        text-align: justify;
    }
    .text_div1{
        height: 30px;
        width: 75px;
        line-height: 0;
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
    .thcenter{
        text-align: center;
    }
    .icon_shang{
        color: gray;
    }
    .icon_xia{
        color: gray;
    }
    .choosewidth2{
        width: 100px;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>用户管理</legend>
    </fieldset>
    <form class="layui-form"  method="post" action="{{url('manager/getUser.index')}}" style="margin: 0 30px 20px 30px;">
        {{ csrf_field() }}
        <div class="layui-inline" style="margin-left: 20px">
            <span>用户名：</span>
            <input id="user_name" value="{{$user_id}}" placeholder="请输入用户名" name="user_name" type="text" class="layui-input username_input clear_val">
        </div>
        <div class="layui-inline choosewidth">
            <label class="layui-form-label">用户状态：</label>
            <div class="layui-input-inline choosewidth2">
                <select name="user_state"  class="clear_val" id="user_state">
                    <option value="2" <?php if($user_state=="2") echo 'selected=""'?>>全部</option>
                    <option value="1" <?php if($user_state=="1") echo 'selected=""'?>>可登录</option>
                    <option value="0" <?php if($user_state=="0") echo 'selected=""'?>>禁用</option>
                </select>
            </div>
        </div>


        <div class="layui-inline choosewidth" style="margin-right: 40px">
            <label class="layui-form-label">账号类型：</label>
            <div class="layui-input-inline choosewidth2">
                <select name="is_fictitious" class="clear_val" id="is_fictitious">
                    <option value="2" <?php if($is_fictitious=="2") echo 'selected=""'?>>全部</option>
                    <option value="0" <?php if($is_fictitious=="0") echo 'selected=""'?>>会员</option>
                    <option value="1" <?php if($is_fictitious=="1") echo 'selected=""'?>>虚拟账号</option>
                    <option value="3" <?php if($is_fictitious=="3") echo 'selected=""'?>>代理</option>
                </select>
            </div>
        </div>
        <input type="submit" class="layui-btn" value="查询">
        <input type="button" class="layui-btn clear" value="清空选择">
        <a href="{{url('manager/getUser.index')}}" class="layui-btn" style="float: right">刷新</a>
    </form>
    <div class="layui-form">
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">用户名</th>
                    <th style="text-align: center">用户ID</th>
                    <th style="text-align: center">上级</th>
                    <th style="text-align: center">余额
                        <span data="asc"  data2="account.remaining_money" style="<?php if($order=='asc' && $name=='account.remaining_money')echo 'color:red'; ?>" class="icon_shang money_order">▲</span>
                        <span data="desc" data2="account.remaining_money" class="icon_xia money_order" style="margin-left: -10px!important;<?php if($order=='desc' && $name=='account.remaining_money')echo 'color:red'; ?>">▼</span>
                    </th>
                    <th style="text-align: center">用户状态</th>
                    <th style="text-align: center">员工账号</th>
                    <th style="text-align: center">注册时间</th>
                    <th style="text-align: center">最后登录时间</th>
                    <th style="text-align: center">最后登录IP</th>
                    <th style="text-align: center">操作</th>
                </tr>
            </thead>
            <tbody>
            @forelse($data as $key=>$val)
                <tr>
                    <td>{{$val["zhanghao"]}}</td>
                    <td>{{$val["user_id"]}}</td>
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
                    <td>
                        @if($val["is_fictitious"] &&  $val['role_id']==1)
                            <span style="color: #00b7ee">虚拟号</span>

                        @elseif($val['role_id']==2)
                            <span style="color: red">代理</span>
                        @else
                            会员
                        @endif
                    </td>
                    <td>{{$val["register_time"]}}</td>
                    <td>{{$val["last_login_time"]}}</td>
                    <td>{{$val["last_login_ip"]}}</td>
                    <td>
                        @if($val["is_fictitious"]!=1)
                        <button data="{{$val["user_id"]}}" class="layui-btn-primary layui-btn-small modAgent">更换代理</button>
                        @endif
                        <button data="{{$val["user_id"]}}" class="layui-btn-primary layui-btn-small tongji">统计</button>
                        <button data="{{$val["user_id"]}}" class="layui-btn-small layui-btn-primary recharge ">充值</button>
                        <button data="{{$val["user_id"]}}" class="layui-btn-small layui-btn-primary lowerScore ">下分</button>
                        <button data="{{$val["user_id"]}}" class="layui-btn layui-btn-small getUser_detail">编辑</button>
                        <input type="hidden" value="{{$val['user_id']}}">
                        @if($val["status"])
                            <input type="hidden" value="{{$val['user_id']}}">
                            <button data="{{$val["user_id"]}}" class="layui-btn layui-btn-small change_state">禁用</button>
                            @else
                            <input type="hidden" value="{{$val['user_id']}}">
                            <button data="{{$val["user_id"]}}" class="layui-btn layui-btn-danger layui-btn-small change_state">启用</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11">暂无数据</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{$data->appends([
            "user_id"       => $user_id,
            "user_state"    => $user_state,
            "is_fictitious"     => $is_fictitious,
            "order"         => $order,
            "name"          => $name,
        ])->links()}}
    </div>
    <audio  display="none" src="/sound/excess.mp3"  id="warning_tone">111</audio>
</div>
<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;
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
                area: ['40%', '50%'],
                maxmin: true,
                content: '{{ url("manager/becomeAgent") }}?user_id='+user_id,
            });
        });


        /*清空选择*/
        $(document).on("click",".clear",function () {
           $(".clear_val").val('');
            form.render()
        });


        /**
         * 作用：踢下线
         * 作者：信
         * 时间：2018/06/13
         * 修改：暂无
         */
        $(document).on("click",".tixiaxian",function () {
           var user_id = $(this).attr("data");
           layer.confirm("确定要将此用户踢下线吗？请谨慎操作！",function () {
               layer.load(1);
               $.ajax({
                   type: "POST",
                   url: "/manager/user_tixiaxian",
                   data: {"user_id":user_id},
                   dataType: "json",
                   headers: {
                       'X-CSRF-TOKEN': '{{ csrf_token() }}'
                   },
                   success: function(res){
                       if(res.code) {
                           layer.msg(res.msg, {icon: 1},function(index){
                               location.reload();
                           });
                       }
                       else{
                           layer.msg(res.msg, {icon: 2},function(index){
                               location.reload();
                           });
                       }
                   },
                   error:function (err) {
                       layer.msg("系统异常，请稍后再试！", {icon: 2});
                   }
               });
               layer.closeAll('loading');
           })
        });



        /**
         * 作用：各种排序
         * 作者：信
         * 时间：2018/06/13
         * 修改：暂无
         */
        $(document).on("click",".money_order",function () {
           var order        = $(this).attr("data");
           var name         = $(this).attr("data2");
           var user_name    = $("#user_name").val();
           if(!user_name){
               user_name = "";
           }
           var user_state   = $("#user_state").val();
           var is_fictitious = $("#is_fictitious").val();
           location.href ='{{url("manager/getUser.index")}}?order='+order+"&user_name="+user_name+"&user_state="+user_state+"&name="+name+"&is_fictitious="+is_fictitious;
        });



        /**
         * 作用：充值
         * 作者：
         * 时间：
         * 修改：信
         * 修改时间：2018/04/08
         */
        $(".recharge").click(function () {
            var user_id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '充值',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '70%'],
                maxmin: true,
                content: '{{ url("manager/recharge") }}?user_id='+user_id,
            });
        });
        /**
         * 作用：下分
         * 作者：
         * 时间：
         * 修改：信
         * 修改时间：2018/04/08
         */
        $(".lowerScore").click(function () {
            var user_id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '下分',
                shadeClose: true,
                shade: 0.8,
                area: ['40%', '70%'],
                maxmin: true,
                content: '{{ url("manager/lowerScore") }}?user_id='+user_id,
            });
        });

        /**
         * 作用：统计
         * 作者：
         * 时间：
         * 修改：信
         * 修改时间：2018/04/08
         */
        $(".tongji").click(function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '数据统计',
                shadeClose: true,
                shade: 0.8,
                area: ['55%', '70%'],
                maxmin: true,
                content: '{{ url("manager/tongji") }}?id='+id,
            });
        });



        /**
         * 作用：升级为代理
         * 作者：信
         * 时间：2018/06/13
         * 修改：信
         */
        $(".shengji").click(function () {
            var user_id = $(this).attr("data");
            var username = $(this).attr("data2");
            layer.confirm("确认升级用户【"+username+"】为【代理】么？",function () {
                layer.load(1);
                $.ajax({
                    type: "POST",
                    url: "/manager/shengji",
                    data: {"user_id":user_id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(index){
                                location.reload();
                            });
                        }
                        else{
                            layer.msg(res.msg, {icon: 2});
                        }
                        layer.closeAll("loading");
                    },
                    error:function (err) {
                        layer.closeAll("loading");
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                    }
                });
            })

        });


        /**
         * 作用：用户详情
         * 作者：
         * 时间：
         * 修改：信
         * 修改时间：2018/04/09
         */
        $('.getUser_detail').click(function () {
            var user_id = $(this).attr("data");
            var url = "{{ url('manager/getUser_detail')}}?user_id="+user_id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'用户编辑',
                    shadeClose:true,
                    shade:0,
                    area:['70%','90%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        });

        $(".modAgent").click(function(){
            var user_id = $(this).attr("data");
            layer.prompt({title: '请输入您要更换的代理名称', formType: 3}, function(pass, index){
                $.ajax({
                    type: "POST",
                    url: "{{route("manager.modAgent")}}",
                    data: {agentId:pass,userId:user_id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code) {
                            layer.msg(res.msg, {icon: 1},function(index){
                                location.reload();
                            });
                        }
                        else{
                            layer.msg(res.msg, {icon: 2},function(index){
                                location.reload();
                            });
                        }
                        layer.closeAll("loading");
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                        layer.closeAll("loading");
                    }
                });
                layer.close(index);
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
            location.href = '{{ url("manager/getUser.index") }}?user_type={{ Request::input("user_type") }}&leader_id={{ Request::input("leader_id") }}&column='+column+'&sort='+sort;
        })


        /**
         * 作用：禁用or启用
         * 作者：
         * 时间：
         * 修改：信
         * 修改时间：2018/04/08
         */
        $(".change_state").click(function () {
            var state = $(this).text();
            var user_id = $(this).prev().val();

            if(state=='禁用') {
                var user_state = 0;
            } else {
                var user_state = 1;
            }
            var data = {state:user_state,user_id:user_id}
            var url = "{{url("/manager/user_state_ajax")}}";
            layer.confirm("确定"+state+"此用户吗？",function () {
                layer.load(1);
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
                            layer.msg(res.msg, {icon: 1},function(index){
                                location.reload();
                            });
                        }
                        else{
                            layer.msg(res.msg, {icon: 2},function(index){
                                location.reload();
                            });
                        }
                        layer.closeAll("loading");
                    },
                    error:function (err) {
                        layer.msg("系统异常，请稍后再试", {icon: 2});
                        layer.closeAll("loading");
                    }
                });
            });
        });


    });
</script>

</body>

</html>