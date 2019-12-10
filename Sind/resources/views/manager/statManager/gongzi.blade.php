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
            margin-top: 25px;
        }

        .main .layui-row {
            margin: 10px 0;
        }
        tr td,th{
            text-align: center!important;
        }
    </style>
</head>
<style>
    .text_div{
        height: 30px;
        line-height: 0px;
        display: inline-block;

    }
    .text_div1{
        height: 30px;
        line-height: 0px;
        display: inline-block;


    }
    .text_span{
        display: inline-block ;
        padding-left: 100%;
    }
    .username_input{
        width: 150px;
        display: inline-block;
    }
    .day_money_detail{
        cursor: pointer;
        text-decoration: underline;
    }
    .server_charge_detail{
        cursor: pointer;
        text-decoration: underline;
    }
    .share_profit_detail{
        cursor: pointer;
        text-decoration: underline;
    }
    .user_id{
        cursor: pointer;
    }


</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>日工资数据统计</legend>
    </fieldset>
    <div style="margin-bottom: 1rem">
        <ul id="category_ul">
            <li>
                <label class="layui-form-label"  style="color: #0C0C0C">快捷选时：</label>
                <button data="1" class="kj_time layui-btn <?php if($time!=1) echo "layui-btn-primary";?>">今天</button>
                <button data="2" class="kj_time layui-btn  <?php if($time!=2) echo "layui-btn-primary";?>">昨天</button>
                <button data="3" class="kj_time layui-btn  <?php if($time!=3) echo "layui-btn-primary";?>">近三天</button>
                <button data="7" class="kj_time layui-btn  <?php if($time!=7) echo "layui-btn-primary";?>">近七天</button>
                <button data="15" class="kj_time layui-btn  <?php if($time!=15) echo "layui-btn-primary";?>">近半月</button>
                <button data="30" class="kj_time layui-btn <?php if($time!=30) echo "layui-btn-primary";?>">近一月</button>
            </li>
        </ul>
    </div>
    <form method="post" action="{{url('manager/sum_gongzi.index')}}">
        {{ csrf_field() }}

        <div style="margin-top: 10px">
            <div class="layui-form">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label"  style="color: #0C0C0C">用户名：</label>
                        <div class="layui-input-inline">
                            <input value="{{$user_id}}" placeholder="请输入用户名"  id="user_id" name="user_id" type="text" class="layui-input username_input">
                        </div>
                        <label class="layui-form-label" style="color: #0C0C0C">起始时间：</label>
                        <div class="layui-input-inline">
                            <input value="{{$date_begin}}"  type="text" placeholder="请输入起始时间" class="layui-input" id="date_begin" name="date_begin" >
                        </div>
                        <label class="layui-form-label" style="color: #0C0C0C">结束时间：</label>
                        <div class="layui-input-inline">
                            <input value="{{$date_end}}" type="text" placeholder="请输入结束时间" class="layui-input" id="date_end" name="date_end">
                        </div>
                        <label class="layui-form-label" style="color: #0C0C0C">状态：</label>
                        <div class="layui-input-inline">
                            <select class="layui-select" name="status">
                                <option value="1" <?php if($status==1)echo 'selected="selected"'?>>已发放</option>
                                <option value="2" <?php if($status==2)echo 'selected="selected"'?>>未发放</option>
                                <option value="3" <?php if($status==3)echo 'selected="selected"'?>>取消发放</option>
                                <option value="4" <?php if($status==4)echo 'selected="selected"'?>>金额不足</option>
                                <option value="5" <?php if($status==5)echo 'selected="selected"'?>>总亏损未达到要求</option>
                                <option value="6" <?php if($status==6)echo 'selected="selected"'?>>活跃人数未达到要求</option>
                                <option value="7" <?php if($status==7)echo 'selected="selected"'?>>平台外发放确认中</option>
                                <option value="8" <?php if($status==8)echo 'selected="selected"'?>>平台外发放成功</option>
                            </select>
                        </div>
                        <input type="submit" class="layui-btn" value="查询">
                        <button id="clear" type="button" class="layui-btn">清空选择</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-col-md3" style="margin-top: -0.5%">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:#dd4b39 !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">已发放工资总金额</span>
                    <span class="info-box-number" style="color: dodgerblue">{{$sum_fafang}}</span>
                </div>
            </div>
        </div>
        <div class="layui-col-md3" style="margin-top: -0.5%">
            <div class="info-box">
                <span class="info-box-icon" style="background-color:green !important;color:white;"><i class="fa fa-rmb" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">搜索条件工资总金额</span>
                    <span class="info-box-number" style="color: dodgerblue">{{$sousuo_gongzi}}</span>
                </div>
            </div>
        </div>
    </form>

    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>用户名</th>
                    <th>日工资比例</th>
                    <th>工资金额</th>
                    <th>状态</th>
                    <th>时间</th>
                </tr>
            </thead>
        <tbody>
            @forelse($data as $key=>$value)
                <tr>
                    <td>{{$value["user"]["username"]}}</td>
                    <td>{{$value["wage"]["wage_ratio"]}}</td>
                    <td>{{$value["wage_amount"]}}</td>
                    <td>

                        @if($value["status"] == 1)
                            已发放
                        @endif
                        @if($value["status"] == 2)
                            未发放
                        @endif
                        @if($value["status"] == 3)
                            取消发放
                        @endif
                        @if($value["status"] == 4)
                            金额不足
                        @elseif($value["status"] == 5)
                            总亏损未达到要求
                        @elseif($value["status"] == 6)
                            活跃人数未达到要求
                        @elseif($value["status"] == 7)
                            平台外发放确认中
                        @elseif($value["status"] == 8)
                            平台外发放成功
                        @endif
                    </td>
                    <td>{{$value["create_time"]}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">暂无数据</td>
                </tr>
            @endforelse
        </tbody>
    </table>
        {{$data->appends([
            "user_id"       => $user_id,
            "date_begin"    => $date_begin,
            "date_end"      => $date_end,
            "status"        => $status,
        ])->links()}}
    </div>
</div>
<script src="/plugins/layui/layui.js"></script>
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
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ route("manager.announcement.edit") }}?id='+config_id,
            });
        });


        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/sum_gongzi.index') }}?time="+$data;
        });



        $("#clear").click(function () {
            $("#user_id").val("");
            $("#date_begin").val("");
            $("#date_end").val("");
        })


        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_begin' //指定元素
            });
        });
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date_end' //指定元素
            });
        });

    });
</script>

</body>

</html>