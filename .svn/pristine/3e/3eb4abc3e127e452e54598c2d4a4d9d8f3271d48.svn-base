<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\LoginService as LoginSer;
use App\Salon\Repositories\V2\BarberRepository;
use App\Salon\Repositories\V2\SupplierRepository;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use App\Salon\Repositories\V2\ConsumerRepository;
use Cache;
use App\Libary\Util\String;

/**
 *
 *
 * @desc 登陆服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class LoginService extends LoginSer
{
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
     * @var Hasher
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
        parent::__construct($barberRe, $supplierRe, $consumerRe, $hasher);
    }
    
    /**
     * 门店实现多端登陆
     * @param array $credentials
     */
    protected function supplierLogin(array $credentials = [])
    {
        $supplier = $this->supplierRe->show($credentials);
        if (is_null($supplier)) {
            return null;
        }
        
        // 检查门店是否登陆过
        $key = self::USER_TYPE_SUPPLIER . $supplier->id;
        $cacheSupplier = Cache::get($key);
        if (Cache::has($key) && !empty($cacheSupplier['token'])) {// 门店已经登陆
            $supplier->token = $cacheSupplier['token'];
            return $supplier;
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
}