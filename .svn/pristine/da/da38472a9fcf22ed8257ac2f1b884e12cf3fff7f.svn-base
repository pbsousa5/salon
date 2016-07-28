<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\NotifyController as NotifyCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\NotifyService;

class NotifyController extends NotifyCon
{
    /**
     * 消息通知的服务层
     * @var NotifyService
     */
    protected $notifySer;
    
    public function __construct(
        EncrypterInterface $aes,
        NotifyService $notifySer
    ){
        parent::__construct($aes, $notifySer);
    }
}