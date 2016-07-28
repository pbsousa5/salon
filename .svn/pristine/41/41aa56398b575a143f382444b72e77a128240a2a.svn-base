<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Libary\Contracts\Http\ResponseInterface;
use Validator;
use Cache;
use App\Salon\Services\SupplierService;
use Illuminate\Http\Request;
use App\Salon\Services\LoginService;
use App\Salon\Services\FundService;
use App\Salon\IncomeCashLog;
use App\Salon\Repositories\WithdrawCashLogRepository;

/**
 * 
 * 
 * @desc 门店提现，及账号管理
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月13日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Withdraw",
 *  description="门店提现，及账号管理、资金明细",
 *  produces="['application/json']"
 * )
 */
class WithdrawController extends ApiBaseController
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
        parent::__construct($aes);
        $this->supplierSer =$supplierSer;
        $this->fundSer = $fundSer;
        $this->loginSer = $loginSer;
        //$this->middleware('req');
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/withdraws/account_list",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取所有账号信息",
     *      notes="目前门店仅支持一个账号，后期可更改",
     *      type="FundAccount",
     *      nickname="list_account",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="生成的签名，请使用初始化接口中的app_key",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="supplier_id",
     *          description="门店的id，aes(supplier_id=xxx, app_key)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="u+YqIdUgApzljMTNsQP81A2OVxYfw+vM5JFgDwnTmYM="
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到信息"),
     *      @SWG\ResponseMessage(code=405, message="Validation exception")
     *  )
     * )
     */
    public function index(Request $request)
    {
        $inputs = $this->decodeAppData($request->input('supplier_id'));
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        $validator = Validator::make($inputs, [
                'supplier_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        $cacheValue = $this->loginSer->getUserCache($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        
        // 获取资金账号列表
        $list = $this->fundSer->listAccount($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '尚无账户信息'
            ));
        }
        
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_OK,
                '请求数据成功',
                $this->encodeAppData($list->toJson(), $cacheValue['token'])
        ));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/withdraws/supplier_fee",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取门店最新的金额信息",
     *      notes="总收入=可提现金额+已支付金额",
     *      type="FundRecord",
     *      nickname="get_fee",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="生成的签名，请使用初始化接口中的app_key",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="supplier_id",
     *          description="门店的id，aes(supplier_id=xxx, app_key)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="u+YqIdUgApzljMTNsQP81A2OVxYfw+vM5JFgDwnTmYM="
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到信息"),
     *      @SWG\ResponseMessage(code=405, message="Validation exception")
     *  )
     * )
     */
    public function showFee(Request $request)
    {
        $inputs = $this->decodeAppData($request->input('supplier_id'));
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        $validator = Validator::make($inputs, [
                'supplier_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        $cacheValue = $this->loginSer->getUserCache($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        
        $record = $this->fundSer->getFeeRecord($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        if (is_null($record)) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '查找的数据不存在'));
        }
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_OK,
                '请求数据成功',
                $this->encodeAppData($record->toJson(), $cacheValue['token'])
        ));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/withdraws/{user_id}/update_account",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="修改用户账号",
     *      notes="无",
     *      type="void",
     *      nickname="update_account",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="生成的签名，请使用初始化接口中的app_key",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="账号所有者id 门店或者理发师",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户类型，supplier:门店 barber:理发师 ,目前仅支持supplier",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="supplier",
     *          enum="['supplier','barber']"
     *      ),
     *      @SWG\Parameter(
     *          name="m_body",
     *          description="aes(user_name=x&card_number&mobile=x&fund_id, token)",
     *          required=true,
     *          type="FundAccount",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="5z8wJ0QT+GgKVyHK5HgewCBiU7E9N02pdRyT7eBvbm26vSUTpZJtpyvYUMjC4jZn+IvVKm2w622lMx5ZSw716PGxFPsMV9xopK9ilZAk3yM="
     *      ),
     *      @SWG\ResponseMessage(code=201, message="修改数据成功"),
     *      @SWG\ResponseMessage(code=404, message="not found"),
     *      @SWG\ResponseMessage(code=422, message="Validation exception")
     *  )
     * )
     */
    public function account(Request $request, $user_id)
    {
        $user_type = strtolower($request->input('user_type'));
        // 检查是否登陆
        $this->isLogin($user_id, $user_type);
        
        $cacheValue = $this->loginSer->getUserCache($user_id, $user_type);
        $inputs = $this->decodeAppData(bodys('json', 'data'), $cacheValue['token']);
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        $inputs['user_id'] = $user_id;
        $inputs['user_type'] = $user_type;
        $validator = Validator::make($inputs, [
                'user_name' => 'required|string',
                'user_id' => 'required|integer',
                'user_type' => 'required|in:supplier,barber',
                'card_number' => 'required|string',
                'mobile' => 'required|mobile',
                'fund_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        $fundAccount = $this->fundSer->updateAccount(['user_id'=>$user_id, 'user_type'=>$inputs['user_type']], $inputs);
        if (is_null($fundAccount)) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_UNAUTHORIZED, '修改的账号不存在'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '修改数据成功'));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/withdraws/apply",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="申请提现",
     *      notes="提现时，会直接提取门店在平台所有金额",
     *      type="void",
     *      nickname="apply",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="生成的签名，请使用初始化接口中的app_key",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="m_body",
     *          description="需要的数据:aes(supplier_id=x, app_key)",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="u+YqIdUgApzljMTNsQP81A2OVxYfw+vM5JFgDwnTmYM="
     *      ),
     *      @SWG\ResponseMessage(code=201, message="申请提现成功"),
     *      @SWG\ResponseMessage(code=401, message="用户无权进行该操作"),
     *      @SWG\ResponseMessage(code=404, message="not found"),
     *      @SWG\ResponseMessage(code=422, message="Validation exception"),
     *      @SWG\ResponseMessage(code=500, message="网络错误")
     *  )
     * )
     */
    public function withdraw()
    {
        $inputs = $this->decodeAppData(bodys('json', 'data'));
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        $validator = Validator::make($inputs, [
                'supplier_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);

        // 检查账号是否合法
        $flag = $this->fundSer->getAccountStatus($inputs['supplier_id']);
        if ($flag !== true) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_FORBIDDEN,
                    $this->fundSer->getAccountStatusTxt($flag)
            ));
        }
        
        // 检查能否进行提现操作
        $flag = $this->fundSer->getFundStatus($inputs['supplier_id']);
        if ($flag !== true) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_FORBIDDEN,
                    $this->fundSer->getFundStatusTxt($flag)
            ));
        }
        
        // 执行操作
        $status = $this->fundSer->insertApply($inputs['supplier_id'], LoginService::USER_TYPE_SUPPLIER);
        if (!$status) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "服务器异常"));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, "申请成功"));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/withdraws/{user_id}/list_income",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定用户的资金明细",
     *      notes="无",
     *      type="void",
     *      nickname="apply",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="生成的签名，请使用初始化接口中的app_key",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="账号所有者id 门店或者理发师",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户类型，supplier:门店 barber:理发师",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="supplier",
     *          enum="['supplier','barber']"
     *      ),
     *      @SWG\Parameter(
     *          name="fee_source",
     *          description="收入来源,此字段在user_type=supplier时必须存在，supplier:门店(此时理发师不存在) barber:理发师",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="supplier",
     *          enum="['supplier','barber']"
     *      ),
     *      @SWG\Parameter(
     *          name="page",
     *          description="默认获取第一页数据",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="per_page",
     *          description="开发阶段，默认2条(建议，如果是wifi情况下，可扩大请求数，若是3g可减小)",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="2"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="申请提现成功"),
     *      @SWG\ResponseMessage(code=401, message="用户无权进行该操作"),
     *      @SWG\ResponseMessage(code=404, message="未找到相关记录"),
     *      @SWG\ResponseMessage(code=422, message="数据验证异常"),
     *      @SWG\ResponseMessage(code=500, message="网络错误")
     *  )
     * )
     */
    public function indexIncome(Request $request, $user_id)
    {
        $inputs = $request->only(['page', 'per_page']);
        $inputs['user_id'] = $user_id;
        $inputs['user_type'] = strtolower($request->input('user_type'));
        $inputs['fee_source'] = strtolower($request->input('fee_source'));
        $validator = Validator::make($inputs, [
                'user_id' => 'required|integer',
                'user_type' => 'required|in:supplier,barber',
                'fee_source' => 'required_if:user_type,supplier|in:supplier,barber',
                'page' => 'integer',
                'per_page' => 'integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($user_id, $inputs['user_type']);
        
        $where = [
                'user_id' => $user_id,
                'user_type' => $inputs['user_type'],
        ];
        $list = $this->fundSer->listDetailIncome($where, $inputs['fee_source'], $inputs['per_page']);
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '查找的数据不存在'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取收入明细成功', $list));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/withdraws/{supplier_id}/list_withdraw",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取门店提现记录",
     *      notes="无",
     *      type="WithdrawCashLog",
     *      nickname="apply",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="生成的签名，请使用初始化接口中的app_key",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="supplier_id",
     *          description="账号所有者id 门店或者理发师",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="page",
     *          description="默认获取第一页数据",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="per_page",
     *          description="开发阶段，默认2条(建议，如果是wifi情况下，可扩大请求数，若是3g可减小)",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="2"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="申请提现成功"),
     *      @SWG\ResponseMessage(code=401, message="用户无权进行该操作"),
     *      @SWG\ResponseMessage(code=404, message="未找到相关记录"),
     *      @SWG\ResponseMessage(code=422, message="数据验证异常"),
     *      @SWG\ResponseMessage(code=500, message="网络错误")
     *  )
     * )
     */
    public function indexWithdraw(
            Request $request,
            WithdrawCashLogRepository $withdrawCashLog,
            $supplier_id
    ){
        $inputs = $request->only(['page', 'per_page']);
        $inputs['supplier_id'] = $supplier_id;
        $validator = Validator::make($inputs, [
                'supplier_id' => 'required|integer',
                'page' => 'integer',
                'per_page' => 'integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($supplier_id, LoginService::USER_TYPE_SUPPLIER);
        
        $list = $withdrawCashLog->index(['supplier_id'=>$supplier_id], '', $inputs['per_page'])->getCollection();
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '查找的数据不存在'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取成功', $list));
    }
}