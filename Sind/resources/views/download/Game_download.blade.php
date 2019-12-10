<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8"/>
		<title>365应用下载</title>
		<meta http-equiv="Access-Control-Allow-Origin" content="*"/>
		<meta http-equiv="content-security-policy">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<script type="text/javascript" src="./static/js/jquery-3.2.1.js"></script>
  		<script type="text/javascript" src="./static/js/vue.js"></script>
		<link rel="stylesheet"  href="./static/css/mui.min.css"/>
		<style>
			.J_btn_bg {
				background-color: #068baa !important;
				background: -webkit-gradient(linear, 100% 0, 0 0, from(#004d67), to(#05b0b9)) !important;
				background: -webkit-linear-gradient(to right, #004d67, #05b0b9) !important;
				background: -moz-linear-gradient(to right, #004d67, #05b0b9) !important;
				background: -o-linear-gradient(to right, #004d67, #05b0b9) !important;
				background: linear-gradient(to right, #004d67, #05b0b9) !important;
			}
			.number_span{
				border-radius: 30px;
				display: inline-block;
				height: 30px;
				width: 30px;
				background-color: orange;
				line-height: 30px;
				text-align: center;
				color: white;
				font-size: 17px;
			}
			.mui_p{
				line-height: 30px;
				font-size:15px ;
			}
			.download_btn{
				background-color: orange;
				color: white;
				border: none;
				height: 40px;
				line-height: 30px;
				border-radius: 5px;
			}
			.android_png{
				height: 200px;
				width: 200px;
			}
			.head_p{
				padding-top: 5%;
				padding-bottom: 5%;
				text-align: center;
				color: white;
				font-size: 15px;
			}
			.I_wc_box{
				width: 100%;
				height: 100%;
				position: fixed;
				z-index: 9999;
				top: 0px;
				left: 0px;
				background: rgba(0,0,0,0.9);
			}
			.I_share_head img{
				width: 100%;
			}
			.I_share_footer img{
				width: 100%;
				position: absolute;
				bottom: 0px;
			}
		</style>
	</head>
<body>
<div class="I_wc_box" id="apple_cover" style="display: none">
	<div class="I_share_head">
		<img src="./static/img/share_head.png"  />
	</div>
	<div class="I_share_footer">
		<img src="./static/img/share_footer_ios.png"  />
	</div>
</div>
<div class="I_wc_box" id="android_cover" style="display: none">
	<div class="I_share_head">
		<img src="./static/img/share_head.png"  />
	</div>
	<div class="I_share_footer">
		<img src="./static/img/share_footer.png"  />
	</div>
</div>
<div id="app">
	<div v-if="android_display"  class="mui-content" style="height:100%">
		<div class="mui-row">
			<p class="head_p J_btn_bg">
				365娱乐应用下载
			</p>
		</div>
		<div class="mui-row">
			<p style="color: red;text-align: center;padding-top: 10%">您的邀请码为:{{$code}}</p>
			<div class="mui-col-sm-3 mui-col-xs-3"></div>
			<div class="mui-col-sm-6 mui-col-xs-6">
				<div class="mui-row" style="padding-top: 13%;padding-bottom: 10%">
					<div class="mui-col-sm-2 mui-col-xs-2"></div>
					<div class="mui-col-sm-8 mui-col-xs-8">
						<img class="mui-col-sm-12 mui-col-xs-12" src="./static/img/download_png.png" alt=""/>
					</div>
					<div class="mui-col-sm-2 mui-col-xs-2"></div>
				</div>
				<div class="mui-row">
					<div class="mui-col-sm-12 mui-col-xs-12">
						<button class="mui-col-sm-12 mui-col-xs-12 download_btn"  v-on:click="download">安卓手机客户端下载</button>
					</div>
				</div>
			</div>
			<div class="mui-col-sm-3 mui-col-xs-3"></div>
		</div>
		<div class="mui-row" style="margin-top: 20px">
			<div class="mui-col-sm-2 mui-col-xs-2"></div>
			<div class="mui-col-sm-9 mui-col-xs-9">
				<p class="mui_p">
					<span class="number_span">1</span>
					<span>在打开的页面内点击直接下载安装</span>
				</p>
				<p class="mui_p">
					<span class="number_span">2</span>
					<span>系统将自动下载应用程序</span>
				</p>
				<p class="mui_p">
					<span class="number_span">3</span>
					<span>下载完成后点击安装,操作如下：<br/>
						<span style="margin-left: 35px">设定>未知来源(允许)>安装</span>
					</span>
				</p>
				<p class="mui_p">
					<span class="number_span">4</span>
					<span>立即开启应用程序</span>
				</p>
				<p style="color: red">注意:使用微信扫描器需要点击右上角菜单选择浏览器下载，谢谢！</p>
			</div>
			<div class="mui-col-sm-1 mui-col-xs-1"></div>
		</div>
	</div>
	<div v-if="apple_display" class="mui-content" style="height:100%">
		<div class="mui-row">
			<p class="head_p J_btn_bg">
				365娱乐应用下载
			</p>
		</div>
		<div class="mui-row">
			<p style="color: red;text-align: center;padding-top: 10%">您的邀请码为:{{$code}}</p>
			<div class="mui-col-sm-3 mui-col-xs-3"></div>
			<div class="mui-col-sm-6 mui-col-xs-6">
				<div class="mui-row" style="padding-top: 13%;padding-bottom: 10%">
					<div class="mui-col-sm-2 mui-col-xs-2"></div>
					<div class="mui-col-sm-8 mui-col-xs-8">
						<img class="mui-col-sm-12 mui-col-xs-12" src="./static/img/download_png.png" alt=""/>
					</div>
					<div class="mui-col-sm-2 mui-col-xs-2"></div>
				</div>
				<div class="mui-row">
					<div class="mui-col-sm-12 mui-col-xs-12">
						<button class="mui-col-sm-12 mui-col-xs-12 download_btn" v-on:click="apple_download">苹果手机客户端下载</button>
					</div>
				</div>
			</div>
			<div class="mui-col-sm-3 mui-col-xs-3"></div>
		</div>
		<div class="mui-row" style="margin-top: 20px">
			<div class="mui-col-sm-2 mui-col-xs-2"></div>
			<div class="mui-col-sm-8 mui-col-xs-8">
				<p class="mui_p">
					<span class="number_span">1</span>
					<span>在打开页面内点击直接下载安装</span>
				</p>
				<p class="mui_p">
					<span class="number_span">2</span>
					<span>按Home键查看安装过程</span>
				</p>
				<p class="mui_p">
					<span class="number_span">3</span>
					<span>完成安装并信任套件,操作如下：<br/>
						<span style="margin-left: 35px;color: orange;font-size: 16px;">设定>通用>设备管理>信任</span>
					</span>
				</p>
				<p class="mui_p">
					<span class="number_span">4</span>
					<span>立即开启应用程序</span>
				</p>
				<p style="color: red">注意:使用微信扫描器需要点击右上角菜单选择浏览器下载，谢谢！</p>
			</div>
			<div class="mui-col-sm-2 mui-col-xs-2"></div>
		</div>
	</div>
	<div v-if="pc_display" class="mui-content" style="height:100%">
		<div class="mui-row">
			<p class="head_p J_btn_bg">
				365娱乐应用下载
			</p>
		</div>
		<div class="mui-row">
			<div class="mui-col-sm-3 mui-col-xs-3"></div>
			<div class="mui-col-sm-6 mui-col-xs-6">
				<div class="mui-row" style="padding-top: 7%;padding-bottom: 10%">
					<div class="mui-col-sm-4 mui-col-xs-4"></div>
					<div class="mui-col-sm-4 mui-col-xs-4">
						<img class="mui-col-sm-12 mui-col-xs-12" src="./static/img/download_png.png" alt=""/>
					</div>
					<div class="mui-col-sm-4 mui-col-xs-4"></div>
				</div>
			</div>
			<div class="mui-col-sm-3 mui-col-xs-3"></div>
		</div>
		<div class="mui-row" style="margin-top: 10px;">
			<div class="mui-col-sm-2 mui-col-xs-2"></div>
			<div class="mui-col-sm-8 mui-col-xs-8">
				<p style="text-align: center;font-size: 25px;">现在只支持Android或Ios手机浏览器安装，谢谢！</p>
			</div>
			<div class="mui-col-sm-2 mui-col-xs-2"></div>
		</div>
	</div>
</div>
</body>
		<script src="./static/js/mui.min.js"></script>
		<script type="text/javascript" charset="utf-8">
		var ua = window.navigator.userAgent.toLowerCase();
		if (ua.match(/MicroMessenger/i) != 'micromessenger') {
			//当前非微信，移除
			$(".I_wc_box").remove();
		}
		var Applecover = new Vue({
            el:"#apple_cover",
            data:{
                Cover:false
            },
            methods:{
                display:function () {
                    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
                        var ua = window.navigator.userAgent.toLowerCase();
                        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                            $("#apple_cover").css({display:"block"});
                        }
                    }
                }
            }
        })
        Applecover.display();
        var Androidcover = new Vue({
            el:"#android_cover",
            data:{
                Cover:false
            },
            methods:{
                display:function () {
                    if (/(Android)/i.test(navigator.userAgent)) {
                        var ua = window.navigator.userAgent.toLowerCase();
                        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                            $("#android_cover").css({display:"block"});
                        }
                    }
                }
            }
        })
        Androidcover.display();
        var Download = new Vue({
            el: "#app",
			data:{
                android_display:false,
                apple_display:false,
				pc_display:false,
			},
            methods: {
                Init: function () {
                    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) { //判断iPhone|iPad|iPod|iOS
                        Download.android_display = false;
                        Download.apple_display = true;
                        Download.pc_display = false;
                    } else if (/(Android)/i.test(navigator.userAgent)) {  //判断Android
                        Download.android_display = true;
                        Download.apple_display = false;
                        Download.pc_display = false;
                    } else {
                        Download.android_display = false;
                        Download.apple_display = false;
                        Download.pc_display = true;
                    };
                },
                download:function (event) {

                    location.href = "http://pay01.36513147777.com/caimi/file/caimi11.apk";
                },
                apple_download:function (event) {
                    location.href = "itms-services://?action=download-manifest&url=https://admin.36513147777.com/qunlongjue.1.5.0.plist";
                   // location.href = "http://p.gdown.baidu.com/1d743d7ae60b8f2838fcb0b2d89471e4e9d44d2aaa01a2e05f1e41c192a58c59141cf0cf4368df610eb851e37656d88512dbebe869794ed04842a31da0a77d941e6c2cec8a2fc9af5df9cd2e3f8d358b279a51e1c94fb5c1be5796b0b4cb6131d405f58f9500942e680f739c8e67b29c06590a900bd64eec595787d049b778b7";
                }
            }
        })
        Download.Init();

		</script>
</html>