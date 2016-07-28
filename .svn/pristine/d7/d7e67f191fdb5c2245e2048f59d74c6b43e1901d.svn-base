<?php

namespace App\Salon\Services;

use Qiniu\Auth;
use App;
/**
 * 
 * 
 * @desc 七牛相关数据生成
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class QiniuTokenService
{
    private $bucket;// 空间名称
    private $callbackUrl;
    private $auth;
    
    public function __construct()
    {
        $config = App::make('config')['qiniu'];
        $this->bucket = $config['bucket'];
        $this->callbackUrl = $config['callback_url'];
        $this->auth = new Auth($config['access_key'], $config['secret_key']);
    }
    
    /**
     * 
     * 生成上传凭证
     * @param string $imgKey 上传图片名称
     * @param integer $expires 有效时间
     * @param array $policy 上传策略
     * @return string
     */
    public function createUploadToken($imgKey='', $expires=3600, $policy=[])
    {
        $policy = [
                'callbackUrl' => $this->callbackUrl,
                'callbackBody' => 'key=$(key)&hash=$(etag)&w=$(imageInfo.width)&h=$(imageInfo.height)',
                'callbackBodyType' => "application/json",
                'insertOnly' => 1,
        ];
        
        if (!empty($imgKey)) {
            return $this->auth->uploadToken($this->bucket, $imgKey, $expires, $policy);
        } else {
            return $this->auth->uploadToken($this->bucket, null, $expires, $policy);
        }
        
    }
    
    /**
     *
     * 生成下载凭证
     * @param string $imgUrl 下载图片的url
     * @param integer $expires 有效时间
     * @return string
     */
    public function createDownloadToken($imgUrl, $expires=3600)
    {
        return $this->auth->privateDownloadUrl($baseUrl, $expires);
    }
    
    /**
     *
     * 生成管理凭证
     * @param string $urlString 需要操作的url
     * @param string $body HTTP Body
     * @return string
     */
    public function createAccessToken($urlString, $body, $contentType=null)
    {
        return $this->auth->signRequest($urlString, $body, $contentType);
    }
}