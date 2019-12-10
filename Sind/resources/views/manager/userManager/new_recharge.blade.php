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
        <legend>充值通道</legend>
    </fieldset>
    <div class="add_div">
    </div>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">


        <div  style="margin-top: 10px">
            <ul id="category_ul">
                <li style="height: 40px">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <div class="layui-inline">


                                <label class="layui-form-label" style="color: #0C0C0C">类型：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="leixing" name="leixing">
                                        <option value=""  <?php if($leixing=="")echo 'selected=""'; ?> >全部</option>
                                        <option value="1" <?php if($leixing=="1")echo 'selected=""'; ?> >银行卡</option>
                                        <option value="2" <?php if($leixing=="2")echo 'selected=""'; ?> >支付宝</option>
                                        <option value="3" <?php if($leixing=="3")echo 'selected=""'; ?> >微信</option>
                                    </select>
                                </div>
                                <label class="layui-form-label" style="color: #0C0C0C">状态：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="status" name="status">
                                        <option value=""  <?php if($status=="")echo 'selected=""'; ?> >全部</option>
                                        <option value="1" <?php if($status=="1")echo 'selected=""'; ?> >开启</option>
                                        <option value="0" <?php if($status=="0")echo 'selected=""'; ?> >关闭</option>
                                    </select>
                                </div>
                                <button id="select" class="layui-btn">查询</button>
                                <button id="clear" class="layui-btn">清空选择</button>
                                <input id="add" type="button" class="layui-btn layui-btn" value="新增通道">

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
                    <th class="tdcenter">ID</th>
                    <th class="tdcenter">名称</th>
                    <th class="tdcenter">支付类型</th>
                    <th class="tdcenter">icon</th>
                    <th class="tdcenter">支付二维码</th>
                    <th class="tdcenter">银行名</th>
                    <th class="tdcenter">开户行</th>
                    <th class="tdcenter">卡号</th>
                    <th class="tdcenter">持卡人</th>
                    <th class="tdcenter">排序</th>
                    <th class="tdcenter">是否推荐</th>
                    <th class="tdcenter">状态</th>
                    <th class="tdcenter">最小金额</th>
                    <th class="tdcenter">最大金额</th>
                    <th class="tdcenter">备注</th>
                    <th class="tdcenter" >操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key=>$value)
                    <tr style=" border-top: 1px solid black">
                        <input type="hidden" id="id" value="{{$value["id"]}}">
                        <td class="tdcenter">{{$value["id"]}}</td>
                        <td class="tdcenter">{{$value["name"]}}</td>
                        <td class="tdcenter">
                            @if($value["type"]==1 )
                                  银联
                             @elseif($value["type"]==2 )
                                支付宝
                             @elseif($value["type"]==3 )
                                微信
                            @endif

                        </td>
                        <td id="" class="tdcenter"  >
                            <img src="{{$value["icon"]}}" />
                        </td>
                        <td class="tdcenter">
                            <img src="{{$value["pay_img"]}}" />
                        </td>
                        <td class="tdcenter">{{$value["bank_name"]}}</td>
                        <td class="tdcenter">{{$value["orgain_bank"]}}</td>
                        <td class="tdcenter">{{$value["bank_id"]}}</td>
                        <td class="tdcenter">{{$value["bank_username"]}}</td>
                        <td class="tdcenter">{{$value["order"]}}</td>
                        <td class="tdcenter">{{$value["is_hot"]}}</td>

                        <td class="tdcenter">
                            @if($value["status"]==1)
                                开启
                            @else
                                关闭
                            @endif
                        </td>


                        <td class="tdcenter">{{$value["min"]}}</td>
                        <td class="tdcenter">{{$value["max"]}}</td>
                        <td class="tdcenter">{{$value["remary"]}}</td>

                        <td class="tdcenter">
                            @if($value["status"]==1 )

                                {{--<button class="layui-btn layui-btn-small jujue" data="{{$value["id"]}}">关闭</button>--}}

                            @endif
                                {{--<button class="layui-btn layui-btn-small jujue" data="{{$value["id"]}}">开启</button>--}}
                                <button class="layui-btn config-btn layui-btn-small" config_id="{{ $value['id'] }}">编辑</button>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$data->appends([
                "time"      => Request::input("time"),
                "user_id"   => Request::input("user_id"),
                "val"       => Request::input("val"),
                "leixing"       => Request::input("leixing")
            ])->links()}}
        </div>
    </div>
</div>

<audio  id="audio_mp3"   loop="loop">
    <source src="/audio/message.mp3" type="audio/mpeg">
    <source src="/audio/message.ogg" type="audio/ogg">
    <embed height="100" width="100" src="/audio/message.mp3" />
