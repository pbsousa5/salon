<?php

use Illuminate\Support\Str;

if (!function_exists('check_signature')) {
    /**
     *
     * @desc 检查签名是否正确
     * @param $signature url中的签名
     * @param $timestamp url中的时间戳
     * @param $nonce url中的随机数
     * @return boolean
     */
    function check_signature($signature, $timestamp, $nonce)
    {
        if (empty($signature) || empty($timestamp) || empty($nonce)) {
            return false;
        }

        $app_secret = config('appinit.app_key');
        $tmpArr = array($app_secret, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = sha1(implode($tmpArr));
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('check_url_time')) {
    /**
     *
     * @desc 检查url的请求时间，是否已经过期
     */
    function check_url_time($timestamp)
    {
        $nowTime = time();
        if (0 == $timestamp) {
            return false;
        }
    
        $expire = config('appinit.url_expire');
        $value = $nowTime - $timestamp;

        if ($value < $expire) {// 还在有效期内
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('urlsafe_base64_encode')) {
    /**
     * 
     * 安全的base64编码
     */
    function urlsafe_base64_encode($string)
    {
        $data = str_replace(['+','/','='], ['-','_',''], base64_encode($string));
        return $data;
    }
}

if (!function_exists('urlsafe_base64_decode')) {
    function urlsafe_base64_decode($string)
    {
        $data = str_replace(['-','_'], ['+','/'], $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        
        return base64_decode($data);
    }
}

if (!function_exists('bodys')) {
    /**
     * 
     * 获取http请求body参数
     * @param $type 用什么方式解密 只能为json 或者空
     * @param $key 获取哪一个key的值
     * @return string
     */
    function bodys($type='', $key='')
    {
        $input = @file_get_contents('php://input');
        $input = trim($input);
        if ($type=='json') {
            $input = json_decode($input, true)[$key];
        }
        
        return $input;
    }
}

if (!function_exists('createLinkstring')) {
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    function create_linkstring($para)
    {
        if (!is_array($para)) {
            return $para;
        }
        $arg  = "";
        while (list($key, $val) = each($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg)-2);
    
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
    
        return $arg;
    }
}

if (!function_exists('resolve_linkstring')) {
    /**
     * 
     * 将：mobile=18683338411&password=123456形式字符串拆分为数组
     * array['mobile'=>'18683338412', 'password'=>'123456']
     * @param string $para 解析的字符串
     * @return array|null
     */
    function resolve_linkstring($para)
    {
        if (!Str::contains($para, '=')) {
            return $para;
        }
        $para = explode('&', $para);
        $arg = [];
        foreach ($para as $val) {
            list($key, $value) = explode('=', $val);
            $arg[$key] = $value;
        }
        
        return $arg;
    }
}

if (!function_exists('filter_arr')) {
    /**
     * 除去数组中的空值即前后空字符串
     * @param $para数组
     * return 过滤后的新数组
     */
    function filter_arr($para)
    {
        if (!is_array($para)) {
            $para = json_decode($para, true);
        }
        $para_filter = [];
        while (list($key, $val) = each($para)) {
            if (trim($val)=="" || trim($key)=="") {
                continue;
            } else {
                $para_filter[trim($key)] = trim($para[$key]);
            }
        }
        return $para_filter;
    }
}

if (!function_exists('forbid_cache')) {
    // 禁止页面缓存
    function forbid_cache()
    {
        // 设置此页面的过期时间(用格林威治时间表示)，只要是已经过去的日期即可。
        header ( " Expires: Mon, 26 Jul 1970 05:00:00 GMT " );
        // 设置此页面的最后更新日期(用格林威治时间表示)为当天，可以强制浏览器获取最新资料
        header ( " Last-Modified:" . gmdate ( " D, d M Y H:i:s " ). "GMT " );
        // 告诉客户端浏览器不使用缓存，HTTP 1.1 协议
        header ( " Cache-Control: no-cache, must-revalidate " );
        // 告诉客户端浏览器不使用缓存，兼容HTTP 1.0 协议
        header ( " Pragma: no-cache " );
    }
}

if (!function_exists('age')) {
    /**
     * 根据生日计算年龄
     * 
     * @param $birth 生日 Y-m-d
     */
    function age($birth)
    {
        list($by, $bm, $bd)=explode('-',$birth);
        if ($by=='0000' || $bm=='00' || $bd=='00') {
            return 0;
        }
        
        $cm = date('n');
        $cd = date('j');
        $age = date('Y') - $by - 1;
        if ($cm>$bm || $cm==$bm && $cd>$bd) $age++;
        
        return $age;
    }
}

if (!function_exists('request_source')) {
    /**
     * 判断请求来源
     * 
     */
    function request_source()
    {
        $useragent = strtolower($_SERVER["HTTP_USER_AGENT"]);
        
        // iphone
        $is_iphone = strripos($useragent, 'iphone');
        // ipad
        $is_ipad = strripos($useragent, 'ipad');
        // ipod
        $is_ipod = strripos($useragent, 'ipod');
        if ($is_iphone || $is_ipad || $is_ipod) {
            return 'ios';
        }
        
        // pc电脑
        $is_pc = strripos($useragent, 'windows nt');
        if ($is_pc) {
            return 'debug_web';
        }
        
        // android
        $is_android = strripos($useragent, 'android');
        if ($is_android) {
            return 'android';
        }
        // 微信
        $is_weixin  = strripos($useragent, 'micromessenger');
        if ($is_weixin) {
            return 'weixin';
        }
        
        return 'other';
    }
}

if (! function_exists('array_key_value')) {
    /**
     * 根据某一特定键(下标)取出一维或多维数组的所有值；
     * 不用循环的理由是考虑大数组的效率，把数组序列化，
     * 然后根据序列化结构的特点提取需要的字符串
     * 
     * @param array $array 数组
     * @param string $key 想要获取的key
     * 
     * @return array
     */
    function array_key_value(array $array, $key='')
    {
        if (!trim($key)) return false;
        
        preg_match_all("/\"$key\";\w{1}:(?:\d+:|)(.*?);/", serialize($array), $res);
        return $res[1];
    }
}

if (! function_exists('array_remove_key')) {
    /**
     * 删除一维数组指定key的键值
     *
     * @param array $inputs 数组
     * @param array $keys 想要删除的key的数组
     *
     * @return array
     */
    function array_remove_keys(array $inputs, array $keys)
    {
        if (empty($keys)) return $inputs;
        
        $flag = true;
        foreach ($keys as $key) {
            if (array_key_exists($key, $inputs)) {
                if (is_int($key)) $flag = false;
                unset($inputs[$key]);
            }
        }
        
        if (! $flag) $inputs = array_values($inputs);
        return $inputs;
    }
}

if (! function_exists('array_remove_value')) {
    /**
     * 删除一维数组指定value的键值
     *
     * @param array $inputs 数组
     * @param string $value 想要删除的value
     *
     * @return array
     */
    function array_remove_value(array $inputs, $value)
    {
        if (! in_array($value, $inputs)) {
            return $inputs;
        }
        
        // 如果该值存在，则删除指定索引
        $flag = false;
        foreach ($inputs as $key => $val) {
            if (is_string($key)) $flag = true;
            
            if ($value == $val) {
                unset($inputs[$key]);
            }
        }
        
        if (! $flag) $inputs = array_values($inputs);
        return $inputs;
    }
}