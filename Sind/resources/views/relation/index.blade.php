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
        text-align: center;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>链接管理</legend>
    </fieldset>
    <div style="display: inline-block">
        <div>
            <span>用户名：</span>
            <input value="{{$user_id}}" id="select_userid" placeholder="请输入用户名" name="user_name" type="text" class="layui-input username_input">
        </div>
    </div>
    <input type="button" data="{{url("manager/relation_index")}}" id="select" class="layui-btn" value="查询">
    <div class="layui-form">
        <table class="layui-table" style="text-align: center">
            <colgroup>
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">用户名</th>
                    <th style="text-align: center">链接</th>
                    <th style="text-align: center">注册数量</th>
                    <th style="text-align: center">高频返点</th>
                    <th style="text-align: center">六合彩返点</th>
                    <th style="text-align: center">状态</th>
                    <th style="text-align: center">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key=>$value)
                    <tr>
                        <td style="text-align: center">{{$value["user"]["username"]}}</td>
                        <td style="text-align: center">
                            {{$value["url"]}}
                            <button class="layui-btn layui-btn-mini layui-btn-primary fuzhi" data="{{$value["url"]}}">复制</button>
                        </td>
                        <td style="text-align: center">{{$value["reg_num"]}}</td>
                        <td style="text-align: center">{{$value["fanDian"]}}</td>
                        <td style="text-align: center">{{$value["bFanDian"]}}</td>
                        <td style="text-align: center">
                            @if($value["status"] == 0)
                                正常
                            @else
                                已失效
                            @endif
                        </td>
                        <td style="text-align: center">
                            @if($value["status"] == 0)
                                <button data="{{$value["id"]}}" status="{{$value["status"]}}" class="layui-btn layui-btn-small change_status">使其失效</button>
                            @else
                                <button data="{{$value["id"]}}" status="{{$value["status"]}}" class="layui-btn layui-btn-small change_status">恢复</button>
                            @endif
                            @if($value["deleted_at"] == 0)
                                <button data="{{$value["id"]}}" class="layui-btn layui-btn-small layui-btn-danger delete">删除</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{$data->links()}}
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


        /**
         * 作用：复制链接信息
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
         * 作用：删除链接
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".delete",function () {
            var id = $(this).attr("data");
            layer.confirm("确定此链接？",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/delete_relation",
                    data: {"id":id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.msg(res.msg);
                        if(res.code){
                            location.reload();
                        }
                    }
                });
            })
        });



        /**
         * 作用：改变状态
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click",".change_status",function () {
            var id = $(this).attr("data");
            var status = $(this).attr("status");
            if(status==0){
                status = 1;
            }else{
                status = 0;
            }
            layer.confirm("确定执行此操作？",function () {
                $.ajax({
                    type: "POST",
                    url: "/manager/change_relation_status",
                    data: {"id":id,"status":status},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res){
                        layer.msg(res.msg);
                        if(res.code){
                            location.reload();
                        }
                    }
                });
            })
        });



        /**
         * 作用：根据用户ID查询
         * 作者：信
         * 时间：2018/04/12
         * 修改：暂无
         */
        $(document).on("click","#select",function () {
            var data = $(this).attr("data");
            var user_id = $("#select_userid").val();
            var url = data+"?user_id="+user_id;
            location.href = url;
        })

    });
</script>

</body>

</html>