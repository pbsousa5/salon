<?php

namespace App\Libary\Http;


use App\Libary\Contracts\Http\ResponseInterface;
/**
 * 
 * 
 * @desc 向APP端响应json
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月14日
 */
class JsonResponse implements ResponseInterface
{
    /**
     * 响应json
     * @see \App\Libary\Contracts\Http\IResponse::buildReplyMsg()
     */
    public function buildReplyMsg($code, $msg, $data='')
    {
        header('Content-Type:application/json; charset=utf-8');
        
        $msg = [
                'code' => $code,
                'msg' => $msg,
        ];
        if (!empty($data)) {
            $msg['data'] = $data;
        }
        
        return json_encode($msg);
    }
    
    /**
     * 获取对应code的文字说明
     * 返回文字已经在resources中定义说明了
     * @see \App\Libary\Contracts\Http\IResponse::getStatusText()
     */
    public function getStatusText($code)
    {
        return trans("feedback.{$code}");
    }
}