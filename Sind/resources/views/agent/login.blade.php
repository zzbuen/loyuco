<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>诚信28代理系统</title>
    <link rel="stylesheet" type="text/css" href="/login/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/login/css/demo.css" />
    <!--必要样式-->
    <link rel="stylesheet" type="text/css" href="/login/css/component.css" />
    <!--[if IE]>
    <script src="/login/js/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container demo-1">
    <div class="content">
        <div id="large-header" class="large-header">
            <canvas id="demo-canvas"></canvas>
            <div class="logo_box">
                <h3>诚信28代理系统</h3>
                <form method="post">
                    {{ csrf_field() }}
                    <div class="input_outer">
                        <span class="u_user"></span>
                        <input name="username" class="text" style="transition: background-color 50000s ease-in-out 0s;color: #FFFFFF !important" type="text" placeholder="请输入账户">
                    </div>
                    <div class="input_outer">
                        <span class="us_uer"></span>
                        <input name="password" class="text" style="transition: background-color 50000s ease-in-out 0s;color: #FFFFFF !important; position:absolute; z-index:100;" value="" type="password" placeholder="请输入密码">
                    </div>
                    <div class="input_outer">
                        <span class="usc_uer" onclick="javascript:re_captcha();"></span>
                        <input name="captcha" class="text" style="color: #FFFFFF !important; position:absolute; z-index:100; width:160px;" value="" type="text" placeholder="请输入验证码">
                        <img id="checkCode" style="width: 120px;height: 40px; position:absolute; z-index:100; right: 16px; top:1px;" src="{{ captcha_src() }}" onclick="javascript:re_captcha();" />
                    </div>
                    <div class="mb2"><a class="act-but submit" href="javascript:;" onclick="$(this).parent().parent().submit()" style="color: #FFFFFF">登录</a></div>
                </form>
            </div>
        </div>
    </div>
</div><!-- /container -->
<script src="/login/js/TweenLite.min.js"></script>
<script src="/login/js/EasePack.min.js"></script>
<script src="/login/js/rAF.js"></script>
<script src="/login/js/demo-1.js"></script>
<script src="/js/app.js"></script>
<script src="/plugins/layui/layui.all.js"></script>
<script>
    function re_captcha() {
        $.ajax({
            type: "GET",
            url: "/agent/captcha/1",
            data: "",
            dataType: "json",
            success: function(data){
                document.getElementById('checkCode').src = data.src;
            }
        });
    }
    @if ($errors->has('error'))
    layer.msg("{{ $errors->first('error') }}");
    @endif
</script>
</body>
</html>