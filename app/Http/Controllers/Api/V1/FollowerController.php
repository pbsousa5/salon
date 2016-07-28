<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\FollowService;
use App\Salon\Services\ConsumerService;
use Illuminate\Http\Request;
use Validator;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Salon\Services\SupplierService;
use App\Salon\Services\LoginService;
use Illuminate\Support\Str;
use App\Salon\Services\BarberService;
use App\Events\ConsumerFollowerEvent;

/**
 * 
 * 
 * @desc 用户关注、删除、获取关注列表的API
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月1日
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
class FollowerController extends ApiBaseController
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
        parent::__construct($aes);
        $this->followerSer = $followerSer;
        $this->consumerSer = $consumerSer;
        $this->supplierSer = $supplierSer;
        $this->loginSer = $loginSer;
        $this->barberSer = $barberSer;
        //$this->middleware('req');
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/followers",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="关注商家或者理发师",
     *      notes="无",
     *      type="void",
     *      nickname="watch",
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
     *          paramType="form"
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="要被关注的门店或者理发师id",
     *          required=true,
     *          type="integer",
     *          paramType="form"
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
     *      @SWG\ResponseMessage(code=201, message="添加数据成功"),
     *      @SWG\ResponseMessage(code=401, message="未登录，无权进行操作"),
     *      @SWG\ResponseMessage(code=415, message="此次请求不合法，可能是以及关注"),
     *      @SWG\ResponseMessage(code=422, message="数据验证错误"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function store(Request $request)
    {
        $inputs = $request->only(['consumer_id', 'user_id', 'user_type']);
        // 检查数据是否合法
        $validator = Validator::make($inputs, [
                'consumer_id'=>'required|integer',
                'user_id'=>'required|integer',
                'user_type'=>'required|in:supplier,barber',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误'.$validator->messages(),
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['consumer_id'], LoginService::USER_TYPE_CONSUMER);
        
        // 检查关注的对象是否存在
        $exist = true;
        if (Str::equals('supplier', $inputs['user_type'])) {
            $model = $this->supplierSer->getSingleInfo(['id'=>$inputs['user_id']], ['consumer_id'=>$inputs['consumer_id']]);
            if (is_null($model)) {
                $exist = false;
            }
        } elseif (Str::equals('barber', $inputs['user_type'])) {
            $model = $this->barberSer->getSignleInfo(['id'=>$inputs['user_id']], ['consumer_id'=>$inputs['consumer_id']]);
            if (is_null($model)) {
                $exist = false;
            }
        }
        
        if (!$exist) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '关注的对象不存在'
            ));
        }
        
        // 检查是否已经关注
        $flag = $this->followerSer->checkStatus(
                    ['consumer_id'=>$inputs['consumer_id'], 'user_id'=>$inputs['user_id']],
                    strtolower($inputs['user_type'])
        );
        if ($flag) {# 已经关注
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '已经关注了'
            ));
        }
        
        // 进行添加操作
        $status = $this->followerSer->addFollower(
                    ['consumer_id'=>$inputs['consumer_id'], 'user_id'=>$inputs['user_id']],
                    strtolower($inputs['user_type'])
        );
        if ($status) {
            event(new ConsumerFollowerEvent($inputs['user_id'], $inputs['user_type']));
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_CREATED,
                    '关注成功'
            ));
        } else {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    '服务器DB异常，是否成功不可知'
            ));
        }
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/followers/{consumer_id}/delete/{user_id}",
     *  @SWG\Operation(
     *      method="DELETE",
     *      summary="消费者删除指定id的关注者",
     *      notes="注意二者id之间的顺序",
     *      type="void",
     *      nickname="delete",
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
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="要被关注的门店或者理发师id",
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
     *      @SWG\ResponseMessage(code=204, message="取消关注成功"),
     *      @SWG\ResponseMessage(code=401, message="未登录，无权进行操作"),
     *      @SWG\ResponseMessage(code=415, message="此次请求不合法，可能是以及关注"),
     *      @SWG\ResponseMessage(code=422, message="数据验证错误"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function destroy(Request $request, $consumer_id, $user_id)
    {
        $inputs = compact('consumer_id', 'user_id');
        $inputs['user_type'] = $request->input('user_type');
        // 检查数据是否合法
        $validator = Validator::make($inputs, ['consumer_id'=>'integer', 'user_id'=>'integer']);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($consumer_id, LoginService::USER_TYPE_CONSUMER);
        
        // 检查是否已经关注
        $flag = $this->followerSer->checkStatus(
                    ['consumer_id'=>$inputs['consumer_id'], 'user_id'=>$inputs['user_id']],
                    strtolower($inputs['user_type'])
        );
        if (!$flag) {# 未关注
                exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                '尚未关注，不能进行该操作'
            ));
        }
        
        $status = $this->followerSer->delFollower(
                    ['consumer_id'=>$inputs['consumer_id'], 'user_id'=>$inputs['user_id']],
                    strtolower($inputs['user_type'])
        );
        if ($status) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NO_CONTENT,
                    '取消关注成功'
            ));
        } else {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    '服务器DB异常，是否成功不可知'
            ));
        }
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/followers/{id}/watcher",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定用户id关注的门店或者理发师列表数据",
     *      notes="登陆后才能调用该接口",
     *      type="Supplier",
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
     *          name="id",
     *          description="用户id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
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
     *          name="latitude",
     *          description="纬度(-90, 90)，sortby=distance时，必须设置(如果不设置，则采用默认排序)",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="30.663484"
     *      ),
     *      @SWG\Parameter(
     *          name="longitude",
     *          description="经度(-180, 180)，sortby=distance时，必须设置(如果不设置，则采用默认排序)",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="104.072007"
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
    public function listWatcher(Request $request, $id)
    {
        // 获取参数，并验证
        $inputs = $request->only('page', 'per_page', 'user_type', 'latitude', 'longitude');
        $inputs['id'] = $id;
        // 检查数据是否合法
        $validator = Validator::make($inputs, [
                'id'=>'required|integer',
                'page' => 'integer',
                'user_type' => 'required|in:supplier,barber',
                'per_page' => 'integer',
                'latitude' => 'string',
                'longitude' => 'string',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($id, LoginService::USER_TYPE_CONSUMER);
        
        $ids = $this->followerSer->listMaster($id, $inputs['user_type'], $inputs['per_page']);
        if (empty($ids)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '该用户尚未有关注的门店'
            ));
        }

        $where['ids'] = $ids;
        $where['longitude'] = $inputs['longitude'];
        $where['latitude'] = $inputs['latitude'];
        $where['sortby'] = 'lower_price';
        $where['order'] = 'asc';
        $where['consumer_id'] = $id;
        $where['type'] = $inputs['user_type'];
        if (Str::equals('supplier', $inputs['user_type'])) {
            $list = $this->supplierSer->listSupplier($where, -1, $inputs['per_page']);
        } else {
            $list = $this->barberSer->listBarber($where, -1, $inputs['per_page']);
        }
        if (is_null($list) || $list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '未找到相关数据'));
        }
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $list));
    }
}