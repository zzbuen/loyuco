<?php


if (!function_exists("current_is")) {
    /**
     * 判断路由
     * @return bool
     */
    function current_is($name)
    {
        $is = false;
        $route = \Illuminate\Support\Facades\Route::current();
        if ($route->getName() == $name) {
            $is = true;
        }
        return $is;
    }
}


if (!function_exists("cdn")) {
    /**
     * 拼接云储存的地址
     * @param $name
     * @param string $prot
     * @return string
     */
    function cdn($name, $prot = '')
    {
        $domain = config('filesystems.disks.qiniu.domains.https');
        $protocol = !empty($prot) ? $prot : config('filesystems.disks.qiniu.protocol');
        $url = sprintf("%s://%s/%s", $protocol, $domain, $name);
        return $url;
    }
}


if (!function_exists('msubstr')) {
    /**
     * 字符串截取
     * @param           $str
     * @param int $start
     * @param           $length
     * @param string $charset
     * @param bool|true $suffix
     * @return string
     * @author longqq
     */
    function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice . '...' : $slice;

    }
}


if (!function_exists("get_description")) {
    /**
     * 根据文章ID截取描述
     * @param $id
     * @param int $word
     * @return string
     * @author longqq
     */
    function get_description($content, $word = 210)
    {
        if (empty($content)) {
            return '...';
        }
        $description = msubstr(strip_tags($content), 0, $word);
        return $description;
    }
}


if (!function_exists("transferMonth")) {
    /**
     * 英文月份解析
     * @param $zhDate
     * @return string
     */
    function transferMonth($zhDate)
    {
        $month = [
            '一月' => 'Jan',
            '二月' => 'Feb',
            '三月' => 'Mar',
            '四月' => 'Apr',
            '五月' => 'May',
            '六月' => 'Jun',
            '七月' => 'Jul',
            '八月' => 'Aug',
            '九月' => 'Sep',
            '十月' => 'Oct',
            '十一月' => 'Nov',
            '十二月' => 'Dec',
        ];
        $zhMonth = explode(' ', $zhDate);
        return sprintf("%s %s %s", $month[$zhMonth[0]], $zhMonth[1], $zhMonth[2]);
    }
}


if (!function_exists("api_response")) {
    /**
     * api接口响应信息
     * @param boolean $status
     * @param json $json_data
     * @param string $message
     * @param string $rspCode
     * @return json
     * @author longqq
     */
    function api_response($status = true, $json_data = '', $message = '', $rspCode = '00000000')
    {
        return json_encode(array('success'=>$status, 'data'=>$json_data, 'message'=>$message, 'rspCode'=>$rspCode));
    }
}

if (!function_exists('make_sign')) {
    /**
     * 游戏服务器端数据校验签名生成
     * @param $content 签名内容
     * @param $access_token api认证授权码
     * @param $appkey 游戏appkey
     * @return string
     * @author longqq
     */
    function make_sign($content, $appkey)
    {
        foreach ($content as $k => $v)
        {
            $Parameters[$k] = $v;
        }

        //签名步骤一：按字典序排序参数
        ksort($Parameters);

        //签名步骤二：签名内容串接
        $buff = '';
        foreach ($Parameters as $k => $v)
        {
            $buff .= $k . "=" . $v;
        }

        //签名步骤三：去除&符号
        $buff = str_replace('&', '', $buff);

        //签名步骤四：md5加密
        $sign = md5($buff.$appkey);

        return $sign;
    }
}

if (!function_exists('send_sms')) {
    /**
     * 发送短信验证码
     * @param $mobiles //群发时，多个手机号使用英文,隔开
     * @param $check_code
     * @return mixed
     */
    function send_sms($mobiles, $content)
    {
        header("Content-Type:text/html;charset=utf-8");
        $post_data = array();
        $post_data['account'] = '';   //帐号
        $post_data['pswd'] = '';  //密码
        $post_data['msg'] =urlencode($content); //短信内容需要用urlencode编码下
        $post_data['mobile'] = $mobiles; //手机号码， 多个用英文状态下的 , 隔开
        $post_data['product'] = ''; //产品ID
        $post_data['needstatus']=false; //是否需要状态报告，需要true，不需要false
        $post_data['extno']='';  //扩展码   可以不用填写
        $url='http://send.18sms.com/msg/HttpBatchSendSM';
        $o='';
        foreach ($post_data as $k=>$v)
        {
            $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('api_curl_post')) {
    /**
     * 认证授权接口的post请求
     * @param $url
     * @param $access_token
     * @param array $data
     * @return mixed
     */
    function api_curl_post($url, $access_token, $data=[]){
        $ch = curl_init();
        /* 设置验证方式 */
        $head_method = [
            'Authorization: Bearer '.$access_token,
            'charset=utf-8',
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head_method);
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json_data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt ($ch, CURLOPT_URL, $url);
        $resp_data = curl_exec($ch);
        curl_close($ch);
        return $resp_data;
    }
}

if (!function_exists('curl_post')) {
    /**
     * 普通的的post请求
     * @param $url
     * @param array $data
     * @return mixed
     */
    function curl_post($data, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}