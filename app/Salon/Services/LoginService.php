<?php

namespace App\Salon\Services;

use App\Salon\Repositories\BarberRepository;
use Cache;
use App\Libary\Util\String;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Str;
use App\Salon\Repositories\SupplierRepository;
use App\Salon\Repositories\ConsumerRepository;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;

/**
 * 
 * 
 * @desc 登陆服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 */
class LoginService
{
    const USER_TYPE_SUPPLIER = 'supplier';#门店
    const USER_TYPE_CONSUMER = 'consumer';#用户
    const USER_TYPE_BARBER = 'barber';#理发师
    
    /**
     * The BarberRepository Instance
     * @var BarberRepository
     */
    protected $barberRe;
    
    /**
     * The SupplierRepository instancel
     * @var SupplierRepository
     */
    protected $supplierRe;
    
    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;
    
    /**
     * The ConsumerRepository instance.
     * @var ConsumerRepository
     */
    protected $consumerRe;
    
    public function __construct(
            BarberRepository $barberRe,
            SupplierRepository $supplierRe,
            ConsumerRepository $consumerRe,
            HasherContract $hasher
    ){
        $this->barberRe = $barberRe;
        $this->supplierRe = $supplierRe;
        $this->consumerRe = $consumerRe;
        $this->hasher = $hasher;
    }
    
    /**
     * 用户登陆接口
     * 
     * @param array $credentials
     * @param string $type 登陆类型 supplier:门店 barber:理发师 consumer:用户
     * @return Illuminate\Database\Eloquent\Model
     */
    public function loginApp(array $credentials = [], $type='')
    {
        switch ($type) {
            case self::USER_TYPE_SUPPLIER:
                $user = $this->supplierLogin($credentials);
                break;
            case self::USER_TYPE_BARBER:
                $user = $this->barberLogin($credentials);
                break;
            case self::USER_TYPE_CONSUMER:
                $user = $this->consumerLogin($credentials);
                break;
            default:
                $user = null;
                break;
        }
        
        if (! is_null($user)) {
            // 触发登录事件
            event(new UserLoggedIn($user, $type));
        }
        
        return $user;
    }
    
    /**
     * 用户退出登录接口
     * 
     * @param integer $user_id
     * @param string $type 登陆类型 supplier:门店 barber:理发师 consumer:用户
     * @return boolean
     */
    public function logoutApp($user_id, $type = '')
    {
        $key = $type.$user_id;
        
        if (!Cache::has($key)) {
            return false;
        }
        
        // 触发退出事件
        event(new UserLoggedOut(Cache::get($key), $type));
        
        $cache = Cache::get($key);
        $cache['token'] = '';
        
        Cache::put($key, $cache, config('appinit.expire'));
        return true;
    }
    
    /**
     * 判断用户是否登陆，并获取用户缓存数据
     *
     * @param integer $user_id
     * @param string $type 登陆类型 supplier:门店 barber:理发师 consumer:用户
     * @return array 返回缓存的信息
     */
    public function getUserCache($user_id, $type = 'supplier')
    {
        $key = $type.$user_id;
        
        if (!Cache::has($key)) {
            return null;
        }
        
        $cacheValue = Cache::get($key);
        Cache::put($key, $cacheValue, config('appinit.expire'));// 延长其过期时间
        
        return $cacheValue;
    }
    
    /**
     * 理发师登陆
     * @param array $credentials
     */
    protected function barberLogin(array $credentials = [])
    {
        $barber = $this->barberRe->getByCredentials($credentials);
        if (is_null($barber)) {
            return null;
        }
        $barber->token = String::randString(32, 0);
        
        $cacheValue = [
                'id' => $barber->id,
                'name' => $barber->barber_nickname,
                'head_img' => $barber->barber_head_img,
                'mobile' => $barber->barber_mobile,
                'token' => $barber->token,
                'longitude' => $barber->barber_longitude,
                'latitude' => $barber->barber_latitude,
                'channel_id' => '',
                'source' => '',
        ];
        
        if ($this->validCredentials($barber, $credentials)) {
            // 验证通过，缓存信息
            if ($this->cacheValue($cacheValue, self::USER_TYPE_BARBER)) {
                return $barber;
            }
        }
        
        return null;
    }
    
    /**
     * 门店登陆
     * @param array $credentials
     */
    protected function supplierLogin(array $credentials = [])
    {
        $supplier = $this->supplierRe->show($credentials);
        if (is_null($supplier)) {
            return null;
        }
        $supplier->token = String::randString(32, 0);
        
        $cacheValue = [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'mobile' => $supplier->mobile,
                'token' => $supplier->token,
                'longitude' => $supplier->longitude,
                'latitude' => $supplier->latitude,
                'channel_id' => '',
                'source' => '',
        ];
        
        if ($this->validCredentials($supplier, $credentials)) {
            // 验证通过，缓存信息
            if ($this->cacheValue($cacheValue, self::USER_TYPE_SUPPLIER)) {
                return $supplier;
            }
        }
        
        return null;
    }
    
    /**
     * 用户登陆
     * @param array $credentials
     */
    protected function consumerLogin(array $credentials = [])
    {
        $consumer = $this->consumerRe->show($credentials);
        
        if (is_null($consumer)) {
            return null;
        }
        $consumer->token = String::randString(32, 0);
        
        $cacheValue = [
                'id' => $consumer->id,
                'name' => $consumer->nickname,
                'mobile' => $consumer->mobile,
                'token' => $consumer->token,
                'channel_id' => '',
                'source' => '',
        ];
        
        if ($this->validCredentials($consumer, $credentials)) {
            // 验证通过，缓存信息
            if ($this->cacheValue($cacheValue, self::USER_TYPE_CONSUMER)) {
                return $consumer;
            }
        }
        
        return null;
    }
    
    /**
     * 检查是否获取到的用户信息，是否与验证信息匹配
     * 
     * @param Illuminate\Database\Eloquent\Model $user
     * @param array $credentials
     * @return bool
     */
    protected function validCredentials($user, array $credentials)
    {
        $plain = $credentials['password'];
        
        if (is_null($user)) {
            return false;
        }
        
        return $this->hasher->check($plain, $user->password);
    }
    
    /**
     * 完成登陆操作，更新缓存信息
     * 
     * @param array $cacheValue
     * @param string $type 登陆类型 supplier:门店 barber:理发师 consumer:用户
     * @return boolean
     */
    protected function cacheValue($cacheValue, $type)
    {
        $key = $type.$cacheValue['id'];
        if (Cache::has($key)) {
            Cache::forget($key);
        }
        
        return Cache::add($type.$cacheValue['id'], $cacheValue, config('appinit.expire'));
    }
}