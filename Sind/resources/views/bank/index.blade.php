<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>银行卡管理页面</title>
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
        line-height: 0px;
        display: inline-block;
        text-align: justify;

    }
    .text_div1{
        height: 30px;
        width: 75px;
        line-height: 0px;
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
    /*.getUser_detail{*/
        /*text-decoration: underline;*/
        /*cursor: pointer;*/
    /*}*/
    .thcenter{
        text-align: center!important;
    }
    .width18{
        width: 16%;
    }
    .width10{
        width: 10%;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>用户银行卡管理</legend>
    </fieldset>
    <div style="display: inline-block;top: 2px;position: relative;margin: 0 30px 10px 30px;">
        <div>
            <span>用户名：</span>
            <input value="{{$user_id}}" id="select_userid" placeholder="请输入用户名" name="user_name" type="text" class="layui-input username_input">
            <span style="margin-left: 20px">用户昵称：</span>
            <input value="{{$name}}" id="select_name" placeholder="请输入用户昵称" name="nickname" type="text" class="layui-input username_input">

        </div>
    </div>
    <input type="button" data="{{url("manager/bank")}}" id="select" class="layui-btn" value="查询">
    <input type="button"  id="clear" class="layui-btn" value="清空选择">
    <div class="layui-form">
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th class="thcenter width10" >用户名</th>
                    <th class="thcenter width10" >用户昵称</th>
                    <th class="thcenter width18">银行名称</th>
                    <th class="thcenter width18">持卡人姓名</th>
                    <th class="thcenter width18">银行卡号</th>
                    <th class="thcenter width18">支行信息</th>
                    <th class="thcenter width18">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $key=>$value)
                    <tr>
                        <td class="thcenter width10">{{$value["username"]}}</td>
                        <td class="thcenter width10">{{$value["name"]}}</td>
                        <td class="thcenter width10">{{$value["bank_name"]}}</td>
                        <td class="thcenter width10">{{$value["bank_account_name"]}}</td>
                        <td class="thcenter width10">{{$value["bank_account"]}}</td>
                        <td class="thcenter width10">{{$value["bank_details"]}}</td>
                        <td class="thcenter width10">
                            <button id="change_bank" class="layui-btn layui-btn-small change_bank" data="{{$value['user_id']}}" >修改</button>
                            <button id="change_bank" class="layui-btn layui-btn-small layui-btn-danger jiebang" data="{{$value['user_id']}}" >解绑</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">暂无数据</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{$data->appends([
        "zhuangtai" => $zhuangtai,
    ])->links()}}
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


        /**
         * 作用：查看银行卡信息
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".look_bank",function () {
            var user_id = $(this).attr("data");
            var bank_id = $(this).attr("bankid");
            var num     = $(this).attr("num");
            var url     = "/manager/look_bank?user_id="+user_id+"&bank_id="+bank_id+"&num="+num;
            layui.use('layer',function(){
                var layer=layui.layer;
                layer.open({
                    type:2,
                    title:'查看银行卡信息',
                    shadeClose:true,
                    shade:0,
                    area:['50%','70%'],
                    content:url,
                    skin:'accountOp'
                })
            })
        });



        /**
         * 作用：复制银行卡号
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
       $(document).on("click",".fuzhi",function () {
           var data = $(this).attr("data");
           var oInput = document.createElement('input');
           oInput.value = data;
           document.body.appendChild(oInput);
           oInput.select();
           document.execCommand("Copy");
           oInput.style.display='none';
           layer.msg("复制成功");
       });



        /**
         * 作用：修改银行卡信息
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".change_bank",function () {
            var id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '修改银行卡信息',
                shadeClose: true,
                shade: 0.8,
                area: ['30%', '60%'],
                maxmin: true,
                content: '{{ url("manager/change_bank") }}?user_id='+id,
            });
        });


        /**
         * 作用：冻结银行卡
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".dongjie",function () {
            var id = $(this).attr("data");
            layer.confirm("确定冻结此用户的该银行卡？",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/donjie_bank_ajax",
                    data: {"id":id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code){
                            layer.msg(res.msg,{"icon":1})
                            location.reload();
                            return false;
                        }
                        layer.msg(res.msg,{"icon":2})
                    }
                });
            })
        });


        /**
         * 作用：解冻银行卡
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".jiedon",function () {
            var id = $(this).attr("data");
            layer.confirm("确定解除冻结此用户的该银行卡？",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/jiedon_bank_ajax",
                    data: {"id":id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code){
                            layer.msg(res.msg,{"icon":1})
                            location.reload();
                            return false;
                        }
                        layer.msg(res.msg,{"icon":2})
                    }
                });
            })
        });




        /**
         * 作用：银行卡解绑
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".jiebang",function () {
            var id = $(this).attr("data");
            layer.confirm("确定解绑此用户的该银行卡？",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/jiebang_bank_ajax",
                    data: {"id":id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        if(res.code){
                            layer.msg(res.msg,{"icon":1})
                            location.reload();
                            return false;
                        }
                        layer.msg(res.msg,{"icon":2})
                    }
                });
            })
        });





        /**
         * 作用：添加银行卡
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".add_bank",function () {
            var user_id = $(this).attr("data");
            layer.open({
                type: 2,
                title: '添加银行卡',
                shadeClose: true,
                shade: 0.8,
                area: ['50%', '80%'],
                maxmin: true,
                content: '{{ url("manager/add_bank") }}?user_id='+user_id,
            });
        });


        /**
         * 作用：根据用户ID查询
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click","#select",function () {
            var data        = $(this).attr("data");
            var user_id     = $("#select_userid").val();
            var user_name   = $("#select_name").val();
            var zhuangtai   = $("#zhuangtai").val();
            var url = data+"?user_id="+user_id+"&name="+user_name+"&zhuangtai="+zhuangtai;
            location.href = url;
        });



        /**
         * 作用：清空选择
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click","#clear",function () {
            $("#select_userid").val("");
            $("#select_name").val("");
            $("#zhuangtai").val("");
        })


    });
</script>

</body>

</html>