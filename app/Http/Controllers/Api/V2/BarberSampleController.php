<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use Illuminate\Http\Request;
use App\Libary\Contracts\Http\ResponseInterface;
use Validator;
use App\Salon\Services\V2\BarberService;
use App\Salon\Services\V2\LoginService;

/**
 * 
 * 
 * @desc 理发师作品集
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月19日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.1.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/BarberSample",
 *  description="理发师作品集",
 *  produces="['application/json']"
 * )
 */
class BarberSampleController extends ApiBaseController
{
    
    /**
     * The BarberService instance.
     * @var BarberService
     */
    protected $barberSer;
    
    
    public function __construct(EncrypterInterface $aes, BarberService $barberSer)
    {
        parent::__construct($aes);
        $this->barberSer = $barberSer;
        
        //$this->middleware('req');
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/samples/{barber_id}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定理发师的作品集",
     *      notes="",
     *      type="BarberSample",
     *      nickname="list_sample",
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
     *          name="barber_id",
     *          description="要获取相册的理发师id",
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
     *      @SWG\ResponseMessage(code=200, message="获取数据成功"),
     *      @SWG\ResponseMessage(code=404, message="查找的数据不存在"),
     *      @SWG\ResponseMessage(code=422, message="数据校验不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function index(Request $request, $barber_id)
    {
        $inputs = $request->only('page', 'per_page');
        $inputs['barber_id'] = $barber_id;
        $validator = Validator::make($inputs, [
                'barber_id' => 'required|integer',
                'page' => 'integer',
                'per_page' => 'integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        $list = $this->barberSer->listSample($barber_id, $inputs['per_page']);
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '查找的数据不存在'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $list));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/samples/{barber_id}/del/{sample_ids}",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="删除理发师指定的作品集或作品集中的某张照片",
     *      notes="需要登录才可操作",
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
     *          name="barber_id",
     *          description="理发师id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="sample_ids",
     *          description="理发师作品id，如果是多个，请用逗号分隔",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="img_name",
     *          description="要删除的相册图片名字，如果不传或未空，表示删除相册，如果上传，表示删除指定名字的图片，多张图用逗号分隔(,)",
     *          required=false,
     *          type="integer",
     *          paramType="form",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=204, message="退出登陆成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到该用户登陆信息"),
     *      @SWG\ResponseMessage(code=422, message="数据校验不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务器故障")
     *  )
     * )
     */
    public function destory(Request $request, $barber_id, $sample_ids)
    {
        // 检查是否登陆
        $this->isLogin($barber_id, LoginService::USER_TYPE_BARBER);
        $inputs['barber_id'] = $barber_id;
        $inputs['sample_ids'] = $sample_ids;
        $inputs['img_name'] = $request->input('img_name', '');
        $validator = Validator::make($inputs, [
                'barber_id' => 'required|integer',
                'sample_ids' => 'required|string',
                'img_name' => 'string',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        // 执行删除操作
        $flag = $this->barberSer->destroySample($sample_ids, $barber_id, $inputs['img_name']);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NO_CONTENT, '删除成功'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '删除的数据不存在'));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/samples/{barber_id}/add_sample",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="上传指定理发师作品集",
     *      notes="现版本支持多图上传",
     *      type="void",
     *      nickname="add_sample",
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
     *          name="barber_id",
     *          description="上传作品的理发师id",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="opus_img",
     *          description="图片路径，多长图请用逗号(,)进行分割",
     *          required=true,
     *          type="integer",
     *          paramType="form"
     *      ),
     *      @SWG\Parameter(
     *          name="small_title",
     *          description="图片标题",
     *          required=false,
     *          type="integer",
     *          paramType="form"
     *      ),
     *      @SWG\Parameter(
     *          name="describe",
     *          description="图片描述",
     *          required=false,
     *          type="string",
     *          paramType="form"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="创建成功"),
     *      @SWG\ResponseMessage(code=422, message="数据验证未通过"),
     *      @SWG\ResponseMessage(code=401, message="用户未登录，或其他授权问题"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function store(Request $request, $barber_id)
    {
        // 检查是否登陆
        $this->isLogin($barber_id, LoginService::USER_TYPE_BARBER);
        
        $inputs = $request->only('opus_img', 'small_title', 'describe');
        $inputs = array_add($inputs, 'barber_id', $barber_id);
        $validator = Validator::make($inputs, [
                'opus_img' => 'required|string',
                'small_title' => 'required|string',
                'describe' => 'required|string',
                'barber_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        $flag = $this->barberSer->addSample($inputs);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '添加作品成功'));
        } else {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, '服务器失联啦'));
        }
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/samples/{barber_id}/edit/{sample_id}",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="修改指定理发师的指定相册",
     *      notes="上传新图与修改简介均是该接口",
     *      type="void",
     *      nickname="update_sample",
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
     *          name="barber_id",
     *          description="上传作品的理发师id",
     *          required=true,
     *          type="integer",
     *          paramType="path"
     *      ),
     *      @SWG\Parameter(
     *          name="sample_id",
     *          description="理发师作品id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="opus_img",
     *          description="新上传图片路径，多长图请用逗号(,)进行分割，如果不上传新图，则该参数不传递",
     *          required=false,
     *          type="integer",
     *          paramType="form"
     *      ),
     *      @SWG\Parameter(
     *          name="img_type",
     *          description="修改的图片类型，仅在opus_img不为空时生效，inside:内容页(此时上传图片可多张用逗号分隔)，cover:封面(此时如果上传多张只会随机选取一张)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="inside",
     *          enum="['inside','cover']"
     *      ),
     *      @SWG\Parameter(
     *          name="describe",
     *          description="图片描述",
     *          required=false,
     *          type="string",
     *          paramType="form"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="修改成功"),
     *      @SWG\ResponseMessage(code=401, message="用户未登录，或其他授权问题"),
     *      @SWG\ResponseMessage(code=404, message="修改的数据不存在"),
     *      @SWG\ResponseMessage(code=422, message="数据验证未通过"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function modify(Request $request, $barber_id, $sample_id)
    {
        // 检查是否登陆
        $this->isLogin($barber_id, LoginService::USER_TYPE_BARBER);
        
        $inputs = $request->only('opus_img', 'describe', 'img_type');
        $inputs = array_add($inputs, 'barber_id', $barber_id);
        $inputs = array_add($inputs, 'sample_id', $sample_id);
        $validator = Validator::make($inputs, [
                'opus_img' => 'string',
                'sample_id' => 'required|integer',
                'describe' => 'string',
                'barber_id' => 'required|integer',
                'img_type' => 'required_with:opus_img|in:inside,cover',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        $inputs = array_add($inputs, 'img_type', 'inside');
        $img_type = $inputs['img_type'];
        if ($inputs['opus_img']) {
            $inputs['opus_img'] = explode(',', $inputs['opus_img']);
        }
        
        $inputs = array_remove_keys($inputs, ['sample_id', 'barber_id', 'img_type']);
        $flag = $this->barberSer->updateSample($barber_id, $sample_id, $inputs, $img_type);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '修改成功'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '修改的内容不存在'));
    }
}