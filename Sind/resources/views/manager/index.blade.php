<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>诚信28管理系统</title>
    <link rel="stylesheet" href="{{ url('/') }}/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="{{ url('/') }}/plugins/font-awesome/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="{{ url('/') }}/build/css/app.css" media="all">
</head>

<body>
<div class="layui-layout layui-layout-admin kit-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">诚信28管理系统</div>
        <div class="layui-logo kit-logo-mobile"></div>
        <ul class="layui-nav layui-layout-left kit-nav" kit-one-level>
            {{--<li class="layui-nav-item"><a href="javascript:;">控制台</a></li>--}}
        </ul>
        <ul class="layui-nav layui-layout-right kit-nav" lay-filter="kitNavbar" kit-navbar>
            <li class="layui-nav-item">
                <a href="javascript:;">
                    {{ auth('manager')->user()->username }}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{ url('manager/secure') }}" data-icon="&#xe614;" data-title="安全设置" kit-target data-id='1'><span>安全设置</span></a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="{{ url('manager/logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black kit-side">
        <div class="layui-side-scroll">
            <div class="kit-side-fold"><i class="fa fa-navicon" aria-hidden="true"></i></div>
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>
                <?php $comData=Request::get('menu'); ?>
                @foreach($comData['top'] as $v)
                <li class="layui-nav-item">
                    <a class="first_menu" href="javascript:;"><i class="{{ $v['icon'] }}" aria-hidden="true"></i><span> {{$v['label']}}</span></a>
                    <dl class="layui-nav-child">
                        @foreach($comData[$v['id']] as $vv)
                        <dd>
                            <a href="javascript:;" data-url="{{URL::route($vv['name'])}}" data-icon="{{$vv['icon']}}" data-title="{{$vv['label']}}" kit-target data-id='{{$vv['id']}}'><i class="layui-icon">{{$vv['icon']}}</i><span> {{$vv['label']}}</span></a>
                        </dd>
                        @endforeach
                    </dl>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="layui-body" id="container">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">主体内容加载中,请稍等...</div>
    </div>
    <audio  id="audio_mp3"   loop="loop">
        <source src="/audio/message.mp3" type="audio/mpeg">
        <source src="/audio/message.ogg" type="audio/ogg">
        <embed height="100" width="100" src="/audio/message.mp3" />
    </audio>
    <audio  id="audio_mp3_1"   loop="loop">
        <source src="/audio/ding.mp3" type="audio/mpeg">
        <source src="/audio/ding.ogg" type="audio/ogg">
        <embed height="100" width="100" src="/audio/ding.mp3" />
    </audio>
    <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>
        <a href="javascript:;" id="tixian" data-url="{{route('manager.user_withdraw_verify.index')}}" data-icon="" data-title="用户提现" kit-target="" data-id="28"><i class="layui-icon"></i><span> 用户提现</span></a>
        <a href="javascript:;" id="chongzhi" data-url="{{route('manager.offline.index')}}" data-icon="" data-title="用户充值" kit-target="" data-id="104"><i class="layui-icon"></i><span> 用户充值</span></a>
    </ul>
</div>
<script type="text/javascript">
</script>
<script src="{{ url('/') }}/plugins/layui/layui.js"></script>
<script>
    var message;
    layui.config({
        base: '{{ url('/') }}/build/js/'
    }).use(['app', 'message'], function() {
        var app = layui.app,
            $ = layui.jquery,
            layer = layui.layer;
        //将message设置为全局以便子页面调用
        message = layui.message;
        //主入口
        app.set({
            type: 'iframe',
            url: '/managermain',
        }).init();
        $('#pay').on('click', function() {
            layer.open({
                title: false,
                type: 1,
                content: '<img src="/build/images/pay.png" />',
                area: ['500px', '250px'],
                shadeClose: true
            });
        });
    });

    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form,
            $ = layui.jquery,
            layer = layui.layer;

        var audio = document.getElementById('audio_mp3');

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

                        layer.alert('有人充值啦', {icon: 6,offset: 'rb',shade:0},function (index) {
                            $("#chongzhi ")[0].click();
                            localStorage.setItem('chongzhi', (new Date()).getTime())
                            audio.pause();
                            layer.close(index);
                        });
                        audio.play();
                    }else{
                        audio.pause();
                    }
                }
            });
        },10000);
        var audio1 = document.getElementById('audio_mp3_1');

        /*实时提示消息*/
        setInterval(function () {
            $.ajax({
                type: "POST",
                url: "{{url('manager/tixian_tishi')}}" ,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.code) {

                        layer.alert('有人提现啦', {icon: 1,offset: 'rb',shade:0},function (index) {

                            $("#tixian ")[0].click()
                            localStorage.setItem('tixian', (new Date()).getTime())
                            audio1.pause();
                            layer.close(index);
                        });
                        audio1.play();
                    }else{
                        audio1.pause();
                    }
                }
            });
        },10000);
    })

</script>
</body>

</html>