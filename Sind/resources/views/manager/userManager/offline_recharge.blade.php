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
        <legend>充值记录</legend>
    </fieldset>
    <input type="hidden" id="now_game" value="{{ Request::input('game_id')?Request::input('game_id'):"01"}}">
    <div class="body_right">
        <div >
            <ul id="category_ul">
                <li><label class="layui-form-label"  style="color: #0C0C0C">快捷选时：</label>
                    <button data="1" class="kj_time layui-btn <?php if($time != 1)echo "layui-btn-primary";?>">今天</button>
                    <button data="2" class="kj_time layui-btn <?php if($time != 2)echo "layui-btn-primary";?>">昨天</button>
                    <button data="3" class="kj_time layui-btn <?php if($time != 3)echo "layui-btn-primary";?>">近三天</button>
                    <button data="7" class="kj_time layui-btn <?php if($time != 7)echo "layui-btn-primary";?>">近七天</button>
                    <button data="15" class="kj_time layui-btn <?php if($time != 15)echo "layui-btn-primary";?>">近半月</button>
                    <button data="30" class="kj_time layui-btn <?php if($time != 30)echo "layui-btn-primary";?>">近一月</button>
                </li>
            </ul>
        </div>

        <div  style="margin-top: 10px">
            <ul id="category_ul">
                <li style="height: 40px">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label"  style="color: #0C0C0C">用户名：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$user_id}}" id="user_id" class="layui-input" placeholder="请输入用户名">
                                </div>

                                <label class="layui-form-label" style="color: #0C0C0C">日期范围：</label>
                                <div class="layui-input-inline">
                                    <input type="text" value="{{$near_time}}" class="layui-input" id="test6" placeholder=" - ">
                                </div>


                                <label class="layui-form-label" style="color: #0C0C0C">状态：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="status" name="status">
                                        <option value=""  <?php if($status=="")echo 'selected=""'; ?> >全部</option>
                                        <option value="1" <?php if($status=="1")echo 'selected=""'; ?> >审核中</option>
                                        <option value="2" <?php if($status=="2")echo 'selected=""'; ?> >充值成功</option>
                                        <option value="3" <?php if($status=="3")echo 'selected=""'; ?> >充值失败</option>
                                    </select>
                                </div>
                                <label class="layui-form-label" style="color: #0C0C0C">通道：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="rn" name="rn">
                                        <option value=""  <?php if($status=="")echo 'selected=""'; ?> >全部</option>
                                        @foreach($newrecharge as $key1=>$value1)
                                            <option value="{{$newrecharge[$key1]['id']}}" <?php if($rn==$value1['id'])echo 'selected=""'; ?> >{{$newrecharge[$key1]['name']}}</option>

                                        @endforeach
                                    </select>
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
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md2">
                    <div class="info-box">
                        <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" style="font-weight: bold;font-size:large">线下充值总金额:</span>
                                <span class="info-box-number">
                                    <span style="color: red;">{{number_format($weixin[0]["recharge_money"],2)}}</span>
                                </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="layui-table">
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                <tr style=" border-top: 1px solid black">
                    <th class="tdcenter">订单ID</th>
                    <th class="tdcenter">用户名</th>
                    <th class="tdcenter">是否虚拟账号</th>
                    <th class="tdcenter">充值金额</th>
                    <th class="tdcenter">充值通道</th>
                    <th class="tdcenter">充值方式</th>
                    <th class="tdcenter">平台银行卡名</th>
                    <th class="tdcenter">平台银行卡号</th>
                    <th class="tdcenter">平台持卡人</th>
                    <th class="tdcenter">充值信息</th>
                    <th class="tdcenter">状态</th>
                    <th class="tdcenter">订单时间</th>
                    <th class="tdcenter">操作时间</th>
                    <th class="tdcenter" >操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key=>$value)
                    <tr style=" border-top: 1px solid black">
                        <input type="hidden" id="user_ids" value="{{$value["user_id"]}}">
                        <td  id="id" class="tdcenter" ids="{{$value["id"]}}">{{$value["id"]}}</td>
                        <td class="tdcenter">{{$value["username"]}}</td>
                        <td class="tdcenter">{{$value["is_fictitious"]}}</td>
                        <td id="recharge_money" class="tdcenter" recharge_money="{{$value["recharge_money"]}}" >{{$value["recharge_money"]}}</td>
                        <td class="tdcenter">{{$value["recharge_name"]}}</td>
                        <td class="tdcenter">
                            @if($value["recharge_type"]==1)
                                银行卡充值
                             @elseif($value["recharge_type"]==3)
                                微信充值
                            @elseif($value["recharge_type"]==2)
                                支付宝充值
                             @endif
                        </td>
                        <td class="tdcenter">{{$value["bank_name"]}}</td>
                        <td class="tdcenter">{{$value["bank_id"]}}</td>
                        <td class="tdcenter">{{$value["bank_username"]}}</td>
                        <td class="tdcenter">{{$value["recharge_conent"]}}</td>

                        <td class="tdcenter">
                            @if($value["status"]==1)
                                审核中
                            @elseif($value["status"]==2)
                                充值成功
                            @elseif($value["status"]==3)
                                充值失败
                            @endif
                        </td>

                        <td class="tdcenter">{{$value["create_time"]}}</td>
                        <td class="tdcenter">{{$value["update_time"]}}</td>

                        <td class="tdcenter">
                            @if($value["status"]==1 )
                                <button class="layui-btn layui-btn-small chongzhichuli" recharge_id="{{$value["recharge_id"]}}" recharge_money="{{$value["recharge_money"]}}" data="{{$value["id"]}}" uid="{{$value["user_id"]}}">同意</button>
                                <button class="layui-btn layui-btn-small jujue" data="{{$value["id"]}}" uid="{{$value["user_id"]}}">拒绝</button>
                                <button class="layui-btn layui-btn-small update_money" recharge_id="{{$value["recharge_id"]}}" recharge_money="{{$value["recharge_money"]}}" data="{{$value["id"]}}" uid="{{$value["user_id"]}}">修改金额</button>


                            @endif
                            {{--<button class="layui-btn layui-btn-small dele" data="{{$value["id"]}}" uid="{{$value["user_id"]}}">删除</button>--}}
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
        //修改金额
        $('.update_money').click(function () {
            var money = $(this).attr("recharge_money");
            var id = $(this).attr('data');
            var user_id = $(this).attr('uid');
            layer.open({
                type: 2,
                title: '修改金额',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.offline.edit") }}?id='+id+'&recharge_money='+money,
            });
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
            var val     =   $("#test6").val().replace(/(^\s*)|(\s*$)/g, "");
            var user_id =   $("#user_id").val().replace(/(^\s*)|(\s*$)/g, "");
//            var leixing =   $("#leixing").val();
            var status =   $("#status").val();
            var rn =   $("#rn").val();
            location.href = "{{url('manager/offline.index') }}?val="+val+"&user_id="+user_id+"&status="+status+"&rn="+rn;
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
        $(document).on("click",".chongzhichuli",function () {
            if(confirm("确定此操作吗")){

                var money = $(this).attr("recharge_money");
                var id = $(this).attr('data');
                var user_id = $(this).attr('uid');
                var recharge_id = $(this).attr("recharge_id");

                $.ajax({
                    type: "POST",
                    url: "/manager/offline_chuli",
                    data: {"money":money,"user_id":user_id,"id":id,"type":1,"recharge_id":recharge_id},
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
            }

        })

        /*处理拒绝请求*/
        $(document).on("click",".jujue",function () {
//            var money = $("#recharge_money").attr("recharge_money");
            var money = $(this).attr("recharge_money");
            var id = $(this).attr('data');
            var user_id = $(this).attr('uid');
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
            var money = $(this).attr("recharge_money");
            var id = $(this).attr('data');
            var user_id = $(this).attr('uid');
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