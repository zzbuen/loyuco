<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
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
    .moneyUl{
        margin-left: 35%;
        margin-top: 8%;
    }
</style>

<body>
<div class="layui-fluid main">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>申请提现</legend>
    </fieldset>
    <ul class="moneyUl">
        <form style="margin-top: 20px;" class="layui-form layui-form-pane" action="">
            <li class="layui-form-item">
                <p style="color:red">
                    *提现时间为每周@if(unserialize($withdraw_info['value'])[0]==0)
                                    <span>日</span>
                                  @elseif(unserialize($withdraw_info['value'])[0]==1)
                                    <span>一</span>
                    @elseif(unserialize($withdraw_info['value'])[0]==2)
                        <span>二</span>
                    @elseif(unserialize($withdraw_info['value'])[0]==3)
                        <span>三</span>
                    @elseif(unserialize($withdraw_info['value'])[0]==4)
                        <span>四</span>
                    @elseif(unserialize($withdraw_info['value'])[0]==5)
                        <span>五</span>
                    @else
                        <span>六</span>
                    @endif
                    <span>{{unserialize($withdraw_info['value'])[1]}}至{{unserialize($withdraw_info['value'])[2]}}</span>
                </p>
            </li>
            <li class="layui-form-item">
                <label class="layui-form-label">可提现金额</label>
                <div class="layui-input-inline">
                    <input disabled type="text" name="source_name" value="{{$agent_list['0']['valid_profit']}}" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </li>
            <li class="layui-form-item">
                <label class="layui-form-label">已提现金额</label>
                <div class="layui-input-inline">
                    <input disabled type="text" name="source_name" value="{{$agent_list['0']['expend_profit']}}" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </li>
            <li class="layui-form-item">
                <label class="layui-form-label">总分润金额</label>
                <div class="layui-input-inline">
                    <input disabled type="text" name="source_name" value="{{$agent_list['0']['totle_profit']}}" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </li>
            <li class="layui-form-item" style="margin-left: -100px">
                <label class="layui-form-label">用户银行</label>
            <div class="layui-input-block" style="width: 400px">
                <select class="layui-select-title" id="bank_select">
                    <option value=""></option>
                    @foreach($user_bank as $item)
                        <option value="{{$item['id']}}">
                            {{$item['bankname']['bank_name']}}{{$item['bank_branch']}}支行{{$item['account']}}
                        </option>
                    @endforeach
                </select>
            </div>
        </li>
        </form>
        <li>
            <button class="layui-btn" style="margin-left: 11%" id="begin_apply">申请提现</button>
        </li>
    </ul>
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
        layui.use('element', function(){
            var element = layui.element;
        });
        $("#begin_apply").click(function () {
            var bank_id = $("#bank_select").val();
            if(bank_id==''){
                layer.alert('请先选择银行',{icon:2},function () {
                    location.reload()
                })
            }else{
                layer.prompt({
                    formType: 3,
                    value: '{{$agent_list['0']['valid_profit']}}',
                    title: '请输入提现金额',
                    area: ['100px', '60px'] //自定义文本域宽高
                }, function(value, index, elem){
                    var bank_id = $("#bank_select").val();
                    var data = {money:value,bank_id:bank_id}
                    var url = "{{url('/agent/applyWithdraw_ajax')}}";
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
                                layer.alert(res.msg, {icon: 1},function(index){
                                    location.reload();
                                });
                            }
                            else{
                                layer.alert(res.msg, {icon: 2},function(index){
                                    location.reload();
                                });
                            }
                        }
                    });
                    layer.close(index);
                });
            }
        })
    });
</script>

</body>

</html>