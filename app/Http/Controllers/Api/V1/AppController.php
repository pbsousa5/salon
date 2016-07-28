<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Salon\Services\QiniuTokenService;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use Qiniu\json_decode;
use App\Salon\Repositories\BannerRepository;
use App\Salon\Services\AppService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Qiniu\base64_urlSafeEncode;
use Validator;
use App\Salon\Repositories\JoinApplyRepository;
use Cache;
use App\Salon\Repositories\FeedbackRepository;

/**
 * 
 * 
 * @desc 系统相关信息
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月27日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/App",
 *  description="系统相关配置信息",
 *  produces="['application/json']"
 * )
 */
class AppController extends ApiBaseController
{
    /**
     * 七牛的token
     * @var QiniuTokenService
     */
    protected $tokenSer;
    
    /**
     * app服务层
     * @var AppService
     */
    protected $appSer;
    
    public function __construct(EncrypterInterface $aes, QiniuTokenService $tokenSer, AppService $ser)
    {
        echo app_path();
        echo public_path();
        parent::__construct($aes);
        $this->tokenSer = $tokenSer;
        $this->appSer = $ser;
        //$this->middleware('req', ['only'=>['banner', 'qiniuToken']]);
    }
    
    public function bootstrap(Request $request)
    {
        $dType = $request->header('Device-Type');
        $dType = strtolower(substr($dType, 0, -3));
        $aType = $request->header('App-Type');
        $vCode = $request->header('Version-Code');
        
        $inputs = compact('dType', 'aType', 'vCode');
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'dType' => 'required|string',
                'aType' => 'required|in:app-consumer,app-supplier',
                'vCode' => 'required_if:dType,android|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        $data = $this->appSer->init($dType, 'v1');
        if (empty($data)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '设备类型不正确',
                    "你的设备类型:$dType"
            ));
        }
        if (Str::equals($aType, 'app-consumer')) {
            $banners = $this->appSer->banners();
            $data['banners'] = $banners ? $banners : [];
        }
        if (Str::equals('android', $dType)) {
            if (Str::equals('app-consumer', $aType)) {
                $device_id = 1;
            } else {
                $device_id = 2;
            }
            $data['version'] = $this->appSer->checkUpgrade($device_id, $vCode);
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取初始数据成功', $data));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/upgrade",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="检查更新，在初始化接口中会自动进行",
     *      notes="请在请求头中填写自己应用类型，目前仅支持android",
     *      type="VersionApp",
     *      nickname="app_upgrade",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="Device-Type",
     *          description="设备类型",
     *          required=true,
     *          type="string",
     *          paramType="header",
     *          allowMultiple=false,
     *          enum="['Android4.0']"
     *      ),
     *      @SWG\Parameter(
     *          name="App-Type",
     *          description="应用类型，用户端与门店端，如果是门店端，将没有banners字段",
     *          required=true,
     *          type="string",
     *          paramType="header",
     *          allowMultiple=false,
     *          enum="['app-consumer','app-supplier']"
     *      ),
     *      @SWG\Parameter(
     *          name="Version-Code",
     *          description="应用的当前版本，即：version_id字段的值",
     *          required=true,
     *          type="integer",
     *          paramType="header",
     *          allowMultiple=false,
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取到最新版本成功"),
     *      @SWG\ResponseMessage(code=201, message="当前已是最新版本"),
     *      @SWG\ResponseMessage(code=415, message="可能请求的设备类型不正确"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
    public function appUpgrade(Request $request)
    {
        $deviceType = $request->header('Device-Type');
        $deviceType = strtolower(substr($deviceType, 0, -3));
        $appType = $request->header('App-Type');
        $versionCode = $request->header('Version-Code');
        
        $inputs = compact('deviceType', 'appType', 'versionCode');
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'deviceType' => 'required|string',
                'appType' => 'required|in:app-consumer,app-supplier',
                'versionCode' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        if (Str::equals('android', $deviceType)) {// 如果是android
            if (Str::equals('app-consumer', $appType)) {
                $device_id = 1;
            } else {
                $device_id = 2;
            }
            
            $versionApp = $this->appSer->checkUpgrade($device_id, $versionCode);
            if (is_null($versionApp)) {
                exit($this->appResp->buildReplyMsg(
                        ResponseInterface::HTTP_CREATED,
                        '当前是最新版本'
                ));
            } else {
                exit($this->appResp->buildReplyMsg(
                        ResponseInterface::HTTP_OK,
                        '检测到新版本',
                        $versionApp
                ));
            }
        } else {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '设备类型不正确',
                    "你的设备类型:$deviceType"
            ));
        }
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/app/banner",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取app的横幅广告",
     *      notes="无",
     *      type="Banner",
     *      nickname="banner",
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
     *      @SWG\ResponseMessage(code=200, message="获取幻灯片成功"),
     *      @SWG\ResponseMessage(code=404, message="暂无幻灯片"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function banner()
    {
        $banners = $this->appSer->banners();
        if (empty($banners)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    "尚未添加幻灯片"
            ));
        }
        
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_OK,
                "获取成功",
                $banners
        ));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/app/banner/{id}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定id横幅广告的内容",
     *      notes="无",
     *      type="void",
     *      nickname="banner_info",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="id",
     *          description="图片的id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      )
     *  )
     * )
     */
    public function getBannerInfo($id)
    {
        $banner = $this->appSer->getBannerInfo($id);
        if (empty($banner)) {// 查看的资源不存在
            throw ModelNotFoundException;
        }
        
        $banner = $banner->toArray();
        
        return view('apps.banner', $banner);
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/app/qiniu_token/{type}",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取七牛的token",
     *      notes="目前仅支持上传类型token生成",
     *      type="void",
     *      nickname="qiniu_token",
     *      @SWG\Consumes("multipart/form-data"),
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
     *          name="type",
     *          description="默认返回uploadToken,如果是手机端请求，类型请选择：js_upload",
     *          required=false,
     *          type="string",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue="upload",
     *          enum="['upload','js_upload','download','access']"
     *      ),
     *      @SWG\Parameter(
     *          name="filename",
     *          description="上传文件的名称,不传文件名，用于应对上传多长图片",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="haha.jpg"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取成功"),
     *      @SWG\ResponseMessage(code=415, message="不支持生成该类型token"),
     *      @SWG\ResponseMessage(code=500, message="服务器异常")
     *  )
     * )
     */
    public function qiniuToken(Request $request, $type='upload')
    {

        if ($type == 'js_upload') {
            header('Access-Control-Allow-Origin:*');
            $uploadToken = $this->tokenSer->createUploadToken();
            echo json_encode(['uptoken'=>$uploadToken]);exit;
        }
        
        $filename = trim($request->input('filename'));
        switch ($type) {
            case 'upload':
                $uploadToken = $this->tokenSer->createUploadToken($filename);
                exit($this->appResp->buildReplyMsg(
                        ResponseInterface::HTTP_OK,
                        '获取token成功',
                        compact('uploadToken')
                ));
                break;
            case 'download':
                // no break;
            case 'access':
                // no break;
            default:
                exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '该token申请暂不支持'
                ));
                break;
        }
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/app/qiniu_callback",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="由七牛负责回调，客户端在上传时，须告知七牛该url",
     *      notes="只能在公网中进行访问",
     *      type="void",
     *      nickname="call_back",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\ResponseMessage(code=400, message="Invalid ID supplied"),
     *      @SWG\ResponseMessage(code=404, message="Pet not found"),
     *      @SWG\ResponseMessage(code=405, message="Validation exception")
     *  )
     * )
     */
    public function qiniuCallback(Request $request)
    {
        $authstr = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($authstr, "QBox ")!=0) {
            return false;
        }
        $auth = explode(":", substr($authstr, 5));
        if (sizeof($auth)!=2 || $auth[0]!=config('qiniu.access_key')) {
            return false;
        }
        
        $data = $request->url()."\n".bodys();
        
        return base64_urlSafeEncode(hash_hmac('sha1', $data, config('qiniu.secret_key'), true)) == $auth[1];
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/{mobile}/sms",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取验证码",
     *      notes="每天只能获取10次",
     *      type="void",
     *      nickname="sms",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="mobile",
     *          description="6位纯数字验证码",
     *          required=true,
     *          type="string",
     *          paramType="path",
     *          allowMultiple=false,
     *          defaultValue="15882049545"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取成功"),
     *      @SWG\ResponseMessage(code=429, message="该用户今天已不可获取验证码"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
    public function sendSmsCode($mobile)
    {
        $flag = $this->appSer->sendSmsCode($mobile);
    
        if ($flag) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_OK,
                    '验证码已发送'
            ));
        } else {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_TOO_MANY_REQUESTS,
                    '每日只能获取10次'
            ));
        }
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/join_apply",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="门店申请加入，或者理发师",
     *      notes="无",
     *      type="void",
     *      nickname="join_apply",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="m_body",
     *          description="将手机，名称，申请人组织层json数据，向服务器传输",
     *          required=true,
     *          type="string",
     *          paramType="body"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="创建成功"),
     *      @SWG\ResponseMessage(code=415, message="可能求情的数据错误"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
    public function storeJoinApply(JoinApplyRepository $joinApply)
    {
        $inputs = json_decode(bodys(), true);
        
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'mobile' => 'required|mobile',
                'store_name' => 'required',
                'legal_name' => 'required',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        // 检查是否已经提交申请
        $model = $joinApply->show(['mobile'=>$inputs['mobile']]);
        if (!is_null($model)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '已提交过申请，不能再次提交'
            ));
        }
        
        if ($joinApply->store($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_CREATED,
                    '创建成功'
            ));
        }
        
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                '服务器异常'
        ));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/{user_id}/push_info",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="提交用户推送相关的信息",
     *      notes="无",
     *      type="void",
     *      nickname="push_info",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户类型",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          defaultValue="consumer",
     *          enum="['consumer','supplier','barber']"
     *      ),
     *      @SWG\Parameter(
     *          name="channel_id",
     *          description="与一台设备唯一对应，必须为端上初始化channel成功之后返回的channel_id",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false
     *      ),
     *      @SWG\ResponseMessage(code=201, message="更新成功"),
     *      @SWG\ResponseMessage(code=415, message="可能求情的数据错误"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
    public function updatePushInfo(Request $request, $user_id)
    {
        $inputs = $request->only('channel_id', 'user_type');
        $inputs['user_id'] = $user_id;
        $inputs['channel_id'] = trim($inputs['channel_id']);
        $validator = Validator::make($inputs, [
                'channel_id' => 'required|string',
                'user_type' => 'required|in:consumer,supplier,barber',
                'user_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        $key = $inputs['user_type'] . $user_id;
        // 检查缓存信息是否存在
        if (Cache::has($key)) {
            $userCache = Cache::get($key);
            $userCache['channel_id'] = $inputs['channel_id'];
            $userCache['source'] = request_source();
            Cache::put($key, $userCache, config('appinit.expire'));
            
            // 获取channel_id成功
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, 'channel_id更新成功'));
        }
        
        // 未登录
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_UNAUTHORIZED,
                '用户未登录'
        ));
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/app/add_feedback",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取反馈意见的页面",
     *      notes="请上传用户的mobile，如果用户登录",
     *      type="void",
     *      nickname="show_feedback",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="mobile",
     *          description="手机号码",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="提交意见的用户身份",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="other",
     *          enum="['supplier','consumer', 'barber', 'other']"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取页面成功")
     *  )
     * )
     */
    public function showFeedback(Request $request)
    {
        $inputs = $request->only('mobile', 'user_type');
        
        return view('apps.feedback')->withInputs($inputs);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/add_feedback",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="提交反馈意见",
     *      notes="无",
     *      type="void",
     *      nickname="add_feedback",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="user_type",
     *          description="用户类型",
     *          required=true,
     *          type="string",
     *          paramType="form",
     *          defaultValue="consumer",
     *          enum="['consumer','supplier','barber','other']"
     *      ),
     *      @SWG\Parameter(
     *          name="mobile",
     *          description="手机号码",
     *          required=false,
     *          type="integer",
     *          paramType="form",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="feedback_txt",
     *          description="反馈意见",
     *          required=false,
     *          type="integer",
     *          paramType="form",
     *          allowMultiple=false
     *      ),
     *      @SWG\ResponseMessage(code=201, message="创建成功"),
     *      @SWG\ResponseMessage(code=415, message="可能求情的数据错误"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
    public function storeFeedback(Request $request, FeedbackRepository $feedback)
    {
        $inputs = $request->all();
        $inputs['source'] = request_source();
        
        header('Access-Control-Allow-Origin:*');
        if (empty($inputs)) {
            echo 'error';
        } else {
			$flag = $feedback->store($inputs);
			if (is_null($flag)) {
			    echo 'error';
			} else {
                echo 'success';
			}
        }
		exit;
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/about_us",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取关于我们页面",
     *      notes="无",
     *      type="void",
     *      nickname="about_us",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\ResponseMessage(code=200, message="获取成功"),
     *      @SWG\ResponseMessage(code=404, message="访问的页面不存在")
     *  )
     * )
     */
    public function aboutUs()
    {
        return view('apps.about_us');
    }
}