<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\OrderService;
use Validator;
use App\Libary\Contracts\Http\ResponseInterface;
use Cache;
use App\Salon\Services\BackOrderService;
use App\Salon\Services\ConsumerService;
use App\Salon\Services\LoginService;
use App\Salon\Repositories\OrderInfoRepository;

/**
 * 
 * 
 * @desc 用户申请退单
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月12日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/BackOrder",
 *  description="用户退单接口",
 *  produces="['application/json']"
 * )
 */
class BackOrderController extends ApiBaseController
{
    
    /**
     * 订单服务层
     * @var OrderService
     */
    protected $orderSer;
    
    /**
     * 退单服务层
     * @var BackOrderService
     */
    protected $backSer;
    
    /**
     * 消费者服务层
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
            OrderService $orderSer,
            BackOrderService $backSer,
            ConsumerService $consumerSer,
            LoginService $loginSer
    ){
        parent::__construct($aes);
        $this->orderSer = $orderSer;
        $this->backSer = $backSer;
        $this->consumerSer = $consumerSer;
        $this->loginSer = $loginSer;
        //$this->middleware('req');
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/back_orders/{order_id}",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="添加一个退单申请",
     *      notes="只有付款未消费的订单才能进行退款申请",
     *      type="void",
     *      nickname="back_orders",
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
     *          name="order_id",
     *          description="退款的订单id",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="order_product_id",
     *          description="退款的订单产品id",
     *          required=true,
     *          type="integer",
     *          paramType="query"
     *      ),
     *      @SWG\Parameter(
     *          name="consumer_id",
     *          description="用户id,使用aes加密，key为初始接口返回的app_key。aes(consumer_id=xx, app_key)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          defaultValue="E1/WNXI/3ePaAmwvqg5SSO7FiXRiyOOdfoiOAid7eKk="
     *      ),
     *      @SWG\Parameter(
     *          name="postscript",
     *          description="退单理由",
     *          required=true,
     *          type="string",
     *          paramType="form"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="退单申请提交成功"),
     *      @SWG\ResponseMessage(code=422, message="请求数据验证失败"),
     *      @SWG\ResponseMessage(code=405, message="Validation exception")
     *  )
     * )
     */
    public function store(Request $request, OrderInfoRepository $orderInfoRe, $order_id)
    {
        $inputs['order_product_id'] = $request->input('order_product_id');
        $inputs['order_id'] = $order_id;
        $inputs['consumer_id'] = $this->decodeAppData($request->input('consumer_id'))['consumer_id'];
        if (empty($inputs['consumer_id'])) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据加密错误'
            ));
        }
        $inputs['postscript'] = $request->input('postscript');
        // 检查订单数据
        $validator = Validator::make($inputs, [
                'order_product_id' => 'required|integer',
                'order_id' => 'required|integer',
                'consumer_id' => 'required|integer',
                'postscript' => 'required|string',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['consumer_id'], LoginService::USER_TYPE_CONSUMER);
        
        // 获取订单状态
        $orderInfo = $orderInfoRe->show(['id'=>$order_id, 'consumer_id'=>$inputs['consumer_id']]);
        $o_status = $this->orderSer->getOrderStatus($orderInfo);
        if ($o_status!=3) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    $this->orderSer->getOrderStatusTxt($o_status)
            ));
        }

        // 添加退单申请
        $flag = $this->backSer->addBackOrder($orderInfo, $inputs['order_product_id'], $inputs['postscript']);
        if (!$flag) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '退单申请失败，只能申请自己的订单与未消费订单'
            ));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '退单申请成功'));
    }
}