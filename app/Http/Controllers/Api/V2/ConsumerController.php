<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\ConsumerController as ConsumerCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\LoginService;
use App\Salon\Services\V2\ConsumerService;

class ConsumerController extends ConsumerCon
{
    /**
     * 消费者服务
     * @var ConsumerService
     */
    protected $consumerSer;
    
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    public function __construct(
            EncrypterInterface $aes,
            ConsumerService $consumerSer,
            LoginService $loginSer
    ){
        parent::__construct($aes, $consumerSer, $loginSer);
    }
}