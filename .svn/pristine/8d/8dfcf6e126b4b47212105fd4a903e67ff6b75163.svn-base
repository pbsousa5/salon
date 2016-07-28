<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Salon\Services\ConsumerService;
use Illuminate\Http\Request;
use Validator;
use App\Libary\Util\String;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use Illuminate\Support\Str;
use Cache;
use App\Salon\Services\AppService;
use App\Salon\Services\LoginService;
use App\Events\ConsumerRegEvent;

/**
 * 
 * 
 * @desc 消费者模块
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月27日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Consumer",
 *  description="消费者用户相关操作",
 *  produces="['application/json']"
 * )
 */
class ConsumerController extends ApiBaseController
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
        parent::__construct($aes);
        $this->consumerSer = $consumerSer;
        $this->loginSer = $loginSer;
        //$this->middleware('req');
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers/{account}/exist",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="检查用户是否已经存在",
     *      notes="无",
     *      type="void",
     *      nickname="exist",
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
     *          name="account",
     *          description="注册账号(默认为手机号码)",
     *          required=true,
     *          type="string",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=200, message="请求成功"),
     *      @SWG\ResponseMessage(code=415, message="请求数据格式不正确"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生错误")
     *  )
     * )
     */
    public function checkExistUser(Request $request, $account)
    {
        $validator = Validator::make(
                compact('account'),
                ['account' => 'mobile']
        );
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '手机号码不合法',
                    $validator->messages()
            ));
        }
        
        $flag = $this->consumerSer->checkAccount($account);
        if ($flag) {
            $exist = true;# 用户存在
        } else {
            $exist = false;# 用户尚不存在
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '请求成功', compact('exist')));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="消费者注册",
     *      notes="先safebase64编码，然后用aes加密",
     *      type="Consumer",
     *      nickname="consumer",
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
     *          name="data",
     *          description="aes(mobile=xxx&password=123, app_key)",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="p/tc05ia8h8HImiQYNoLxnTCgjhzFVOATMaaQpYhphAvOYcg40YJnRGRamRYZNPe"
     *      ),
     *      @SWG\Parameter(
     *          name="sms_code",
     *          description="短信验证码，开发阶段可用141107",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="141107"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="添加新用户成功"),
     *      @SWG\ResponseMessage(code=415, message="请求数据格式不正确"),
     *      @SWG\ResponseMessage(code=422, message="添加的用户已经存在"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
    public function register(Request $request, AppService $appSer)
    {
        $data = $this->decodeAppData(bodys('json', 'data'));
        if (empty($data)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        
        $validator = Validator::make(
                $data,
                ['mobile' => 'mobile', 'password' => 'required|string']
        );
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 验证验证码
        $smsCode = $request->input('sms_code', config('appinit.debug_code', '141107'));
        if (!Str::equals($smsCode, config('appinit.debug_code', '141107'))) {
            $flag = $appSer->validateCode($data['mobile'], $smsCode);
            if (!$flag) {
                exit($this->appResp->buildReplyMsg(
                        ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                        '验证码不正确'
                ));
            }
        }
        
        $consumer = $this->consumerSer->addConsumer($data);
        if (is_null($consumer)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '用户已存在或其他未知情况'
            ));
        }
        
        // 注册，赠送优惠券
        event(new ConsumerRegEvent($consumer));
        
        // 调用登陆
        $consumer = $this->loginSer->loginApp($data, LoginService::USER_TYPE_CONSUMER);
        $consumer->token = $this->encodeAppData($consumer->token);
        $consumer = $this->consumerSer->handleData($consumer);
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '注册成功啦', $consumer));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers/login",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="用户登陆",
     *      notes="请将登陆名称与密码进行AES加密",
     *      type="Consumer",
     *      nickname="login",
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
     *          name="data",
     *          description="加密、编码后的数据eg:{'data':'mobile=xxx&password=123'}",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="p/tc05ia8h8HImiQYNoLxj7Us7INUI7fqxowugPko2Tw98w+HtRE3m1UZY88/nui"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="登陆成功"),
     *      @SWG\ResponseMessage(code=404, message="登陆的用户不存在"),
     *      @SWG\ResponseMessage(code=422, message="登陆用户密码错误"),
     *      @SWG\ResponseMessage(code=500, message="服务端未知错误")
     *  )
     * )
     */
    public function login()
    {
        $data = $this->decodeAppData(bodys('json', 'data'));
        if (empty($data)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        $validator = Validator::make(
                $data,
                ['mobile' => 'mobile', 'password' => 'required']
        );
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        $consumer = $this->loginSer->loginApp($data, LoginService::USER_TYPE_CONSUMER);
        if (is_null($consumer)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '用户不存在或密码错误'
            ));
        }
        
        $consumer->token = $this->encodeAppData($consumer->token);
        $consumer = $this->consumerSer->handleData($consumer);
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '登陆成功', $consumer));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers/{id}",
     *  @SWG\Operation(
     *      method="DELETE",
     *      summary="退出登陆",
     *      notes="无",
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
     *          name="_method",
     *          description="方法欺骗，为了解决部分不支持delete请求的问题而设计",
     *          required=true,
     *          type="string",
     *          paramType="form",
     *          allowMultiple=false,
     *          defaultValue="DELETE"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="退出登陆的用户id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=204, message="退出登陆成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到该用户登陆信息"),
     *      @SWG\ResponseMessage(code=500, message="服务器故障")
     *  )
     * )
     */
    public function logout($id)
    {
        $user_id = (string) $id;
        $flag = $this->loginSer->logoutApp($user_id, LoginService::USER_TYPE_CONSUMER);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NO_CONTENT, '退出登陆成功'));
        }
        
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_NOT_FOUND,
                '未找到该用户登陆信息或其他问题'
        ));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers/{id}/edit",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="更新用户信息",
     *      notes="此时加密的数据请使用token",
     *      type="Consumer",
     *      nickname="update",
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
     *          description="需要修改数据的用户id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="m_data",
     *          description="aes加密需要修改的数据:{'data':aes('head=xxx&age_tag=123', token)}",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="kBdQtJXqnV+i43eJpfYxTmssSXjQI6H3lp9QftJsrIc="
     *      ),
     *      @SWG\ResponseMessage(code=201, message="更新成功"),
     *      @SWG\ResponseMessage(code=401, message="用户登陆过期或其他权限原因"),
     *      @SWG\ResponseMessage(code=500, message="服务器错误")
     *  )
     * )
     */
    public function modify($id)
    {
        $this->isLogin($id, LoginService::USER_TYPE_CONSUMER);
        // 检查是否登陆
        $cacheValue = $this->loginSer->getUserCache($id, LoginService::USER_TYPE_CONSUMER);
        
        $inputs = $this->decodeAppData(bodys('json', 'data'), $cacheValue['token']);
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                        'head_img' => 'string',
                        'gender' => 'numeric',
                        'nickname' => 'string',
                        'age_tag' => 'string',
        ]);
        if (array_key_exists('my_coupon', $inputs)) {
            unset($inputs['my_coupon']);
        }
        if (array_key_exists('level_score', $inputs)) {
            unset($inputs['level_score']);
        }
        if (array_key_exists('my_bean', $inputs)) {
            unset($inputs['my_bean']);
        }
        if (array_key_exists('weight', $inputs)) {
            unset($inputs['weight']);
        }
        if (array_key_exists('account', $inputs)) {
            unset($inputs['account']);
        }
        if (array_key_exists('mobile', $inputs)) {
            unset($inputs['mobile']);
        }
        if (array_key_exists('password', $inputs)) {
            unset($inputs['password']);
        }
        
        $consumer = $this->consumerSer->modifyConsumer(['id'=>$id], $inputs);
        if (is_null($consumer)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    '服务端DB发生异常'
            ));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '更新成功'));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers/{id}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="刷新用户信息",
     *      notes="必须登陆后才可调用该方法",
     *      type="Consumer",
     *      nickname="flush_user",
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
     *          description="刷新的用户id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取用户信息成功"),
     *      @SWG\ResponseMessage(code=401, message="用户未登录，无权调用该接口"),
     *      @SWG\ResponseMessage(code=404, message="用户未发现"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生故障")
     *  )
     * )
     */
    public function show($id)
    {
        // 检查是否登陆
        $this->isLogin($id, LoginService::USER_TYPE_CONSUMER);
        
        $consumer = $this->consumerSer->getSingleInfo(compact('id'));
        if (empty($consumer)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '未找到该用户信息'
            ));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '刷新成功', $consumer));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/consumers/passwd",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="重置密码(忘记密码、修改密码均使用该接口)",
     *      notes="验证码，请调用获取短信接口",
     *      type="Consumer",
     *      nickname="reset_passwd",
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
     *          description="aes(mobile=123&password=123&sms_code=141107, app_key)",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="p/tc05ia8h8HImiQYNoLxnTCgjhzFVOATMaaQpYhphA9CUFzRW8HbyvvQNkuw9Ejlmr2OEbeOYjbVMiXnfLZbXfTiqFMWbwGdsYBYgiL0Wk="
     *      ),
     *      @SWG\ResponseMessage(code=201, message="更新成功"),
     *      @SWG\ResponseMessage(code=401, message="未登录或用户无权限修改"),
     *      @SWG\ResponseMessage(code=422, message="验证码错误"),
     *      @SWG\ResponseMessage(code=500, message="服务器异常"),
     *  )
     * )
     */
    public function resetPassWord(AppService $appSer)
    {
        $inputs = $this->decodeAppData(bodys('json', 'data'));
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'mobile' => 'required|mobile',
                'password' => 'required|string',
                'sms_code' => 'required|digits:6',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 验证验证码
        if (
                !$appSer->validateCode($inputs['mobile'], $inputs['sms_code'])&&
                !Str::equals(config('appinit.debug_code', '141107'), $inputs['sms_code'])
        ) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNAUTHORIZED,
                    '验证码错误'
            ));
        }
        
        // 修改
        $consumer = $this->consumerSer->modifyConsumer(['mobile'=>$inputs['mobile']], ['password'=>$inputs['password']]);
        if (is_null($consumer)) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '该用户不存在'));
        }
        
        // 调用登陆
        $credentials = [
                'mobile' => $inputs['mobile'],
                'password' => $inputs['password'],
        ];
        $consumer = $this->loginSer->loginApp($credentials, LoginService::USER_TYPE_CONSUMER);
        $consumer->token = $this->encodeAppData($consumer->token);
        $consumer = $this->consumerSer->handleData($consumer);
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '登陆成功', $consumer));
    }
}