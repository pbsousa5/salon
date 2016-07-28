<?php

namespace App\Libary\Push\Sms;

use App\Libary\Contracts\Push\PusherInterface;
use Cache;
use Illuminate\Support\Str;

/**
 * 
 * 
 * @desc 向用户推送短信
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月22日
 */
class PusherSMS implements PusherInterface
{
    private $rest;
    
    public function __construct(CCPRestSDK $rest)
    {
        $this->rest = $rest;
    }
    
    /**
     * @param string $to 短信接收彿手机号码集合,用英文逗号分开
     * @param array $message 内容数据
     * @param array $opt 中应该包括tempId这个键值，默认为1
     * @see \App\Libary\Contracts\Push\PusherInterface::push()
     */
    public function push($to, $message, array $opt = ['tempId'=>43646])
    {
        // 发送模板短信
        $result = $this->rest->sendTemplateSMS($to, $message, $opt['tempId']);
        if ($result == NULL || $result->statusCode!=0) {
            return false;
        } else {
            $input = explode(',', $to);
            foreach ($input as $val) {
                // 发送成功后，将$code设置到redies中
                Cache::put("{$val}code", $message[0], $message[1]);
            }
            
            return true;
        }
    }
    
    /**
     * 验证短信验证码是否合法
     * @param string $mobile 需要验证的手机号码
     * @param string $code 用户传过来的验证码
     * @return boolean
     */
    public function validateCode($mobile, $code){
        $localCode = Cache::pull("{$mobile}code", '0');
        
        if (Str::equals($localCode, $code)) {
            return true;
        } else {
            return false;
        }
    }
    
}