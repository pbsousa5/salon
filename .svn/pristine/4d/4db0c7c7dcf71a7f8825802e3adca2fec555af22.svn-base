<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\NotifyService;
use Validator;
use App\Libary\Contracts\Http\ResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\UserReadNotifyEvent;

/**
 * 
 * 
 * @desc 用户消息接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月3日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Notify",
 *  description="通知消息",
 *  produces="['application/json']"
 * )
 */
class NotifyController extends ApiBaseController
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
        parent::__construct($aes);
        $this->notifySer = $notifySer;
        //$this->middleware('req');
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/notifys/{user_id}/list",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取消息列表",
     *      notes="建议缓存该列表，当用户阅读消息时，直接读取本地内容",
     *      type="Notify",
     *      nickname="msg_list",
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
     *          description="用户的id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户的类型,consumer:用户,supplier:门店,barber:理发师",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple="consumer",
     *          enum="['consumer','supplier','barber']"
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
     *      @SWG\ResponseMessage(code=200, message="获取数据成功"),
     *      @SWG\ResponseMessage(code=401, message="用户登陆过期或其他权限原因"),
     *      @SWG\ResponseMessage(code=404, message="未找到内容"),
     *      @SWG\ResponseMessage(code=422, message="请求数据验证失败，格式不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function index(Request $request, $user_id)
    {
        // 获取参数，并验证
        $inputs = $request->only('page', 'per_page', 'user_type');
        $inputs['user_id'] = $user_id;
        $validator = Validator::make($inputs, [
                'page' => 'integer',
                'per_page' => 'integer',
                'user_id' => 'required|integer',
                'user_type' => 'required|in:consumer,supplier,barber',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($user_id, strtolower($inputs['user_type']));
        
        $list = $this->notifySer->listNotify($inputs['user_id'], $inputs['user_type'], $inputs['per_page']);
        $data = $list->toArray()['data'];
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '访问的数据不存在'));
        }
        
        foreach ($data as $key=>$val) {
            $data[$key]['created_at'] = date('Y-m-d', strtotime($val['created_at']));
            unset($data[$key]['updated_at']);
        }
        
        // 触发消息已读事件
        event(new UserReadNotifyEvent($user_id, $inputs['user_type']));
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '请求数据成功', $data));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/notifys/{id}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定消息的详细信息",
     *      notes="所有详细的信息均会在list中返回，建议直接缓存，本地读取",
     *      type="Notify",
     *      nickname="msg_info",
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
     *          description="指定消息资源的id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="用户的id",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户的类型,consumer:用户,supplier:门店,barber:理发师",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple="consumer",
     *          enum="['consumer','supplier','barber']"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取数据成功"),
     *      @SWG\ResponseMessage(code=401, message="用户登陆过期或其他权限原因"),
     *      @SWG\ResponseMessage(code=404, message="未找到该内容"),
     *      @SWG\ResponseMessage(code=422, message="请求数据验证失败，格式不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function show(Request $request, $id)
    {
        $inputs = $request->only(['user_id', 'user_type']);
        $inputs['id'] = $id;
        $validator = Validator::make($inputs, [
                'id'=>'required|integer',
                'user_id' => 'required|integer',
                'user_type' => 'required|in:consumer,supplier,barber',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否登陆
        $this->isLogin($inputs['user_id'], strtolower($inputs['user_type']));
        
        $notify = $this->notifySer->show($id, ['user_id'=>$inputs['user_id'], 'user_type'=>$inputs['user_type']]);
        if (is_null($notify)) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '阅读的数据不存在'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $notify));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/notifys/{id}",
     *  @SWG\Operation(
     *      method="DELETE",
     *      summary="删除某条消息",
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
     *          name="id",
     *          description="删除的消息id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="用户的id",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户的类型,consumer:用户,supplier:门店, barber:理发师",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple="consumer",
     *          enum="['consumer','supplier','barber']"
     *      ),
     *      @SWG\ResponseMessage(code=204, message="删除信息成功"),
     *      @SWG\ResponseMessage(code=404, message="删除的数据不存在"),
     *      @SWG\ResponseMessage(code=422, message="请求数据验证失败，格式不合法"),
     *      @SWG\ResponseMessage(code=405, message="Validation exception")
     *  )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $inputs = $request->only(['user_id', 'user_type']);
        $inputs['id'] = $id;
        $validator = Validator::make($inputs, [
                'id'=>'required|integer',
                'user_id' => 'required|integer',
                'user_type' => 'required|in:consumer,supplier,barber',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }

        // 检查是否登陆
        $this->isLogin($inputs['user_id'], strtolower($inputs['user_type']));
        
        $flag = $this->notifySer->delNotify($id, ['user_id'=>$inputs['user_id'], 'user_type'=>$inputs['user_type']]);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NO_CONTENT, '删除数据成功'));
        } else {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '删除的数据不存在'));
        }
    }
}