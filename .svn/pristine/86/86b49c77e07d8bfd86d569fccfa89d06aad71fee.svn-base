<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use Cache;
use App\Libary\Contracts\Http\ResponseInterface;
/**
 * 
 * 
 * @desc 用户辅助开发
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月30日
 *
 *
 * @SWG\Resource(
 *  apiVersion="1.0.0",
 *  swaggerVersion="2.1",
 *  basePath="http://localhost/mei_fa/public/api/v2",
 *  resourcePath="/Helper",
 *  description="生成各种常用数据，如：aes加密",
 *  produces="['application/json']"
 * )
 */
class HelperController extends ApiBaseController
{
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/helper/aes_encode",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="生成aes加密数据",
     *      notes="秘钥必须为16或者32位",
     *      type="void",
     *      nickname="aes_encode",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="data",
     *          description="加密的数据,格式为：mobile=18683338412&password=141107",
     *          required=true,
     *          type="string",
     *          paramType="form",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="secret_key",
     *          description="加密的秘钥，16或者32位",
     *          required=true,
     *          type="string",
     *          paramType="form",
     *          allowMultiple=false,
     *          defaultValue="VVSOYqny5qWCnkoLHvnmm23FN4Cydmfd"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="加密成功"),
     *      @SWG\ResponseMessage(code=500, message="服务器加密失败")
     *  )
     * )
     */
    public function aesEncodeData(Request $request)
    {
        $value = trim($request->input('data'));
        $key = trim($request->input('secret_key'));
        echo $this->encodeAppData($value, $key);
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/helper/aes_decode",
     *  @SWG\Operation(
     *      method="POST",
     *      summary="解密加密后的aes数据",
     *      notes="秘钥必须为16或者32位",
     *      type="void",
     *      nickname="aes_decode",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="data",
     *          description="加密的aes数据",
     *          required=true,
     *          type="string",
     *          paramType="form",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="secret_key",
     *          description="解密的秘钥，16或者32位",
     *          required=true,
     *          type="string",
     *          paramType="form",
     *          allowMultiple=false,
     *          defaultValue="VVSOYqny5qWCnkoLHvnmm23FN4Cydmfd"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="加密成功"),
     *      @SWG\ResponseMessage(code=500, message="服务器加密失败")
     *  )
     * )
     */
    public function aesDecodeData(Request $request)
    {
        $value = trim($request->input('data'));
        $key = trim($request->input('secret_key'));
        $data = $this->decodeAppData($value, $key);
        if (empty($data)) {
            if (empty($inputs)) {
                exit($this->appResp->buildReplyMsg(
                        ResponseInterface::HTTP_UNSUPPORTED_MEDIA_TYPE,
                        '加密数据不能解析'
                ));
            }
        }
        echo create_linkstring($data);
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/helper/sign",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="根据时间戳，随机字符串生成url签名",
     *      notes="秘钥是服务端初始接口返回的",
     *      type="void",
     *      nickname="sign",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="secret_key",
     *          description="解密的秘钥，服务端下发的",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue="VVSOYqny5qWCnkoLHvnmm23FN4Cydmfd"
     *      ),
     *      @SWG\ResponseMessage(code=200, message="签名成功"),
     *      @SWG\ResponseMessage(code=500, message="服务端错误")
     *  )
     * )
     */
    public function createSignature(Request $request)
    {
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        $key = $request->input('secret_key');
        
        $tmpArr = [$key, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = sha1(implode($tmpArr));
        
        echo $tmpStr;
    }
    
    /**
     *
     *
     * @SWG\Api(
     *  path="/helper/check_sign",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="检验url签名是否正确",
     *      notes="秘钥是服务端初始接口返回的",
     *      type="void",
     *      nickname="check_sign",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="timestamp",
     *          description="发送请求的时间戳",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="nonce",
     *          description="签名是加入的随机字符串(客户端生成)",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=false,
     *          defaultValue=""
     *      ),
     *      @SWG\Parameter(
     *          name="signature",
     *          description="url签名",
     *          required=true,
     *          type="string",
     *          paramType="query",
     *          allowMultiple=true,
     *          defaultValue=""
     *      ),
     *      @SWG\ResponseMessage(code=200, message="验证成功"),
     *      @SWG\ResponseMessage(code=500, message="服务端错误")
     *  )
     * )
     */
    public function checkSignature(Request $request)
    {
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        $signature = $request->input('signature');
        
        $ret = check_signature($signature, $timestamp, $nonce);
        if ($ret) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    
    /**
     * 
     * 
     * @SWG\Api(
     *  path="/helper/{id}/token",
     *  @SWG\Operation(
     *      method="GET",
     *      summary="获取指定id的token",
     *      notes="获取的token是未加密的token，请直接使用，不要进行aes解密操作",
     *      type="void",
     *      nickname="token",
     *      @SWG\Consumes("multipart/form-data"),
     *      authorizations={},
     *      @SWG\Parameter(
     *          name="id",
     *          description="要获取的资源id",
     *          required=true,
     *          type="integer",
     *          paramType="path",
     *          allowMultiple=false
     *      ),
     *      @SWG\ResponseMessage(code=200, message="成功"),
     *      @SWG\ResponseMessage(code=500, message="失败")
     *  )
     * )
     */
    public function getUserToken($id)
    {
        $kid = 'consumer'.$id;
        
        if (Cache::has($kid)) {
            echo Cache::get($kid)['token'];exit;
        }
        
        echo 'null';exit;
    }
}