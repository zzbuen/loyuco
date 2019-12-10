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

                                <label class="layui-form-label" style="color: #0C0C0C">类型：</label>
                                <div class="layui-input-inline">
                                    <select class="layui-select" id="leixing" name="leixing">
                                        <option value=""  <?php if($leixing=="")echo 'selected=""'; ?> >全部</option>
                                        <option value="1" <?php if($leixing=="1")echo 'selected=""'; ?> >第三方充值</option>
                                        {{--<option value="3" <?php if($leixing=="3")echo 'selected=""'; ?> >手动充值</option>--}}
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
            <table class="table-body layui-table" cellspacing="0" cellpadding="0" border="0" id="table">
                <thead>
                    <tr style=" border-top: 1px solid black">
                        <th class="tdcenter">订单ID</th>
                        <th class="tdcenter">第三方订单号</th>
                        <th class="tdcenter">交易编号</th>
                        <th class="tdcenter">用户名</th>
                        <th class="tdcenter">充值金额</th>
                        <th class="tdcenter">充值前余额</th>
                        <th class="tdcenter">账户当前余额</th>
                        <th class="tdcenter">充值方式</th>
                        <th class="tdcenter">充值通道</th>
                        <th class="tdcenter">状态</th>
                        {{--<th class="tdcenter">首充</th>--}}
                        <th class="tdcenter">付款名</th>
                        <th class="tdcenter">订单时间</th>
                        <th class="tdcenter">操作时间</th>
                        <th class="tdcenter" >操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key=>$value)
                        <tr style=" border-top: 1px solid black">
                            <td class="tdcenter">{{$value["id"]}}</td>
                            <td class="tdcenter">{{$value["bank_sn"]}}</td>
                            <td class="tdcenter">{{$value["trade_sn"]}}</td>
                            <td class="tdcenter">{{$value["user"]["username"]}}</td>
                            <td class="tdcenter" style="color: cornflowerblue">{{$value["trade_amount"]}}</td>
                            <td class="tdcenter">{{$value["old_money"]}}</td>
                            <td class="tdcenter">{{$value["account"]["remaining_money"]}}</td>
                            <td class="tdcenter">
                                {{$value["trade_info"]}}
                            </td>
                            <td class="tdcenter">
                                {{$value["recharge_name"]}}
                            </td>
                            <td class="tdcenter">
                                @switch($value["trade_state"])
                                    @case(0)
                                    @case(1)
                                    @if(time()-strtotime($value['trade_time'])<=300)
                                     处理中
                                    @else
                                        交易取消
                                    @endif
                                    @break
                                    @case(2)
                                成功
                                    @break
                                    @case(3)
                                <span style="color: red">异常</span>
                                    @break
                                    @case(4)
                                交易取消
                                    @break
                                @endswitch
                            </td>
                            {{--<td class="tdcenter">--}}
                                {{--@if($value["info"]["first_charge"])--}}
                                    {{--否--}}
                                    {{--@else--}}
                                    {{--是--}}
                                {{--@endif--}}
                            {{--</td>--}}
                            <td class="tdcenter">{{$value["trade_name"]}}</td>
                            <td class="tdcenter">{{$value["created_at"]}}</td>
                            <td class="tdcenter">{{$value["updated_at"]}}</td>
                            <td class="tdcenter">
                                @if($value["trade_type"]==3 && ($value["trade_state"]==0 || $value["trade_state"]==1))
                                    <button class="layui-btn layui-btn-small chongzhichuli" data="{{$value["id"]}}">充值处理</button>
                                    <button class="layui-btn layui-btn-small delet" data="{{$value["id"]}}">删除</button>
                                @endif
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

        /*清空选择*/
        $("#clear").click(function () {
            $("#user_id").val("");
            $("#test6").val("");
            $("#leixing").val("");
            form.render()
        });




        function chongzhichuli(id) {
            var url = "{{ url('manager/chongzhichuli')}}";
            url     = url+"?id="+id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'充值处理',
                    shadeClose:true,
                    shade:0,
                    area:['50%','80%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        }


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
            location.href = "{{url('manager/user_chongzhi') }}?time="+$data;
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
            var leixing =   $("#leixing").val();
            location.href = "{{url('manager/user_chongzhi') }}?val="+val+"&user_id="+user_id+"&leixing="+leixing;
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
        $(document).on("click",".chongzhichuli",function () {
            var id = $(this).attr("data");
            var url = "{{ url('manager/chongzhichuli')}}";
            url     = url+"?id="+id;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'充值处理',
                    shadeClose:true,
                    shade:0,
                    area:['50%','80%'],
                    content:url,
                    skin:'accountOp',
                })
            })
        })

        
    });





</script>

</body>

</html>