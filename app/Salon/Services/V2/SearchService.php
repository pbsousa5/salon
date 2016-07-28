<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\SearchService as SearchSer;
use App\Salon\Repositories\V2\SupplierRepository;
use App\Salon\Repositories\V2\AddressRepository;

/**
 *
 *
 * @desc 搜索服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class SearchService extends SearchSer
{
    /**
     * 门店服务层
     * @var SupplierService
     */
    protected $supplierSer;
    
    /**
     * 地址的数据仓库
     * @var AddressRepository
     */
    protected $addressRe;
    
    /**
     * 门店数据层
     * @var SupplierRepository
     */
    protected $supplierRe;
    
    public function __construct(
            SupplierService $supplierSer,
            SupplierRepository $supplierRe,
            AddressRepository $addressRe
    ){
        parent::__construct($supplierSer, $supplierRe, $addressRe);
    }
}