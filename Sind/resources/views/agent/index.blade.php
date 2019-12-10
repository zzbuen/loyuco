<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>诚信28代理管理系统</title>
    <link rel="stylesheet" href="/plugins/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/plugins/font-awesome/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="/build/css/app.css" media="all">
</head>

<body>
<div class="layui-layout layui-layout-admin kit-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">诚信28代理管理系统</div>
        <div class="layui-logo kit-logo-mobile">K</div>
        </ul>
        <ul class="layui-nav layui-layout-right kit-nav" lay-filter="kitNavbar" kit-navbar>
            <li class="layui-nav-item">
                <a href="javascript:;">
                    {{ auth('agent')->user()->username }}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" data-url="{{ url('agent/secure') }}" data-icon="&#xe614;" data-title="安全设置" kit-target data-id='1'><span>安全设置</span></a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="{{ url('agent/logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black kit-side">
        <div class="layui-side-scroll">
            <div class="kit-side-fold"><i class="fa fa-navicon" aria-hidden="true"></i></div>
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>
                <li class="layui-nav-item">
                    <a class="first_menu" href="javascript:;"><i class="fa fa-user-o" aria-hidden="true"></i><span> 用户管理</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" data-url="{{url('agent/getUser') }}" data-icon="&#xe612;" data-title="用户管理" kit-target data-id='1'><i class="layui-icon">&#xe612;</i><span> 用户管理</span></a>
                        </dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a class="first_menu" href="javascript:;"><i class="fa fa-area-chart" aria-hidden="true"></i><span> 报表统计</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" data-url="{{url('agent/teamMsg') }}" data-icon="&#xe65e;" data-title="团队信息" kit-target data-id='2'><i class="layui-icon">&#xe65e;</i><span> 团队信息</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" data-url="{{url('agent/getOrder') }}" data-icon="&#xe659;" data-title="团队投注" kit-target data-id='3'><i class="layui-icon">&#xe659;</i><span> 团队投注</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" data-url="{{url('agent/teamJou') }}" data-icon="&#xe610;" data-title="团队账变" kit-target data-id='4'><i class="layui-icon">&#xe610;</i><span> 团队账变</span></a>
                        </dd>
                        <dd>
                            <a href="javascript:;" data-url="{{url('agent/teamStat') }}" data-icon="&#xe60a;" data-title="团队盈亏" kit-target data-id='5'><i class="layui-icon">&#xe60a;</i><span> 团队盈亏</span></a>
                        </dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-body" id="container">
        <div style="padding: 15px;">主体内容加载中,请稍等...</div>
    </div>
</div>
<script type="text/javascript">
</script>
<script src="/plugins/layui/layui.js"></script>
<script>
    var message;
    layui.config({
        base: '/build/js/'
    }).use(['app', 'message'], function() {
        var app = layui.app,
            $ = layui.jquery,
            layer = layui.layer;
        //将message设置为全局以便子页面调用
        message = layui.message;
        //主入口
        app.set({
            type: 'iframe',
            url: '/main'
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
</script>
</body>

</html>