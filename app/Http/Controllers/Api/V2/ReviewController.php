<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\ReviewController as ReviewCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\ReviewService;
use App\Salon\Services\V2\ConsumerService;
use App\Salon\Services\V2\OrderService;
use App\Salon\Services\V2\LoginService;
use App\Salon\Contracts\Repositories\ReviewTagRepositoryInterface;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Salon\Repositories\V2\ReviewTagRepository;
use Validator;

/**
 *
 *
 * @desc 评论功能，重载部分获取tag的与评论的部分方法
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
class ReviewController extends ReviewCon
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
        parent::__construct($aes, $reviewSer, $consumerSer, $orderSer, $loginSer, $reviewTagRe);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/reviews/{consumer_id}/tags",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取用于评论的tags,重构方法(1.1)",
     *      notes="未登录用户不能调用",
     *      type="void",
     *      nickname="get_tags",
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
     *          description="用户的id",
     *          required=true,
     *          type="string",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到数据"),
     *      @SWG\ResponseMessage(code=500, message="服务器异常")
     *  )
     * )
     */
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
        
        foreach ($list as $tag) {
            unset($tag->id);
        }
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $list));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/reviews/{consumer_id}/create",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="用户评论接口",
     *      notes="该接口会检查用户是否登陆，未登录不能使用，需要删除",
     *      type="void",
     *      nickname="add_review",
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
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="a_data",
     *          description="其他数据请使用json进行上传(此处不需要AES加密)",
     *          required=true,
     *          type="Review",
     *          paramType="body",
     *          allowMultiple=false
     *      ),
     *      @SWG\ResponseMessage(code=201, message="用户添加评论成功"),
     *      @SWG\ResponseMessage(code=401, message="用户尚未登陆，或无权限调用该接口"),
     *      @SWG\ResponseMessage(code=403, message="用户已登陆，但无权限进行评论(未购买该产品或已经评论)"),
     *      @SWG\ResponseMessage(code=422, message="数据验证未通过")
     *  )
     * )
     */
    public function store($consumer_id)
    {
        // 检查是否登陆
        $this->isLogin($consumer_id, LoginService::USER_TYPE_CONSUMER);
    
        // 获取body数据
        $inputs = json_decode(bodys(), true);
        $inputs = array_add($inputs, 'consumer_id', $consumer_id);
        if (trim($inputs['comment_txt']) == '') {
            $inputs = array_remove_keys($inputs, ['comment_txt']);
        }
        
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
                'is_anonymous' => 'required|integer|in:0,1',
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
        
        // 处理上传的type为整形
        foreach ($inputs['review_tags'] as $key => $tag) {
            $inputs['review_tags'][$key]['type'] = (int) $tag['type'];
        }
    
        // 检查用户是否有权限评论改产品(备注：只有自己购买的产品，并且未评论过的产品，才能进行评价)
        if (! $this->reviewSer->checkReviewAuth($consumer_id, $inputs['order_id'], $inputs['order_product_id'])) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '该订单不能进行评论'
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
}