<?php
namespace app\middleware;

use App\exception\RuntimeException;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        return $handler($request);

        $session = $request->session();
        // 用户未登录
        // 拦截请求，返回一个重定向响应，请求停止向洋葱芯穿越
        if (!$session->get('userinfo') && $request->action !== 'login') {
            echo 'has not login';
//            return redirect('/index/login');
            return throw new RuntimeException('has not login');
        }
        // 请求继续穿越
        return $handler($request);
    }
}