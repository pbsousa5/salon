<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\LoginService;
use Cache;
use App\Libary\Contracts\Http\ResponseInterface;

abstract class ApiBaseController extends Controller
{
    /**
     * 响应客户端
     * @var App\Libary\Contracts\Http\ResponseInterface
     */
    protected $appResp;
    /**
     * aes加密工具
     * @var App\Libary\Contracts\Encryption\EncrypterInterface
     */
    protected $aes;
    
    public function __construct(EncrypterInterface $aes)
    {
        $this->appResp = App::make('appResp');
        $this->aes = $aes;
    }
    
    /**
     * 编码数据
     * @param array|string $string 需要加密的数据
     */
    protected function encodeAppData($string='', $key='')
    {
        if (!empty($key)) {
            $this->aes->setKey($key);
        }
        if (is_array($string) && count($string)>1) {
            $string = create_linkstring($string);
        } elseif (is_array($string) && count($string)==1) {
            $string = $string[0];
        } else {
            $string = $string;
        }
        
        return $this->aes->encrypt(urlsafe_base64_encode($string));
    }
    
    /**
     * 
     * 加密客户端加密的aes数据
     * @param string $string
     */
    protected function decodeAppData($string, $key='')
    {
        if (empty($string)) {
            return null;
        }
        
        if (!empty($key)) {
            $this->aes->setKey($key);
        }
        $decodeData = urlsafe_base64_decode($this->aes->decrypt($string));
        if (empty($decodeData)) {
            return null;
        }
        return resolve_linkstring($decodeData);
    }
    
    /**
     * 检查是否已经登陆
     * 
     * @param $id 用户的id
     * @param $user_type 用户的类型
     * 
     * @return void
     */
    protected function isLogin($id, $user_type)
    {
        $key = $user_type . $id;
        
        $cacheValue = [];
        if (Cache::has($key)) {
            $cacheValue = Cache::get($key);
        }
        
        // 检查是否登陆
        if (!array_key_exists('token', $cacheValue) || empty($cacheValue['token'])) {// 未登录
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNAUTHORIZED,
                    '用户未登录'
            ));
        }
    }
}