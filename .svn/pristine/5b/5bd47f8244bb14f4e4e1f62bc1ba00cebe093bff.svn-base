<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\SearchService;
use Illuminate\Http\Request;
use App\Libary\Contracts\Http\ResponseInterface;
use Validator;

class SearchController extends ApiBaseController
{
    /**
     * 搜索的服务层
     * @var SearchService
     */
    protected $searchSer;
    
    public function __construct(EncrypterInterface $aes, SearchService $searchSer)
    {
        parent::__construct($aes);
        $this->searchSer = $searchSer;
        //$this->middleware('req');
    }
    
    public function search(Request $request)
    {
        $inputs = $request->only(['q', 'model', 'type', 'page', 'per_page', 'latitude', 'longitude', 'sortby', 'order']);
        $inputs['q'] = e($inputs['q']);
        $validator = Validator::make($inputs, [
                'q' => 'required',
                'model' => 'in:1,2',
                'type' => 'in:1,2,3',
                'sortby' => 'string|required_if:model,2|in:updated_at,avg_score,lower_price,distance',
                'order' => 'string|required_if:model,2|in:desc,asc',
                'page' => 'integer',
                'per_page' => 'integer',
        ]);
        if ($validator->fails()) {
            exit($this->appResp->buildReplyMsg(
                    ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                    '请求数据验证失败',
                    $validator->messages()
            ));
        }

        $list = $this->searchSer->search($inputs, $inputs['model'], $inputs['type'], $inputs['per_page']);
        if (is_null($list)) {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_NOT_FOUND, '未找到匹配的数据'));
        } else {
            exit($this->appResp->buildReplyMsg(ResponseInterface::HTTP_OK, '请求数据成功', $list));
        }
    }
    
}