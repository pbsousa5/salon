<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\WithdrawController as WithdrawCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\SupplierService;
use App\Salon\Services\V2\FundService;
use App\Salon\Services\V2\LoginService;

class WithdrawController extends WithdrawCon
{
    /**
     * 门店服务层
     * @var SupplierService
     */
    protected $supplierSer;
    
    /**
     * 资金服务层
     * @var FundService
     */
    protected $fundSer;
    
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    public function __construct(
            EncrypterInterface $aes,
            SupplierService $supplierSer,
            FundService $fundSer,
            LoginService $loginSer
    ){
        parent::__construct($aes, $supplierSer, $fundSer, $loginSer);
    }
}