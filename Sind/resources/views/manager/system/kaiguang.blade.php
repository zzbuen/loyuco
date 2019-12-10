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
            margin-top: 25px;
        }

        .main .layui-row {
            margin: 10px 0;
        }
        tr th,td{
            text-align: center!important;
        }
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
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>AG和BBIN开关</legend>
    </fieldset>

    <div class="layui-form">
        <table class="layui-table">
            <thead>
                <tr>
                    <th>名称</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="test_tbody">
                <tr v-for="item in items">
                    <td>@{{item.key}}</td>
                    <td>
                        <span v-if="item.status==1">已开启</span>
                        <span v-else>已关闭</span>
                    </td>
                    <td>
                        <button v-bind:class="[layui_btn,layui_btn_small,layui_btn_danger]" v-on:click="open_close(item.status,item.key)"  v-if="item.status == 1">关 闭</button>
                        <button v-bind:class="[layui_btn,layui_btn_small]" v-on:click="open_close(item.status,item.key)" v-else>开 启</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" data-info="{{$data}}" id="data">
</div>

<script src="/plugins/layui/layui.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<script src="/js/vue.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;



        /*data获取到的直接是一个数组，可以直接用了*/
        var data = $("#data").data("info");
        var test_tbody = new Vue({
            el:"#test_tbody" ,
            data:{
                layui_btn:"layui-btn",
                layui_btn_small:"layui-btn-small",
                layui_btn_danger:"layui-btn-danger",
                items:data
            },

            methods:{
                /**
                 * 开启或关闭
                 * @param status   当前状态
                 * @param key      key值
                 */
                open_close:function(status,key){

                    var status = status==1?2:1;

                    change_payment_ajax(status,key);

                }


            }

        });



        /**
         * 更改状态
         * @param pay_id        key
         * @param status        value
         */
        function  change_payment_ajax(status,key) {
            layer.load(1);
            var data = {key:key,status:status};
            var url = '{{url("/manager/onoff_ajax")}}'
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    layer.closeAll("loading");
                    if(res.code){
                        layer.alert(res.msg, {icon: 1},function () {
                            location.reload();
                        })
                    } else{
                        layer.alert(res.msg, {icon: 2},function () {
                            location.reload();
                        })
                    }
                },
                error:function (err) {
                    layer.closeAll("loading");
                    layer.alert("系统异常，请稍后再试",{icon:2})
                }
            });
        }

    });

</script>

</body>

</html>