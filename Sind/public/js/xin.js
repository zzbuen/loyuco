/*
 *作用:正则验证手机号码合法性
 *作者:信
 *时间:2018/02/07 
 *@param  {int} 	str  需要验证的手机号
 *@return {Boolean}  	 Boolean 对象 
 **/
function verifyPhone(str) {
	var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
	if(!myreg.test(str)) {
		return false;
	} else {
		return true;
	}
}


/*
 *作用:正则验证座机/固定电话合法性
 *作者:信
 *时间:2018/02/07 
 *@param  {str} 	str  需要验证的手机号
 *@return {Boolean}  	 Boolean 对象 
 **/
function isTel(str){
	var reg = /0\d{2,3}-\d{7,8}/;
	var res = reg.test(str);
	return res;
}


/*
 *作用:去除左右空格
 *作者:信
 *时间:2018/02/07 
 *@param  {str} str  需要验证的字符串
 *@return {str}  	  替换后的字符串
 **/
function isEmpty(str){
	var str = str.replace(/(^\s*)|(\s*$)/g, "");
	return str;
}



/*
 *作用:验证6-18位密码
 *作者:信
 *时间:2018/02/09 
 *@param  {str} str  需要验证的字符串
 *@return {pwd}  	 Boolen 对象
 **/
function regPassword(pwd){
	var reg = /^[a-zA-Z0-9]{6,18}$/
	var pwd = reg.test(pwd);
	return pwd;
}


/*
 *作用:仿PHP的htmlspecialchars_decode(一般UEditor适用)
 *作者:信
 *时间:2018/02/07 
 *@param  {str} 	str  需要验证的手机号
 *@return {str}  		  解析后的字符串 
 **/
function htmlspecialchars_decode(str){           
    str = str.replace(/&amp;/g, '&'); 
    str = str.replace(/&lt;/g, '<');
    str = str.replace(/&gt;/g, '>');
    str = str.replace(/&quot;/g, "''");  
    str = str.replace(/&#039;/g, "'");  
    return str;  
}


/*
 *作用:自制JS的date(仿PHP的date)
 *作者:信
 *时间:2018/02/07 
 *@param  {string} format    格式
 *@param  {int}    timestamp 要格式化的时间 默认为当前时间
 *@return {string}           格式化的时间字符串
 **/
function date (format,timestamp ) {
    var a, jsdate=((timestamp) ? new Date(timestamp*1000) : new Date());
    var pad = function(n, c){
        if((n = n + "").length<c){
            return new Array(++c - n.length).join("0") + n;
        }else{
            return n;
        }
    };
    var txt_weekdays 	= ["Sunday","Monday","Tuesday","Wednesday", "Thursday","Friday","Saturday"];        
    var txt_ordin 		= {1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"};
    var txt_months 		= ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var f = {
        /*Day天*/
        d: function(){
            return pad(f.j(),2);
        },
        D: function(){
            t = f.l(); return t.substr(0,3);
        },
        j: function(){
            return jsdate.getDate();
        },
        l: function(){
            return txt_weekdays[f.w()];
        },
        N: function(){
            return f.w() + 1;
        },
        S: function(){
            return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th';
        },
        w: function(){
            return jsdate.getDay();
        },
        z: function(){
            return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0;
        },
        /*week周*/
        W: function(){
            var a = f.z(), b = 364 + f.L() - a;
            var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1;
            if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){
                return 1;
            } else{
                if(a <= 2 && nd >= 4 && a >= (6 - nd)){
                    nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31");
                    return date("W", Math.round(nd2.getTime()/1000));
                } else{
                    return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0);
                }
            }
        },

        /*Month月*/
        F: function(){
            return txt_months[f.n()];
        },
        m: function(){
            return pad(f.n(), 2);
        },
        M: function(){
            t = f.F(); return t.substr(0,3);
        },
        n: function(){
            return jsdate.getMonth() + 1;
        },
        t: function(){
            var n;
            if( (n = jsdate.getMonth() + 1) == 2 ){
                return 28 + f.L();
            } else{
                if( n & 1 && n < 8 || !(n & 1) && n > 7 ){
                    return 31;
                } else{
                    return 30;
                }
            }
        },

        /*Year年*/
        L: function(){
            var y = f.Y();
            return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0;
        },
       /*o还不支持*/
        Y: function(){
            return jsdate.getFullYear();
        },
        y: function(){
            return (jsdate.getFullYear() + "").slice(2);
        },

        /*Time时间*/
        a: function(){
            return jsdate.getHours() > 11 ? "pm" : "am";
        },
        A: function(){
            return f.a().toUpperCase();
        },
        B: function(){
            /*peter paul koch:*/
            var off = (jsdate.getTimezoneOffset() + 60)*60;
            var theSeconds = (jsdate.getHours() * 3600) +
                             (jsdate.getMinutes() * 60) +
                              jsdate.getSeconds() + off;
            var beat = Math.floor(theSeconds/86.4);
            if (beat > 1000) beat -= 1000;
            if (beat < 0) beat += 1000;
            if ((String(beat)).length == 1) beat = "00"+beat;
            if ((String(beat)).length == 2) beat = "0"+beat;
            return beat;
        },
        g: function(){
            return jsdate.getHours() % 12 || 12;
        },
        G: function(){
            return jsdate.getHours();
        },
        h: function(){
            return pad(f.g(), 2);
        },
        H: function(){
            return pad(jsdate.getHours(), 2);
        },
        i: function(){
            return pad(jsdate.getMinutes(), 2);
        },
        s: function(){
            return pad(jsdate.getSeconds(), 2);
        },
        /*u还不支持
    	 *时区
         *e还不支持
         *I还不支持
         **/
        O: function(){
           var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4);
           if (jsdate.getTimezoneOffset() > 0) t = "-" + t; else t = "+" + t;
           return t;
        },
        P: function(){
            var O = f.O();
            return (O.substr(0, 3) + ":" + O.substr(3, 2));
        },
        /*T 还不支持
        *Z 还不支持
        *完整的时间 Date/Time
        * */
        c: function(){
            return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P();
        },
        /*r还不支持*/
        U: function(){
            return Math.round(jsdate.getTime()/1000);
        }
    };

    return format.replace(/[\\]?([a-zA-Z])/g, function(t, s){
        if( t!=s ){
            /*escaped*/
            ret = s;
        } else if( f[s] ){
            /*a date function exists*/
            ret = f[s]();
        } else{
            /*nothing special*/
            ret = s;
        }
        return ret;
    });
}
/*function:自制JS的date(仿PHP的date) end*/