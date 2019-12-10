<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>投注比例</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
    <link rel="stylesheet" type="text/css" href="/plugins/font-awesome/css/font-awesome.css">
</head>
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
        font-weight: 100;
        color: #01AAED;
    }

    .main {
        margin-top: 25px;
    }

    .main .layui-row {
        margin: 10px 0;
    }
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
    tr td,th{
        text-align: center!important;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>投注比例</legend>
    </fieldset>
    <div class="layui-row layui-col-space15">
        <input type="hidden" id="now_game" value="">
            <div class="body_right">
            <div>
                <ul id="category_ul">
                    <li>
                        <label class="layui-form-label" style="color: #0C0C0C">快捷选时：</label>
                        <button data="1" class="kj_time layui-btn <?php if($time!=1 ) echo "layui-btn-primary";?>">今天</button>
                        <button data="2" class="kj_time layui-btn <?php if($time!=2) echo "layui-btn-primary";?>">昨天</button>
                        <button data="3" class="kj_time layui-btn <?php if($time!=3 ) echo "layui-btn-primary";?>">近三天</button>
                        <button data="7" class="kj_time layui-btn <?php if($time!=7 ) echo "layui-btn-primary";?>">近七天</button>
                        <button data="15" class="kj_time layui-btn <?php if($time!=15) echo "layui-btn-primary";?>">近半月</button>
                        <button data="30" class="kj_time layui-btn <?php if($time!=30 ) echo "layui-btn-primary";?>">近一月</button>
                    </li>
                </ul>
            </div>
            <div style="margin-top: 10px" >
                <ul id="category_ul">
                    <li style="height: 40px">
                        <div class="layui-form">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label" style="color: #0C0C0C">用户名：</label>
                                    <div class="layui-input-inline">
                                        <input type="text" value="{{$user_id}}" class="layui-input" id="user_id" placeholder="请输入用户名">
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

                <div class="layui-form" >
                    <table class="layui-table">
                        <thead>
                            <tr>
                                <th class=" sorting" sort="daymoney">账号</th>
                                <th class=" sorting" sort="server_charge">昵称</th>
                                <th class=" sorting" sort="server_charge">投注总额</th>
                                <th class=" sorting" sort="share_profit">把数</th>
                                <th class=" sorting" sort="share_profit">盈亏</th>
                                <th class=" sorting" sort="share_profit">大小单双占比</th>
                                <th class=" sorting" sort="share_profit">组合占比</th>
                                <th class=" sorting" sort="pure_profit">小双大单占比</th>
                                <th class=" sorting" sort="pure_profit">数字占比</th>
                                <th class=" sorting" sort="pure_profit">极值占比</th>
                                <th class=" sorting" sort="pure_profit">色波占比</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_data as $key=>$value)
                                <tr>
                                    <td class=" sorting" sort="daymoney">{{$value["username"]}}</td>
                                    <td class=" sorting" sort="server_charge">{{$value["name"]}}</td>
                                    <td class=" sorting" sort="server_charge">{{$value["betting"]}}</td>
                                    <td class=" sorting" sort="share_profit">{{$value["betNum"]}}</td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["total"]<0)
                                            <span style="color: red">{{$value["total"]}}</span>
                                        @else
                                            {{$value["total"]}}
                                        @endif</td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"]==0)
                                            0
                                        @else
                                        {{round($value["dxds"]/($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"])*100,2)}}%
                                        @endif
                                    </td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"]==0)
                                            0
                                        @else
                                            {{round($value["zh"]/($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"])*100,2)}}%
                                        @endif
                                    </td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"]==0)
                                            0
                                        @else
                                            {{round($value["xsdd"]/($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"])*100,2)}}%
                                        @endif
                                    </td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"]==0)
                                            0
                                        @else
                                            {{round($value["sz"]/($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"])*100,2)}}%
                                        @endif
                                    </td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"]==0)
                                            0
                                        @else
                                            {{round($value["jz"]/($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"])*100,2)}}%
                                        @endif
                                    </td>
                                    <td class=" sorting" sort="share_profit">
                                        @if($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"]==0)
                                            0
                                        @else
                                            {{round($value["sb"]/($value["dxds"]+$value["zh"]+$value["xsdd"]+$value["sz"]+$value["jz"]+$value["sb"])*100,2)}}%
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($all_data)==0)
                                <tr>
                                    <td colspan="11">暂无数据</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{$all_data->appends([
                    "time" =>$time,
                    "val"   => $val,
                    "user_id"=>$user_id,
                ])->links()}}
        </div>
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

        $("#clear").click(function () {
            $("#user_id").val("");
            $("#test6").val("");
        })


        /**
         * 作用：快捷选时
         * 作者：信
         * 时间：2018/3/29
         * 修改：暂无
         */
        $(document).on("click",".kj_time",function () {
            var $data = $(this).attr("data");
            location.href = "{{url('manager/order_ratio.index') }}?time="+$data;
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
            var user_id = $("#user_id").val().replace(/(^\s*)|(\s*$)/g, "");
            location.href = "{{url('manager/order_ratio.index') }}?val="+val+"&user_id="+user_id;
        });
        


        
    });





</script>

</body>

</html>