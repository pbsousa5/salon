<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\BarberController as BarberCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\BarberService;
use App\Salon\Services\V2\LoginService;
use App\Salon\Repositories\V2\BarberRepository;
use Illuminate\Http\Request;
use Validator;
use App\Libary\Contracts\Http\ResponseInterface;
use Illuminate\Support\Str;
use App\Salon\Repositories\V2\BarberSampleRepository;

/**
 * 
 * 
 * @desc 理发师的控制器
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月17日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Barber",
 *  description="理发师操作类",
 *  produces="['application/json']"
 * )
 */
class BarberController extends BarberCon
{
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    /**
     * The BarberRepository instance.
     * @var BarberRepository
     */
    protected $barberRe;
    
    /**
     * The BarberService instance.
     * @var BarberService
     */
    protected $barberSer;
    
    public function __construct(
            EncrypterInterface $aes,
            LoginService $loginSer,
            BarberRepository $barberRe,
            BarberService $barberSer
    ){
        parent::__construct($aes, $loginSer, $barberRe, $barberSer);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/barbers",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取理发师列表数据，默认每次10条(1.1)",
     *      notes="如果请求时上传了门店id，则忽略距离相关问题",
     *      type="Barber",
     *      nickname="b_list",
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
     *          description="排序字段,fans:粉丝， avg_score:平均分，lower_price:最低价, distance:距离",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="update_at",
     *          enum="['fans','avg_score','lower_price','distance']"
     *      ),
     *      @SWG\Parameter(
     *          name="consumer_id",
     *          description="设置用户id的目的是为了检查该用户是否关注了该理发师",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="supplier_id",
     *          description="门店id，如果传入该字段，表示获取门店下的理发师。如果存在consumer_id，则检查该用户是否关注了该理发师",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false
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
     *          description="开发阶段，默认2条(建议，如果是wifi情况下，可扩大请求数，若是3g可减小)",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="2"
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
     *          name="latitude",
     *          description="纬度(-90, 90)，sortby=distance时，必须设置(如果不设置，则采用默认排序)",
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
     *      @SWG\ResponseMessage(code=200, message="获取数据成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到数据"),
     *      @SWG\ResponseMessage(code=422, message="数据验证未通过"),
     *      @SWG\ResponseMessage(code=500, message="服务器异常")
     *  )
     * )
     */
    public function index(Request $request)
    {
        // 获取参数，并验证
        $inputs = $request->only('page', 'm_page', 'per_page', 'consumer_id', 'supplier_id', 'sortby', 'longitude', 'latitude', 'kilometer');
        $validator = Validator::make($inputs, [
                'page' => 'required|integer',
                'per_page' => 'required|integer',
                'sortby' => 'string|required|in:fans,avg_score,lower_price,distance',
                'm_page' => 'required_if:sortby,distance|integer',
                'consumer_id' => 'integer',
                'supplier_id' => 'integer',
                'longitude' => 'required_if:sortby,distance|string',
                'latitude' => 'required_if:sortby,distance|string',
                'kilometer' => 'required_if:sortby,distance',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        // 获取门店理发师时，默认使用积分排序
        if ($inputs['supplier_id']) {
            $inputs['sortby'] = 'avg_score';
            $inputs = array_remove_keys($inputs, ['kilometer']);
        } else {
            $inputs = array_remove_keys($inputs, ['supplier_id']);
        }
        
        $list = $this->barberSer->listBarber($inputs, $inputs['page'], $inputs['per_page']);
        if ($list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '暂无理发师数据'));
        }
        
        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '获取数据成功', $list));
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/barbers/{barber_id}/add_product",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="添加选择的项目(1.1)",
     *      notes="无",
     *      type="void",
     *      nickname="add_product",
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
     *          name="product_ids",
     *          description="需要添加的项目id：aes(1,2,3, token)，token是理发师token",
     *          required=true,
     *          type="string",
     *          paramType="body",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=201, message="添加成功"),
     *      @SWG\ResponseMessage(code=415, message="加密数据不能解析"),
     *      @SWG\ResponseMessage(code=422, message="数据校验不合法"),
     *      @SWG\ResponseMessage(code=500, message="服务器错误")
     *  )
     * )
     */
    public function storeProduct(Request $request, $barber_id)
    {
        // 检查是否登陆
        $this->isLogin($barber_id, LoginService::USER_TYPE_BARBER);
        $cacheValue = $this->loginSer->getUserCache($barber_id, LoginService::USER_TYPE_BARBER);
    
        $inputs = $this->decodeAppData(bodys('json','data'), $cacheValue['token']);
        if (empty($inputs)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                    '加密数据不能解析'
            ));
        }
        
        $product_ids = filter_arr(explode(',', $inputs));
        if (empty($product_ids) || !is_array($product_ids)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误'
            ));
        }
    
        $barber = $this->barberRe->show(['id'=>$barber_id]);
        // 检查理发师是否能够添加或修改项目
        if (is_null($barber) || ($barber->barber_status != 3 && $barber->barber_status != 5)) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '只有绑定或未添加项目的理发师才能调用该接口'
            ));
        }
        
        // 获取添加的项目列表
        $flag = $this->barberSer->addProduct($barber, $product_ids);
        if ($flag) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_CREATED, '操作成功'));
        } else {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY, '添加的项目存在不合法操作'));
        }
    }
}