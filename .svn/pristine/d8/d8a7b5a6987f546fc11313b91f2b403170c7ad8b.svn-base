<?php

namespace App\Libary\Util;

class String
{
    /**
     * 生成UUID 不重复
     * @access public
     * @return string
     */
    public static function uuid() {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
        
        return str_replace('-', '', substr($uuid, 1, -1));
    }
    
    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @return string
     */
    public static function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
    {
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        
        return $suffix ? $slice.'...' : $slice;
    }
    
    /**
     * 产生随机字串，可用来自动生成密码
     * 默认长度6位 字母和数字混合 支持中文
     * @param string $len 长度
     * @param string $type 字串类型
     * 0字母 1数字 2字母数字 其它 混合
     * @param string $addChars 额外字符
     * @return string
     */
    public static function randString($len=6, $mode='', $addChars='=@#$&%*+-?|')
    {
        $str = '';
        switch ($mode) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 1:
                $chars = str_repeat('0123456789',3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz23456789';
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
                break;
        }
        if ($len > 10) {//位数过长重复字符串一定次数
            $chars = $mode==1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($mode != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            // 特殊符号
            for ($i=0; $i<$len; $i++) {
                $str .= self::msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8')-1)), 1, 'utf-8', false);
            }
        }
        return $str;
    }
    
    /**
     * 生成1个随机数
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
     * @return string
     */
    public static function buildCountRand ($length=6, $mode=1)
    {
        return self::randString($length, $mode);
    }
    
    /**
     * 当前时间戳组合生成
     * @param int $timestamp 生成订单号的时间戳
     * @param string $prefix 传入的前缀
     * @return string
     */
    public static function buildTimeString($timestamp, $prefix = ''){
        if(is_string($prefix) && ''!=$prefix){
            $mixCode = $prefix;
        }
    
        $mixCode .= date('YmdHis').str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);

        return $mixCode;
    }
}