<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\ProductController as ProductCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\SupplierService;
use App\Salon\Services\V2\ProductService;
use App\Salon\Services\V2\LoginService;
use App\Libary\Contracts\Http\ResponseInterface;
use Validator;
use Illuminate\Http\Request;

/**
 *
 *
 * @desc 获取产品相关信息接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月26日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.1.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Product",
 *  description="产品相关信息API",
 *  produces="['application/json']"
 * )
 */
class ProductController extends ProductCon
{
    /**
     * 产品的服务层
     * @var ProductService
     */
    protected $productSer;
    
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
    
    public function __construct(
            EncrypterInterface $aes,
            ProductService $productSer,
            SupplierService $supplierSer,
            LoginService $loginSer
    ){
        parent::__construct($aes, $productSer, $supplierSer, $loginSer);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/products/{user_id}/list",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定门店或理发师的产品列表(1.1)",
     *      notes="无",
     *      type="Product",
     *      nickname="p_list",
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
     *          description="门店id或者理发师id",
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
     *          name="req_type",
     *          description="获取项目的请求类型,1:获取自身项目,2:理发师获取所有项目，并标识项目理发师是否拥有(hasProduct)，仅在请求类型为barber时有效",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1",
     *          enum="['1','2']"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="获取数据成功"),
     *      @SWG\ResponseMessage(code=404, message="未发现你想要的数据"),
     *      @SWG\ResponseMessage(code=422, message="数据验证失败")
     *  )
     * )
     */
    public function index(Request $request, $user_id)
    {
        // 获取参数，并验证
        $inputs['user_id'] = $user_id;
        $inputs['user_type'] = $request->input('user_type');
        $inputs['req_type'] = $request->input('req_type');
        $validator = Validator::make($inputs, [
                'user_id' => 'required|integer',
                'user_type' => 'required|in:supplier,barber',
                'req_type' => 'integer|in:1,2',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
    
        $list = $this->productSer->listProduct($user_id, $inputs['user_type'], 0, $inputs['req_type']);
        if (is_null($list) || $list->isEmpty()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    '查找的数据不存在'
            ));
        }
    
        exit($this->appResp->buildReplyMsg(
                ResponseInterface::HTTP_OK,
                '获取数据成功',
                $list
        ));
    }
}