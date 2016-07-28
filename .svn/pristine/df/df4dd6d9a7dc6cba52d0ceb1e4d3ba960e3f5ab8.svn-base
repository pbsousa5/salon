<?php

namespace App\Salon\Services;

use App\Salon\Repositories\FundAccountRepository;
use App\Salon\Repositories\FundRecordRepository;
use App\Salon\FundAccount;
use App\Salon\Repositories\WithdrawCashLogRepository;
use App\Salon\Repositories\IncomeCashLogRepository;
use Illuminate\Support\Str;
use App\Salon\IncomeCashLog;

/**
 * 
 * 
 * @desc 资金服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月13日
 */
class FundService
{
    /**
     * 资金账户数据仓库
     * @var FundAccountRepository
     */
    protected $fundAccountRe;
    
    /**
     * 资金记录数据仓库
     * @var FundRecordRepository
     */
    protected $fundRecordRe;
    
    /**
     * 提现的数据仓库
     * @var WithdrawCashLogRepository
     */
    protected $withdrawRe;
    
    /**
     * The IncomeCashLogRepository instance.
     * @var IncomeCashLogRepository
     */
    protected $incomeCashLogRe;
    
    public function __construct(
            FundAccountRepository $fundAccountRe,
            FundRecordRepository $fundRecordRe,
            WithdrawCashLogRepository $withdrawRe,
            IncomeCashLogRepository $incomeCashLogRe
    ){
        $this->fundAccountRe = $fundAccountRe;
        $this->fundRecordRe = $fundRecordRe;
        $this->withdrawRe = $withdrawRe;
        $this->incomeCashLogRe = $incomeCashLogRe;
    }
    
    /**
     * 获取资金列表
     * 
     * @param array $where 查询条件
     * @param string $user_type 用户类型 supplier:门店
     * 
     */
    public function listAccount($user_id, $user_type='supplier')
    {
        $where['user_id'] = $user_id;
        $list = $this->fundAccountRe->index($where, $user_type);
        
        return $list;
    }
    
    /**
     * 根据门店id，获取门店的资金详情
     *
     * @param integer $user_id 用户id
     * @param string $user_type 用户类型 supplier:门店
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getFeeRecord($user_id, $user_type='suppleir')
    {
        $record = $this->fundRecordRe->show($user_id, $user_type);
        
        if (Str::equals('supplier', $user_type)) {
            $record->detail = $this->incomeCashLogRe->sumCashFee($user_id);
        }
        
        return $record;
    }
    
    /**
     * 根据更新条件，修改账号信息
     *
     * @param array $where 更新数据的条件
     * @param array $inputs 输入的信息
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function updateAccount($where, array $inputs)
    {
        if (array_key_exists('id', $inputs)) {
            unset($inputs['id']);
        }
        if (array_key_exists('user_id', $inputs)) {
            unset($inputs['user_id']);
        }
        if (array_key_exists('user_type', $inputs)) {
            unset($inputs['user_type']);
        }
        
        return $this->fundAccountRe->update($where, $inputs);
    }
    
    /**
     * 检查账号状态
     * 
     * @param integer $supplier_id 门店id
     * 
     * @return boolean
     */
    public function getAccountStatus($supplier_id)
    {
        $model = $this->fundAccountRe->show($supplier_id, 'supplier');
        
        if (empty($model)) {#查找的账号不存在
            return 1;
        } elseif (empty($model->user_name) || empty($model->card_number) || empty($model->mobile)) {#账号资料不完善
            return 2;
        } else {
            return true;
        }
    }
    
    /**
     * 获取账户状态文本信息
     * 
     * @param integer $status 账号状态
     * @return string
     */
    public function getAccountStatusTxt($status)
    {
        switch ($status) {
            case 1:
                return "查找的账号不存在";
                break;
            case 2:
                return "账号信息不完整";
                break;
            default:
                return '状态不能识别';
                break;
        }
    }
    
    /**
     * 检查资金状态（是否有提现审核中的记录，是否还有资金可供提现）
     *
     * @param integer $supplier_id 门店id
     *
     * @return boolean
     */
    public function getFundStatus($supplier_id)
    {
        $fund = $this->fundRecordRe->show($supplier_id, 'supplier');
        if ($fund->balance_fee == 0) {#余额不足，不能提现
            return 1;
        }
        
        // 检查是否有正在审核中的提现操作
        $count = $this->withdrawRe->countApplyInfo($supplier_id, ['is_verify'=>0]);
        if ($count != 0) {
            return 2;
        }
        
        return true;
    }
    /**
     * 获取资金状态文本信息
     *
     * @param integer $status 状态
     * @return string
     */
    public function getFundStatusTxt($status)
    {
        switch ($status) {
            case 1:
                return "余额不足";
                // no break;
            case 2:
                return "有个一提现申请正在审核中";
                // no break;
            default:
                return '状态不能识别';
                // no break;
        }
    }
    
    /**
     * 执行提现操作
     *
     * @param integer $user_id 门店或者理发师id
     * @param string $user_type 用户类型 supplier：门店 barber:理发店
     * @return boolean
     */
    public function insertApply($user_id, $user_type='supplier')
    {
        // 获取门店账号、资金信息
        $account = $this->fundAccountRe->show($user_id, $user_type);
        $fund = $this->fundRecordRe->show($user_id, $user_type);
        
        $inputs['supplier_id'] = $user_id;
        $inputs['fund_account_id'] = $account->id;
        $inputs['cash_fee'] = $fund->balance_fee;// 门店能够体现的金额
        $inputs['pay_fee'] = $fund->total_pay_fee;// 实际支付的价格
        $inputs['user_name'] = $account->user_name;
        $inputs['card_number'] = $account->card_number;
        $inputs['pay_code'] = $account->pay_code;
        
        return $this->withdrawRe->store($inputs);
    }
    
    /**
     * 根据条件，获取用户的资金明细情况
     * 
     * @param array $where 查询资金明细的条件，一般为用户id与用户类型
     * @param string $fee_source 资金的来源
     * @param integer $size 获取多少条数据
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function listDetailIncome($where, $fee_source='supplier', $size = 10)
    {
        $list = $this->incomeCashLogRe->index($where, $fee_source, $size)->getCollection();
        
        foreach ($list as $key => $fund) {
            if (! $fund->consumer->nickname) {
                $fund->consumer->nickname = '';
            }
            if (! $fund->consumer->head_img) {
                $fund->consumer->head_img = '';
            }
            unset($fund->consumer->account);
            
            // 获取对应的项目名称
            $list[$key]->product_name = $fund->orderInfo->orderProducts()->pluck('product_name');
        }
        
        $data = $list->toArray();
        while (list($key,$val)= each($data)) {
            unset($data[$key]['order_info']);
        }
        
        return collect($data);
    }
}