<?php

namespace App\Libary\Contracts\Http;

/**
 * 
 * 
 * @desc 向客户端响应
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月14日
 */
interface ResponseInterface
{
    const HTTP_OK = 200; // [GET] -服务器成功返回用户请求的数据
    const HTTP_CREATED = 201; // [POST/PUT/PATCH] -用户新建或修改数据成功
    const HTTP_ACCEPTED = 202; // 表示一个请求已经进入后台排队
    const HTTP_NO_CONTENT = 204; // 对一次没有返回主体信息(像一次DELETE请求)的请求的响应 
    
    const HTTP_NOT_MODIFIED = 304; // 当使用HTTP缓存头信息时使用304
    
    const HTTP_BAD_REQUEST = 400; // [POST/PUT/PATCH] - 请求是畸形的, 比如无法解析请求体 
    const HTTP_UNAUTHORIZED = 401; // 当没有提供或提供了无效认证细节时。如果返回401，需要用户重新登陆
    const HTTP_FORBIDDEN = 403; // 表示用户得到授权（与401错误相对），但是访问是被禁止的
    const HTTP_NOT_FOUND = 404; // 用户发出的请求针对的是不存在的记录，服务器没有进行操作
    const HTTP_METHOD_NOT_ALLOWED = 405; // 当一个对认证用户禁止的HTTP方法被请求时
    const HTTP_NOT_ACCEPTABLE = 406; // 用户请求的格式不可得
    const HTTP_GONE = 410; // 表示资源在终端不再可用。当访问老版本API时，作为一个通用响应很有用 (需要更新版本)
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415; // 如果请求中包含了不正确的内容类型 
    const HTTP_UNPROCESSABLE_ENTITY = 422; //  [POST/PUT/PATCH] - 出现验证错误时使用 
    const HTTP_TOO_MANY_REQUESTS = 429; // 当请求由于访问频率限制而被拒绝时
    
    const HTTP_INTERNAL_SERVER_ERROR = 500; // 服务器发生错误，用户将无法判断发出的请求是否成功。
    
    /**
     * 
     * 组装响应APP端的消息
     */
    public function buildReplyMsg($code, $msg, $data);
    
    /**
     * 
     * 获取code对应的文本说明
     */
    public function getStatusText($code);
}