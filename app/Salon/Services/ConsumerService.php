<?php

namespace App\Salon\Services;

use App\Salon\Consumer;
use App\Libary\Util\String;
use Illuminate\Hashing\BcryptHasher;
use Cache;
use App\Salon\Repositories\ConsumerRepository;
/**
 * 
 * 
 * @desc 消费者服务类
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class ConsumerService
{
    /**
     * 数据仓库实例
     * @var ConsumerRepository
     */
    protected $consumerRe;
    
    /**
     * 消息服务层
     * @var NotifyService
     */
    protected $notifySer;
    
    public function __construct(ConsumerRepository $consumerRe, NotifyService $notifySer)
    {
        $this->consumerRe = $consumerRe;
        $this->notifySer = $notifySer;
    }
    
    /**
     * 
     * 检查账号是否存在，合法等
     * @param string $account 需要检查的账号
     * @return boolean
     */
    public function checkAccount($account)
    {
        $consumer = $this->consumerRe->show(['mobile'=>$account]);
        if (is_null($consumer)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 处理用户数据
     * 
     * @param Consumer $consumer 用户模型
     */
    public function handleData(Consumer $consumer)
    {
        if (empty($consumer->nickname)) {
            $consumer->nickname = '';
        }
        if (empty($consumer->head_img)) {
            $consumer->head_img = '';
        }
        
        if ($consumer->gender == 1) {
            $consumer->gender = '男';
        } elseif ($consumer->gender == 0) {
            $consumer->gender = '女';
        } else {
            $consumer->gender = '未设置';
        }
        $consumer->age_tag = $this->getAgeTagTxt($consumer->age_tag);
        
        $consumer->new_notify = 0;// 没有新消息
        // 获取是否有未读消息
        $hasNewNotify = $this->notifySer->getUserNotifyNotRead($consumer->id, 'consumer');
        if ($hasNewNotify) {
            $consumer->new_notify = 1;// 有新消息
        }
        
        return $consumer;
    }
    
    /**
     * 
     * 添加一个新的消费者
     * @param array $inputs 添加消费者
     * @return array|null
     */
    public function addConsumer(array $inputs)
    {
        // 检查用户是否已经存在
        $flag = $this->checkAccount($inputs['mobile']);
        if ($flag) {// 用户已存在，无序后续操作
            return null;
        }
        
        return $this->consumerRe->store($inputs);
    }
    
    // 根据age_tag的值，获取对应的年龄标签
    private function getAgeTagTxt($age_tag)
    {
        // '0':'其他','1':'60后', '2':'70后', '3':'80后', '4':'90后', '5':'00后'
        switch ($age_tag) {
            case 1:
                return '60后';
                // no break;
            case 2:
                return '70后';
                // no break;
            case 3:
                return '80后';
                // no break;
            case 4:
                return '90后';
                // no break;
            case 5:
                return '00后';
                // no break;
            default:
                return '其他';
                // no break;
        }
    }
    
    /**
     * 
     * 修改消费者基本信息
     * @param array $where 要修改的资源查找条件
     * @param array $inputs 修改消费者数据
     * @return App\Salon\Consumer|null
     */
    public function modifyConsumer($where, $inputs)
    {
        return $this->consumerRe->update($where, $inputs);
    }
    
    /**
     * 
     * 获取单个用户详细信息
     * @param array $condition 查找信息，使用['id'=>1] or ['mobile'=>123]等等
     * @return App\Salon\Consumer|null
     */
    public function getSingleInfo($condition=[])
    {
        $consumer = $this->consumerRe->show($condition);
        
        return $this->handleData($consumer);
    }
}