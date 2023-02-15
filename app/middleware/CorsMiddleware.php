<?php
    namespace app\middleware;

    use Webman\MiddlewareInterface;
    use Webman\Http\Response;
    use Webman\Http\Request;

    class CorsMiddleware implements MiddlewareInterface
    {
        public function process(Request $request, callable $handler) : Response
        {
            $response = $request->method() === 'OPTIONS' ? response('') : $handler($request);
            $response->withHeaders([
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Origin' => $request->header('Origin', '*'),
                'Access-Control-Allow-Methods' => '*',
                // 'Access-Control-Allow-Headers' => '*',
                'Access-Control-Allow-Headers' => 'Content-Type,Authorization,token,X-Requested-With,Accept,Origin,requesttype',
            ]);

            return $response;
        }
    }
