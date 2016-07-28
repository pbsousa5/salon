<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Libary\Contracts\Http\ResponseInterface;
use App\Libary\Contracts\Http\BadAppRequestExceptions;
use RuntimeException;
use Predis\Connection\ConnectionException;
use ErrorException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /* private $appResp;
    public function __construct(IResponse $appResp, LoggerInterface $log)
    {
        $this->appResp = $appResp;
        parent::__construct($log);
    } */
    
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $appResp = App::make('appResp');
        
        /* if ($e instanceof ModelNotFoundException) {
            exit($appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    $appResp->getStatusText(ResponseInterface::HTTP_NOT_FOUND)
            ));
        }
        if ($e instanceof BadAppRequestExceptions) {
            exit($appResp->buildReplyMsg($e->getStatusCode(), $e->getMessage()));
        } elseif ($e instanceof ModelNotFoundException) {
            exit($appResp->buildReplyMsg(
                    ResponseInterface::HTTP_NOT_FOUND,
                    $appResp->getStatusText(ResponseInterface::HTTP_NOT_FOUND)
            ));
        } elseif ($e instanceof ConnectionException) {
            exit($appResp->buildReplyMsg(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    '服务器目前拒绝连接，请稍后再试'
            ));
        } elseif ($e instanceof  MethodNotAllowedHttpException) {
            exit($appResp->buildReplyMsg(
                    ResponseInterface::HTTP_METHOD_NOT_ALLOWED,
                    '访问方式不正确，服务器拒绝接受请求'
            ));
        } elseif ($e instanceof RuntimeException || $e instanceof ErrorException) {
            exit($appResp->buildReplyMsg(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    $appResp->getStatusText(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
            ));
        } */
        
        return parent::render($request, $e);
    }
}
