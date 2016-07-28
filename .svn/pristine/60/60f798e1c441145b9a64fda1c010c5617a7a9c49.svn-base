<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\ReviewService;
use App\Salon\Services\ConsumerService;
use App\Libary\Contracts\Http\ResponseInterface;
use Validator;
use App\Salon\Services\OrderService;
use Illuminate\Http\Request;
use App\Events\ConsumerReviewEvent;
use App\Listeners\OrderListener;
use App\Events\ReviewOrderEvent;
use App\Salon\Services\LoginService;
use App\Salon\Repositories\OrderInfoRepository;
use App\Events\MarkUserfulAddScoreEvent;
use App\Salon\Repositories\ReviewTagRepository;

/**
 * 
 * 
 * @desc 评论功能
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月5日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Review",
 *  description="评论相关API",
 *  produces="['application/json']"
 * )
 */
class ReviewController extends ApiBaseController
{
    /**
     * 评论的服务层
     * @var ReviewService
     */
    protected $reviewSer;
    /**
     * 消费者服务层
     * @var ConsumerService
     */
    protected $consumerSer;
    
    /**
     * 订单的服务层
     * @var OrderService
     */
    protected $orderSer;
    
    /**
     * 登陆服务层
     * @var LoginService
     */
    protected $loginSer;
    
    /**
     * 评论标签数据仓库
     * @var ReviewTagRepository
     */
    protected $reviewTagRe;
    
    public function __construct(
            EncrypterInterface $aes,
            ReviewService $reviewSer,
            ConsumerService $consumerSer,
            OrderService $orderSer,
            LoginService $loginSer,
            ReviewTagRepository $reviewTagRe
    ){
        parent::__construct($aes);
        $this->reviewSer = $reviewSer;
        $this->consumerSer = $consumerSer;
        $this->orderSer = $orderSer;
        $this->loginSer = $loginSer;
        $this->reviewTagRe = $reviewTagRe;
        //$this->middleware('req');
    }

    public function tag($consumer_id)
    {
        // 检查是否登陆
        $this->isLogin($consumer_id, LoginService::USER_TYPE_CONSUMER);
        
        $list = $this->reviewTagRe->index();
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '未找到数据'
            ));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $list));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/reviews/{user_id}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="查看门店或理发师的评论,注意评论tag结构发生改变(1.1)",
     *      notes="model中的某些字段未返回，请以实际返回的字段为准",
     *      type="ListReview",
     *      nickname="list",
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
     *          description="门店或理发师id",
     *          required=true,
     *          type="string",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
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
     *      @SWG\ResponseMessage(code=200, message="请求数据成功"),
     *      @SWG\ResponseMessage(code=404, message="请求的数据不存在"),
     *      @SWG\ResponseMessage(code=422, message="请求数据验证失败"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function index(Request $request, $user_id)
    {
        // 获取参数，并验证
        $inputs = $request->only('page', 'per_page', 'user_type');
        $inputs = array_add($inputs, 'user_id', $user_id);
        $validator = Validator::make($inputs, [
                'page' => 'integer',
                'per_page' => 'integer',
                'user_id' => 'required|integer',
                'user_type' => 'required|in:supplier,barber'
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        $list = $this->reviewSer->listReviews($user_id, $inputs['user_type'], $inputs['per_page']);
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '未找到数据'));
        }
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '请求数据成功', $list));
    }
    
    public function store($consumer_id)
    {
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                '该版本已不支持评论,请到应用市场下载最新版'
        ));
        
        // 检查是否登陆
        $this->isLogin($consumer_id, LoginService::USER_TYPE_CONSUMER);
        
        // 获取body数据
        $inputs = json_decode(bodys(), true);
        $inputs = array_add($inputs, 'consumer_id', $consumer_id);
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'consumer_id' => 'required|integer',
                'supplier_id' => 'required|integer',
                'product_id' => 'integer',
                'order_id' => 'required|integer',
                'barber_id' => 'required_if:product_id,0|integer',
                'barber_nickname' => 'required_if:product_id,0|string',
                'barber_product_id' => 'required_if:product_id,0|integer',
                'order_product_id' => 'required|integer',
                'service_score' => 'required|integer',
                'skill_score' => 'required|integer',
                'env_score' => 'required|integer',
                'comment_txt' => 'required|string',
                'comment_imgs' => 'array',
                'queue_time' => 'required|string',
                'review_tags' => 'required|array',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据验证未通过',
                    $validator->messages()
            ));
        }
        
        // 检查是否有多余数据
        $inputs = array_remove_keys($inputs, ['is_highgrade', 'zan_num']);
        
        if ($inputs['product_id'] !=0) {// 如果产品不为0，则表示获取的是门店项目，与理发师无关
            $inputs['barber_product_id'] = $inputs['barber_id'] = 0;
            $inputs['barber_nickname'] = '';
        }
        
        // 检查用户是否有权限评论改产品(备注：只有自己购买的产品，并且未评论过的产品，才能进行评价)
        $orderInfo = $orderInfoRe->show(['id'=>$inputs['order_id'], 'consumer_id'=>$consumer_id]);
        $status = $this->orderSer->getOrderStatus($orderInfo);
        if ($status != 4) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_FORBIDDEN,
                    $this->orderSer->getOrderStatusTxt($status)
            ));
        }
        if ($this->reviewSer->checkExist($inputs['consumer_id'], $inputs['order_product_id'])) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_FORBIDDEN,
                    '用户已评论，不能进行评论'
            ));
        }

        // 添加评论
        $flag = $this->reviewSer->addReview($inputs);
        if ($flag) {
            $data['supplier_id'] = $inputs['supplier_id'];
            $data['barber_id'] = isset($inputs['barber_id']) ? $inputs['barber_id'] : 0;
            
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '评论成功'));
        } else {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    '服务器异常，是否成功无法判断'
            ));
        }
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/reviews/mark_heart",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="用户点击红心",
     *      notes="用户如果点击后，不能取消",
     *      type="void",
     *      nickname="mark_heart",
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
     *          name="consumer_id",
     *          description="用户id",
     *          required=true,
     *          type="integer",
     *          paramType="form",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="review_id",
     *          description="评论id",
     *          required=true,
     *          type="integer",
     *          paramType="form",
     *          allowMultiple=false
     *      ),
     *      @SWG\ResponseMessage(code=201, message="点击有用成功"),
     *      @SWG\ResponseMessage(code=401, message="用户未登录，或无权限请求该接口"),
     *      @SWG\ResponseMessage(code=403, message="用户已经点击过有用"),
     *      @SWG\ResponseMessage(code=422, message="请求的数据验证不通过"),
     *      @SWG\ResponseMessage(code=500, message="服务器错误，不能判断操作是否有效")
     *  )
     * )
     */
    public function markUserful(Request $request)
    {
        $inputs = $request->only(['consumer_id', 'review_id']);
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'consumer_id' => 'required|integer',
                'review_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据验证未通过',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['consumer_id'], LoginService::USER_TYPE_CONSUMER);
        
        // 检查是否已经点击过
        $flag = $this->reviewSer->checkClickHeart($inputs['consumer_id'], $inputs['review_id']);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_FORBIDDEN,
                    '用户已经点击过，无法再次点击'
            ));
        }
        
        // 更新有用
        $flag = $this->reviewSer->clickHeart($inputs['consumer_id'], $inputs['review_id']);
        if ($flag) {
            event(new MarkUserfulAddScoreEvent($inputs['review_id']));
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '操作成功'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '该条评论不存在'));
    }
}