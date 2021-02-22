<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Log::error($exception->getMessage());

        // 用户认证的异常，我们需要返回 401 的 http code 和错误信息
        if ($exception instanceof UnauthorizedHttpException) {
            return errorReturn(401, '请先登录');
        }

        if ($exception instanceof ValidationException) {
            $msg = array_values($exception->errors())[0][0];
            return errorReturn(400, $msg, ['error' => $exception->getMessage()]);
        } elseif ($exception instanceof CustomValidateException) {
            return errorReturn(400, $exception->getMessage());
        } elseif ($exception instanceof NotFoundHttpException) {
            return errorReturn(700, '请求资源不存在');
        } elseif ($exception instanceof ModelNotFoundException) {
            return errorReturn(700, '数据资源不存在');
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return errorReturn(400, '请求方式不对');
        } elseif ($exception instanceof AuthenticationException) {
            return errorReturn(401, '请先登录', ['error' => $exception->getMessage()]);
        } elseif ($exception instanceof ThrottleRequestsException) {
            return errorReturn(400, 'frequent operation', ['error' => $exception->getMessage()]);
        } elseif ($exception instanceof DatabaseDealFailException) {
            return errorReturn(300, '操作失败');
        } else {
            return errorReturn(500, '系统错误', ['error' => $exception->getMessage()]);
        }
        return parent::render($request, $exception);
    }
}
