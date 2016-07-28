<?php
namespace App\Libary\Util;

/**
 * 
 * @desc 常用的验证工具类
 * @author helei <helei@bnersoft.com>
 * @date 2015年4月18日
 *
 */
class Validate {
    const NAME_EN_TYPE = 1;
    const NAME_ENNUM_TYPE = 2;
    const NAME_ENZH_TYPE = 3;
    const NAME_ZH_TYPE = 4;
    const NAME_ALL_TYPE = 5;
    
    /**
     * 验证11位手机号码
     * @param string $mobile
     * @return boolean
     */
    public static function isMobile($mobile) {
        if (!preg_match('/^((13[0-9])|(14[5,7])|(17[0,6-8])|(15[^4,\\D])|(18[0-9]))\\d{8}$/', trim($mobile))) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 验证是否为座机
     * @param unknown $val
     * @return boolean
     */
    public static function isPhone($val) {
        //eg: xxx-xxxxxxxx-xxx | xxxx-xxxxxxx-xxx ...
        if (ereg("^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$", $val)){
            return true;
        }
        
        return false;
    }
    
    /**
     * 验证邮箱
     * @param string $email
     * @return boolean
     */
    public static function isEmail($email) {
        if(!preg_match('/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i', trim($email))){
            return false;
        }
        
        return true;
    }
    
    /**
     * 验证是否是url
     * @param string $url
     * @return boolean
     */
    public static function isUrl($url){
        if(!preg_match('#(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?#i', trim($url))){
            return false;
        }

        return true;
    }
    
    /**
     * 验证身份证号码
     * @param string $str
     * @return boolean
     */
    public static function isIDcard($idcard){
        if(!preg_match("/^([0-9]{15}|[0-9]{17}[0-9a-z])$/i", trim($idcard))){
            return false;
        }
        
        return true;
    }
    
    /**
     * 验证输入的邮编是否正确
     * @param string $val
     * @return boolean
     */
    public static function isPostcode($val) {
        if (ereg("^[0-9]{4,6}$", $val)){
            return true;
        }

        return false;
    }
    
    /**
     * 验证是否是数字
     * @param string $val
     * @return boolean
     */
    public static function isNumber($val) {
        if (ereg("^[0-9]+$", $val)){
            return true;
        }
    
        return false;
    }
    
    /**
     * 验证字符串长度
     * @param int $min 最小长度
     * @param int $max 最大长度
     * @param string $str 验证的字符串
     * @return boolean
     */
    public static function length($str, $min=6, $max=18){
        $str = trim(trim($str));
        if(strlen($str)>=$min && strlen($str)<=$max){
            return true;
        }
        
        return false;
    }
    
    /**
     * 验证输入是否为中文
     * @param string $sInBuf
     * @return boolean
     */
    public static function isChinese($sInBuf) {
        $iLen = strlen($sInBuf);
        for ($i = 0; $i < $iLen; $i++) {
            if (ord($sInBuf{$i}) >= 0x80) {
                if ((ord($sInBuf{$i}) >= 0x81 && ord($sInBuf{$i}) <= 0xFE) && ((ord($sInBuf{$i + 1}) >= 0x40 && ord($sInBuf{$i + 1}) < 0x7E) || (ord($sInBuf{$i + 1}) > 0x7E && ord($sInBuf{$i + 1}) <= 0xFE))) {
                    if (ord($sInBuf{$i}) > 0xA0 && ord($sInBuf{$i}) < 0xAA) {
                        //有中文标点
                        return false;
                    }
                } else {
                    //有日文或其它文字
                    return false;
                }
                $i++;
            } else {
                return false;
            }
        }
        return true;
    }
    
    /**
     * 检查日期是否符合0000-00-00
     * @param string $sDate
     * @return boolean
     */
    public static function isDate($sDate) {
        if (ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2}$", $sDate)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 检查日期是否符合0000-00-00 00:00:00
     * @param string $sTime
     * @return boolean
     */
    public static function isTime($sTime) {
        if (ereg("^[0-9]{4}\-[][0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$", $sTime)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 检查输入值是否为合法人民币格式,小数点后只有两位
     * @param mixed $val
     * @return boolean
     */
    public static function isMoney($val) {
        if (ereg("^[0-9]{1,}$", $val))
            return true;
        if (ereg("^[0-9]{1,}\.[0-9]{1,2}$", $val))
            return true;
        return false;
    }
    
    /**
     * 检查是否为ip
     * @param unknown $val
     * @return boolean
     */
    public static function isIp($val) {
        return (bool) ip2long($val);
    }
    
    /**
     * 校验用户名
     * @param string $account 用户名
     * @param string $type 用户名类型，目前支持：英文，
     * @param number $len 最小长度
     * @return boolean
     */
    public static function userName($account, $type=self::NAME_ALL_TYPE, $len=2) {
        $account = trim($account);
        if($len>strlen($account)){
            return false;
        }
        
        switch($type){
            case self::NAME_EN_TYPE://纯英文
                if(preg_match("/^[a-zA-Z]+$/", $account)){
                    return true;
                }
                
                return false;
                break;
            case self::NAME_ENNUM_TYPE://英文数字
                if(preg_match("/^[a-zA-Z0-9]+$/", $account)){
                    return true;
                }
                
                return false;
                break;
            case self::NAME_ENZH_TYPE:
                if (preg_match("/^[\x80-\xffa-zA-Z0-9]{'.$len.',60}$/", $account)) {
                    return true;
                }
                
                return false;
                break;
            case self::NAME_ZH_TYPE:
                return self::isChinese($account);
                break;
            case self::NAME_ALL_TYPE:    //允许的符号(_字母数字汉字)
                if(preg_match('/^[_\w\d\x{4e00}-\x{9fa5}]{'.$len.',60}$/iu', $account)){
                    return true;
                }
                
                return false;
                break;
            default:
                return false;
                break;
        }
    }
}