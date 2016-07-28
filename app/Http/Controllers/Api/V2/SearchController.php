<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\SearchController as SearchCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\SearchService;
use App\Libary\Contracts\Http\ResponseInterface;
use Illuminate\Http\Request;
use Validator;

/**
 *
 *
 * @desc 搜索API
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月16日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Search",
 *  description="搜索的API",
 *  produces="['application/json']"
 * )
 */
class SearchController extends SearchCon
{
    /**
     * 搜索的服务层
     * @var SearchService
     */
    protected $searchSer;
    
    public function __construct(EncrypterInterface $aes, SearchService $searchSer)
    {
        parent::__construct($aes, $searchSer);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/searchs",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="搜索接口，根据关键词查找对应结果(1.1)",
     *      notes="目前支持：店名查找，地址查找，区域查找",
     *      type="Supplier",
     *      nickname="search",
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
     *          name="q",
     *          description="查询的关键词,检查是否输入包含sql等关键字",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="test"
     *      ),
     *      @SWG\Parameter(
     *          name="model",
     *          description="1：查找统计数，2：查找list结果集,当它==1时，会忽略type参数，此时type参数可不传",
     *          required=true,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue=1,
     *          enum="['1','2']"
     *      ),
     *      @SWG\Parameter(
     *          name="type",
     *          description="查找类型,1:按店名，2:按区域，3:按地址，不传表示获取三种数据总和",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          enum="['1','2','3']"
     *      ),
     *      @SWG\Parameter(
     *          name="page",
     *          description="默认获取第一页数据,只有当model==2时，才有效",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="per_page",
     *          description="开发阶段，默认2条,只有当model==2时，才有效",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="2"
     *      ),
     *      @SWG\Parameter(
     *          name="longitude",
     *          description="经度(-180, 180),如果不设置，则pitch字段值为0",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="104.072007"
     *      ),
     *      @SWG\Parameter(
     *          name="latitude",
     *          description="纬度(-90, 90)，如果不设置，则pitch字段值为0",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="30.663484"
     *      ),
     *      @SWG\Parameter(
     *          name="consumer_id",
     *          description="如果登陆，必须传，消费者id",
     *          required=false,
     *          type="integer",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="1"
     *      ),
     *      @SWG\Parameter(
     *          name="sortby",
     *          description="排序字段,blend:综合， avg_score:平均分，lower_price:最低价, distance:距离",
     *          required=false,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="blend",
     *          enum="['blend','avg_score','lower_price','distance']"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="请求数据成功"),
     *      @SWG\ResponseMessage(code=404, message="未找到匹配的数据"),
     *      @SWG\ResponseMessage(code=422, message="请求数据验证失败"),
     *      @SWG\ResponseMessage(code=500, message="服务器发生异常")
     *  )
     * )
     */
    public function search(Request $request)
    {
        $inputs = $request->only(['q', 'model', 'type', 'page', 'per_page', 'latitude', 'longitude', 'sortby']);
        $inputs['q'] = e(trim($inputs['q']));
        $validator = Validator::make($inputs, [
                'q' => 'required',
                'model' => 'in:1,2',
                'type' => 'in:1,2,3',
                'sortby' => 'string|required_if:model,2|in:blend,avg_score,lower_price,distance',
                'page' => 'integer',
                'per_page' => 'integer',
                'longitude' => 'required_if:sortby,distance|string',
                'latitude' => 'required_if:sortby,distance|string',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '数据验证错误',
                    $validator->messages()
            ));
        }
        
        $search_model = array_get($inputs, 'model', 1);
        $search_type = array_get($inputs, 'type', '');
        $per_page = array_get($inputs, 'per_page', 10);
        $inputs = array_remove_keys($inputs, ['model', 'type', 'per_page']);
        $inputs = array_add($inputs, 'type', 'list');
        
        $list = $this->searchSer->search($inputs, $search_model, $search_type, $per_page);
        
        if (is_null($list)) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '未找到匹配的数据'));
        }

        exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '请求数据成功', $list));
    }
}