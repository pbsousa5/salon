<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\AppController as AppCon;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\QiniuTokenService;
use App\Salon\Services\V2\AppService;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;

/**
 * 
 * 
 * @desc 系统相关信息，重写初始化接口，检查ios是否能够升级
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月27日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.1.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/App",
 *  description="系统相关配置信息，重写初始化接口，检查ios是否能够升级",
 *  produces="['application/json']"
 * )
 */
class AppController extends AppCon
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
    
    public function __construct(
            EncrypterInterface $aes,
            QiniuTokenService $qiniuSer,
            AppService $appSer
    ){
        parent::__construct($aes, $qiniuSer, $appSer);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/app/init_info",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取应用初始化配置信息（1.1）",
     *      notes="请在请求头中填写自己应用类型",
     *      type="void",
     *      nickname="init_info",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="Device-Type",
     *          description="设备类型",
     *          required=true,
     *          type="string",
     *          paramType="header",
     *          allowMultiple=false,
     *          enum="['Android4.0','Ios7.0']"
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
     *          description="应用的当前版本，即：version_id字段的值，如果设备类型为android，则该字段必须",
     *          required=false,
     *          type="integer",
     *          paramType="header",
     *          allowMultiple=false,
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取初始数据成功"),
     *      @SWG\ResponseMessage(code=422, message="数据校验不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务端发生了故障")
     *  )
     * )
     */
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
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误',
                    $validator->messages()
            ));
        }

        $data = $this->appSer->init($dType, 'v2');
        if (empty($data)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '设备类型不正确',
                    "你的设备类型:$dType"
            ));
        }
        if (Str::equals($aType, 'app-consumer')) {
            $banners = $this->appSer->banners();
            $data['banners'] = $banners ? $banners : [];
        }
        
        if (Str::equals('app-consumer', $aType)) {
            if (Str::equals('android', $dType)) {
                $device_id = 1;
            } else {
                $device_id = 3;
            }
        } else {
            if (Str::equals('android', $dType)) {
                $device_id = 2;
            } else {
                $device_id = 4;
            }
        }
        $data['version'] = $this->appSer->checkUpgrade($device_id, $vCode);
    
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取初始数据成功', $data));
    }
}