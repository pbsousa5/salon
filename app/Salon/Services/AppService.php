<?php

namespace App\Salon\Services;

use App\Salon\Repositories\BannerRepository;
use App\Libary\Util\String;
use App\Libary\Contracts\Push\PusherInterface;
use App\Libary\Push\Sms\PusherSMS;
use App\Salon\VersionApp;
/**
 * 
 * 
 * @desc app服务
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class AppService
{
    /**
     * 幻灯片的数据层
     * @var BannerRepository
     */
    protected $bannerRe;
    
    /**
     * 短信接口
     * @var PusherSMS
     */
    protected $sms;
    
    public function __construct(BannerRepository $bannerRe, PusherInterface $push)
    {
        $this->bannerRe = $bannerRe;
        $this->sms = $push;
    }
    
    /**
     * 根据设备类型，返回对应的初始化信息
     * 
     * @param string $type 设备类型 android ios，必须为小写
     * @param string $version 初始化的版本号
     * @return array
     */
    public function init($type, $version = 'v1')
    {
        switch ($type) {
            case 'android':
                // nobreak;
            case 'ios':
                $data = [
                        'qiniu_url' => config('appinit.imgurl'),
                        'server_url' => config('appinit.apiurl') . $version . '/',
                        'app_key' => env('APP_KEY'),
                        'app_version' => $version,
                        'server_time' => config('appinit.server_time'),
						'custmer_service_phone' => config('appinit.custmer_service_phone'),
                ];
                break;
            default:
                $data = null;
                break;
        }
        
        return $data;
    }
    
    /**
     * 检查更新，并获取当前的最新版本信息
     * 
     * @param integer $device_id app类型，andorid门店端，andorid用户端，ios门店端，ios用户端
     * @param integer $version_id 当前版本号
     * 
     * @return null|VersionApp
     */
    public function checkUpgrade($device_id, $version_id)
    {
        // 检查是否是最新版本
        $count = VersionApp::where('device_id', $device_id)->where('version_id', '>', $version_id)->count();
        if ($count == 0) {// 没有更新的版本
            return null;
        }
        
        // 查找最新的版本
        return VersionApp::where('device_id', $device_id)->orderBy('version_id', 'desc')->first();
    }
    
    /**
     * 获取所有幻灯片
     * @return array
     */
    public function banners()
    {
        $bannerCol = $this->bannerRe->index();
        if ($bannerCol->isEmpty()) {
            return null;
        }
        
        $banners = $bannerCol->toArray();
        foreach ($banners as $key=>$val) {
            unset($banners[$key]['banner_info']);
            unset($banners[$key]['created_at']);
            unset($banners[$key]['updated_at']);
            $banners[$key] = array_add($banners[$key], 'url', config('appinit.apiurl')."v1/app/banner/{$val['id']}");
        }
        
        return $banners;
    }
    
    /**
     * 获取幻灯片详细内容
     * @param integer $id 资源的id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getBannerInfo($id)
    {
        return $this->bannerRe->show($id);
    }
    
    /**
     *
     * 向指定号码发送验证码
     * @param string $account
     * @return void
     */
    public function sendSmsCode($mobile)
    {
        $message = [
                //String::randString(6, 1),
                'xx通过美丽地图预约了您的xx服务，时间为XX月XX日，XX:00，您可以通过18180921886联系Ta，有任何需要帮忙的地方，欢迎随时拨打我们的客服电话028-65294236',
                10,
        ];
    
        return $this->sms->push($mobile, $message);
    }
    
    /**
     *
     * 检验验证码是否合法
     * @param string $account 哪一个账号的验证码
     * @param string $smsCode 验证码内容
     * @return boolean
     */
    public function validateCode($account, $smsCode)
    {
        return $this->sms->validateCode($account, $smsCode);
    }
}