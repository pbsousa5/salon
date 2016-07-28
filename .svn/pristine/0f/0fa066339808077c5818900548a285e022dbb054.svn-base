<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\FollowerController as FollowerCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use Illuminate\Http\Request;
use Validator;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Salon\Services\V2\FollowService;
use App\Salon\Services\V2\ConsumerService;
use App\Salon\Services\V2\SupplierService;
use App\Salon\Services\V2\LoginService;
use App\Salon\Services\V2\BarberService;

/**
 * 
 * 
 * @desc 请描述这个类的功能
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月23日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Follower",
 *  description="用户关注、删除、获取关注列表和理发师、门店获取粉丝数的API",
 *  produces="['application/json']"
 * )
 */
class FollowerController extends FollowerCon
{
    /**
     * 关注相关服务
     * @var FollowService
     */
    protected $followerSer;
    
    /**
     * 消费者服务
     * @var ConsumerService
     */
    protected $consumerSer;
    
    /**
     * 门店服务层
     * @var SupplierService
     */
    protected $supplierSer;
    
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    /**
     * The BarberService instance.
     * @var BarberService
     */
    protected $barberSer;
    
    public function __construct(
            EncrypterInterface $aes,
            FollowService $followerSer,
            ConsumerService $consumerSer,
            SupplierService $supplierSer,
            LoginService $loginSer,
            BarberService $barberSer
    ) {
        parent::__construct($aes, $followerSer, $consumerSer, $supplierSer, $loginSer, $barberSer);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/followers/{user_type}/fans/{user_id}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取 门店/理发师粉丝数",
     *      notes="登陆后才能调用该接口",
     *      type="void",
     *      nickname="watcher",
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
     *          description="门店或者理发师id",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户类型，supplier:门店 barber:理发师",
     *          required=true,
     *          type="string",
     *          paramType="path",
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
     *      @SWG\ResponseMessage(code=200, message="获取列表成功"),
     *      @SWG\ResponseMessage(code=401, message="未登录，无权进行操作"),
     *      @SWG\ResponseMessage(code=404, message="该用户尚未关注店铺"),
     *      @SWG\ResponseMessage(code=422, message="数据验证错误"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function getFans(Request $request, $user_type, $user_id)
    {
        // 获取参数，并验证
        $inputs = $request->only('page', 'per_page');
        $inputs['user_type'] = $user_type;
        $inputs['user_id'] = $user_id;
        
        // 检查是否登陆
        if ($user_type == 'supplier') {
            $this->isLogin($user_id, LoginService::USER_TYPE_SUPPLIER);
        } elseif ($user_type == 'barber') {
            $this->isLogin($user_id, LoginService::USER_TYPE_BARBER);
        } else {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误'
            ));
        }
        
        // 检查数据是否合法
        $validator = Validator::make($inputs, [
                'user_id'=>'required|integer',
                'page' => 'integer',
                'user_type' => 'required|in:supplier,barber',
                'per_page' => 'integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
    
        $fans = $this->followerSer->getFans($user_id, $user_type, $inputs['per_page']);
        if (is_null($fans) || $fans->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '您目前还没有粉丝'));
        }
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $fans));
        
        
    }
}