</audio>

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
                title: '通道编辑',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.recharge_edit.index") }}?id='+config_id,
            });
        });
        $('#add').click(function () {
//            var config_id = $(this).attr('config_id');
            layer.open({
                type: 2,
                title: '通道添加',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.recharge_add.index") }}',
            });
        });
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

        if($(".select_game").length>1){
            $($(".select_game")[0]).removeClass('select_game');
        }
        if($(".layui-this").length>1){
            $($(".layui-this")[0]).removeClass('layui-this');
        }

        var game_td_length = $(".game_td").length-1;
        $($(".game_td")[game_td_length]).addClass('last_game_td');


        /*日期*/
        layui.laydate.render({
            elem: '#test6'
            ,range: true
        });

        /*清空选择*/
        $("#clear").click(function () {
            $("#user_id").val("");
            $("#test6").val("");
            $("#leixing").val("");
            form.render()
        });







        /* var audio = document.getElementById('audio_mp3');
         /!*实时提示消息*!/
         setInterval(function () {
         $.ajax({
         type: "POST",
         url: "{{url('manager/chongzhi_tishi')}}" ,
         dataType: "json",
         headers: {
         'X-CSRF-TOKEN': '{{ csrf_token() }}'
         },
         success: function(res){
         if(res.code) {
         var id = res.id;
         layer.alert('有人充值啦', {icon: 6,offset: 'rb',shade:0},function () {
         chongzhichuli(id);
         });
         audio.play();
         }else{
         audio.pause();
         }
         }
         });
         },1000);*/



        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/offline.index') }}?time="+$data;
        });


        /**
         * 作用：日期范围
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         * 注意：一定要传一个time过去防止页面报错
         */
        $(document).on("click","#select",function () {
//            var val     =   $("#test6").val().replace(/(^\s*)|(\s*$)/g, "");
//            var user_id =   $("#user_id").val();
            var leixing =   $("#leixing").val();
            var status =   $("#status").val();
            location.href = "{{url('manager/recharge_lis') }}?leixing="+leixing+"&status="+status;
        });

        $(document).on("click",".delet",function () {
            let id = $(this).attr("data");
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.confirm('确认删除该条充值订单吗？', function(index){
                    var index = layer.load(1, {
                        shade: [0.1, '#fff'] //0.1透明度的白色背景
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{url('manager/user_chongzhi.deletOrder')}}" ,
                        dataType: "json",
                        data:{id:id},
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(res){
                            layer.close(index);
                            layer.alert(res.msg, {
                                closeBtn: 0
                            },function(){
                                window.location.reload();
                            });

                        }
                    });

                });

            })
        });


        /*点击充值处理*/
        {{--$(document).on("click",".chongzhichuli",function () {--}}
        {{--var id = $(this).attr("data");--}}
        {{--var url = "{{ url('manager/chongzhichuli')}}";--}}
        {{--url     = url+"?id="+id;--}}
        {{--layui.use('layer',function(){--}}
        {{--var layer=layui.layer;--}}
        {{--layer.open({--}}
        {{--type:2,--}}
        {{--title:'充值处理',--}}
        {{--shadeClose:true,--}}
        {{--shade:0,--}}
        {{--area:['50%','80%'],--}}
        {{--content:url,--}}
        {{--skin:'accountOp',--}}
        {{--})--}}
        {{--})--}}
        {{--})--}}
        /*处理同意请求*/
        $(document).on("click",".xiugai",function () {
            var money = $("#recharge_money").attr("recharge_money");
            var id = $("#id").attr("ids");
            var user_id = $("#user_ids").val();
//            alert(money)
            $.ajax({
                type: "POST",
                url: "/manager/offline_chuli",
                data: {"money":money,"user_id":user_id,"id":id,"type":1},
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.msg(res.msg);

                    location.reload();

                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            });
        })
        /*修改金额*/
        $(document).on("click",".chong_update",function () {
            var money = $("#recharge_money").attr("recharge_money");
            var id = $("#id").attr("ids");
            var user_id = $("#user_ids").val();
//            alert(money)
            $.ajax({
                type: "POST",
                url: "/manager/chong_update",
                data: {"money":money,"user_id":user_id,"id":id,"type":1},
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.msg(res.msg);

                    location.reload();

                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            });
        })
        /*处理拒绝请求*/
        $(document).on("click",".jujue",function () {
//            var money = $("#recharge_money").attr("recharge_money");
            var id = $("#id").attr("ids");
            var user_id = $("#user_ids").val();
//            alert(money)
            $.ajax({
                type: "POST",
                url: "/manager/offline_chuli",
                data: {"user_id":user_id,"id":id,"type":2},
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.msg(res.msg);

                    window.location.reload();

                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            });
        })
        /*处理删除请求*/
        $(document).on("click",".dele",function () {
//            var money = $("#recharge_money").attr("recharge_money");
            var id = $("#id").attr("ids");
            var user_id = $("#user_ids").val();
//            alert(money)
            $.ajax({
                type: "POST",
                url: "/manager/offline_chuli",
                data: {"user_id":user_id,"id":id,"type":3},
                headers:{
                    "X-CSRF-TOKEN":"{{ csrf_token()}}"
                },
                success: function(res){
                    layer.msg(res.msg);

                    location.reload();

                },
                error:function (err) {
                    layer.msg("系统异常，请稍后再试")
                }
            });
        })
    });





</script>

</body>

</html>