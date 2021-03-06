<?php
namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\SupplierService;
use App\Http\Controllers\Api\V1\SupplierController as SupplierCon;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Salon\Services\V2\OrderService;
use App\Salon\Services\V2\LoginService;
use Validator;

/**
 * 
 * 
 * @desc 门店的处理逻辑，重写获取列表接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.1.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Supplier",
 *  description="门店相关接口，重写获取列表接口",
 *  produces="['application/json']"
 * )
 */
class SupplierController extends SupplierCon
{
    /**
     * 指定资源
     * @var SupplierService
     */
    protected $supplierSer;
    
    /**
     * 订单服务层
     * @var OrderService
     */
    protected $orderSer;
    
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    
    public function __construct(
            EncrypterInterface $aes,
            SupplierService $supplierSer,
            OrderService $orderSer,
            LoginService $loginSer
    ){
        parent::__construct($aes, $supplierSer, $orderSer, $loginSer);
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/suppliers",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取门店列表数据，默认每次20条(1.1)",
     *      notes="如果请求时，未传递用户经纬度，则距离字段返回0",
     *      type="Supplier",
     *      nickname="s_list",
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
     *          name="sortby",
     *          description="排序字段,blend:综合， avg_score:平均分，lower_price:最低价, distance:距离",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="avg_score",
     *          enum="['blend','avg_score','lower_price','distance']"
     *      ),
     *      @SWG\Parameter(
     *          name="consumer_id",
     *          description="如果用户登录，需要上传用户id",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="m_page",
     *          description="当排序规则是distance时，该字段必须上传，从1开始，此时，page参数必须为1",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="page",
     *          description="默认获取第一页数据",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="per_page",
     *          description="开发阶段，默认2条，建议每次获取15+",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="2"
     *      ),
     *      @SWG\Parameter(
     *          name="longitude",
     *          description="经度，sortby=distance时，必须设置(如果不设置，则采用默认排序)",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="104.072007"
     *      ),
     *      @SWG\Parameter(
     *          name="latitude",
     *          description="纬度，sortby=distance时，必须设置(如果不设置，则采用默认排序)",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="30.663484"
     *      ),
     *      @SWG\Parameter(
     *          name="kilometer",
     *          description="单位km，列表时不传，地图模式时，必须上传",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="3"
     *      ),
     *      @SWG\Parameter(
     *          name="type",
     *          description="请求类型，支持：地图与列表两种模式",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="list",
     *          enum="['map','list']"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="请求数据成功"),
     *      @SWG\ResponseMessage(code=404, message="请求的数据不存在"),
     *      @SWG\ResponseMessage(code=422, message="数据校验不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务器异常")
     *  )
     * )
     */
    public function index(Request $request)
    {
        // 获取参数，并验证
        $inputs = $request->only('page', 'm_page', 'per_page', 'consumer_id', 'sortby', 'longitude', 'latitude', 'kilometer', 'type');
        $validator = Validator::make($inputs, [
                'page' => 'required|integer',
                'per_page' => 'required|integer',
                'consumer_id' => 'integer',
                'sortby' => 'string|required|in:blend,avg_score,lower_price,distance',
                'm_page' => 'required_if:sortby,distance|integer',
                'longitude' => 'required_if:sortby,distance|string',
                'latitude' => 'required_if:sortby,distance|string',
                'kilometer' => 'required_if:sortby,distance|integer',
                'type' => 'required|in:map,list',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }

        $list = $this->supplierSer->listSupplier($inputs, $inputs['page'], $inputs['per_page']);
        
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '尚无门店信息'));
        }
        
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_OK,
                '获取数据成功',
                $list
        ));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/suppliers/{id}/edit",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="更新门店信息、营业时间、是否营业等（V1.1）",
     *      notes="此时加密的数据请使用token",
     *      type="Supplier",
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
     *          description="需要修改数据的门店id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\Parameter(
     *          name="m_data",
     *          description="aes加密需要修改的数据:{'data':'name=xxx&morning_time=07:00&night_time=22:00'},修改门店状态时，status的值0：关店，1：营业中，3：休息中",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue="TwtVP/i/F62Ose7ePjsmW2sX4piRvDlm2YoZU91jf2DyYTgNBwHgVRDfBMUUzLEK"
     *      ),
     *      @SWG\ResponseMessage(code=201, message="更新成功"),
     *      @SWG\ResponseMessage(code=401, message="用户登陆过期或其他权限原因"),
     *      @SWG\ResponseMessage(code=422, message="数据校验不合法"),
     *      @SWG\ResponseMessage(code=415, message="加密数据不能解析"),
     *      @SWG\ResponseMessage(code=500, message="服务器错误")
     *  )
     * )
     */
    public function modify($id)
    {
        // 检查是否登陆
        $this->isLogin($id, LoginService::USER_TYPE_SUPPLIER);
        $cacheValue = $this->loginSer->getUserCache($id, LoginService::USER_TYPE_SUPPLIER);
    
        $inputs = $this->decodeAppData(bodys('json', 'data'), $cacheValue['token']);
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '加密数据不能解析'
            ));
        }
        // 检验数据是否合法
        $validator = Validator::make($inputs, [
                'name' => 'string',
                'staff_count' => 'integer',
                'phones' => 'array',
                'gallerys' => 'array',
                'basic_discount' => 'integer',
                'morning_time' => 'string',
                'noon_time' => 'string',
                'afternoon_time' => 'string',
                'night_time' => 'string',
                'status' => 'integer|in:0,1',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据错误',
                    $validator->messages()
            ));
        }
        
        $inputs = array_remove_keys($inputs, ['account', 'mobile', 'password']);

        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '你未修改任何内容'
            ));
        }
    
        $flag = $this->supplierSer->modifySupplier(['id'=>$id], $inputs);
        if (is_null($flag)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '修改的数据不存在'
            ));
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '修改的数据不存在'));
        }
    
        $retInfo = $this->supplierSer->getSingleInfo(['id'=>$id]);
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '更新成功', $retInfo));
    }
}