<?php

namespace App\Http\Middleware;

use Closure;
use App\Libary\Contracts\Http\IResponse;
use Illuminate\Session\TokenMismatchException;
use App\Libary\Contracts\Http\BadAppRequestExceptions;
use App\Libary\Contracts\Http\ResponseInterface;

/**
 * 
 * 
 * @desc 检查请求的参数是否非法
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月13日
 * )
 */
class VerifyReqUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signature = !empty($request->input('signature')) ? $request->input('signature') : null;
        $timestamp = !empty($request->input('timestamp')) ? $request->input('timestamp') : 0;
        $nonce = !empty($request->input('nonce')) ? $request->input('nonce') : 0;
        
        if (!check_url_time($timestamp) || !check_signature($signature, $timestamp, $nonce)) {
            throw new BadAppRequestExceptions(ResponseInterface::HTTP_BAD_REQUEST, 'url请求过期或签名错误');
        }
        
        return $next($request);
    }
}
