<?php


namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class AccessControlAllowOrigin
{
    public function handle($request, Closure $next)
    {
        //允许的域名集
        $allow_origin = [
            '*',
//            'http://192.168.0.199',
//            'http://192.168.0.199:8080',
//            'https://new-oriental.lmh5.com',
        ];
        $response = $next($request);
        $origin = 'https://' . $request->server('HTTP_HOST') ? $request->server('HTTP_HOST') : '';

        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Cookie, X-CSRF-TOKEN, Accept, Authorization, X-XSRF-TOKEN',
            'Access-Control-Expose-Headers' => 'Authorization, authenticated',
            'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, OPTIONS ,DELETE',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        if (in_array($origin, $allow_origin)) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
        }
        return $response;
    }
}