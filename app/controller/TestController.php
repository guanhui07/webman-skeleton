<?php
declare(strict_types=1);


namespace app\controller;
use app\controller\BaseController;
use app\event\TestEvent;
use app\middleware\CorsMiddleware;
use app\middleware\AuthMiddleware;
use app\middleware\TestMiddleware;
use app\model\UserModel;
use app\service\entity\ExchGiftInfo;
use app\service\entity\TestEntity;
use app\service\TestService;
use app\service\UserService;
use app\utils\JwtToken;
use Carbon\Carbon;
use DI\Attribute\Inject;
use Inhere\Validate\Validation;
use Sunsgne\Annotations\Mapping\RequestMapping;
use Sunsgne\Annotations\Mapping\Middleware;
use Sunsgne\Annotations\Mapping\Middlewares;
use support\Log;
use support\Redis;
use support\Request;

class TestController extends BaseController
{

    /**
     * php8注解方式注入
     * @var TestService
     */
    #[Inject]
    public TestService $testService;

    /**
     * 测试构造参数依赖注入
     * 测试使用中间件
     *
     * @param  TestService  $s
     */
    public function __construct(TestService $s)
    {
        parent::__construct();
//        $this->testService = $s;
    }


    #[RequestMapping(methods: "GET , POST" , path:"/test/test") , Middlewares(CorsMiddleware::class , AuthMiddleware::class)]
    public function test(Request $request)
    {
        // 测试 request
        $test = $request->get('name', 'zhangsan');

        //测试获取config
        //        var_dump( config('app.debug'));die;
        // 测试 response
        //        return  reponse()->setContent('test_abc')->send();
        // 测试collect
        $test = collect([23, 34])->pop(1);
        // 测试carbon
        echo Carbon::now()->format('Y-m-d');
        // 测试 log
        Log::info('test');
        // 测试 laravel orm包
        // 测试 laravel orm包
        $ret = UserModel::query()->limit(2)->get(['name', 'id', 'nickname']);

        //测试redis
        Redis::setex('test_key',22,45);
//        debug(Redis::->get('test_key'));

        // 测试 DI
        $this->testService->testDi();
        di()->get(TestService::class)->testDi();
        //test aop
        di()->get(UserService::class)->first();
        $data = UserModel::query()->limit(1)->get(['id']);
        return json(['code' => 0, 'data' => $data]);
    }

    #[RequestMapping(methods: "GET , POST" , path:"/test/event")]
    public function event(Request $request)
    {
        event('test',[new TestEvent(['test'=>'event data'])]);
        return json(['code' => 0, 'data' => 'ok']);
    }

    #[RequestMapping(methods: "GET , POST" , path:"/test/validate")]
    public function validate(Request $request)
    {
        $v = Validation::check($request->all(), [
            // add rule
            ['title', 'min', 40],
            ['freeTime', 'number'],
        ]);

        if ($v->isFail()) {
            var_dump($v->getErrors());
            var_dump($v->firstError());
        }

        // $postData = $v->all(); // 原始数据
        $safeData = $v->getSafeData(); // 验证通过的安全数据
        return json(['code' => 0, 'data' => $safeData]);
    }


    /**
     * 测试 dto
     */
    #[RequestMapping(methods: 'GET , POST', path:'/test/dto')]
    public function dto()
    {
        arrayToEntity([
            'msg' => 'dsfdf',
            'user_id' => 222,

        ], new TestEntity());

        $settleConfig = new TestEntity([
            'msg' => 'dsfdf',
            'user_id' => 222,
            'gift' => new ExchGiftInfo([
                'id' => 1,
                'name' => 'test',
            ]),
        ]);
        return $this->dtoParam($settleConfig);
    }

    protected function dtoParam(TestEntity $testEntity): int
    {
        return $testEntity->gift->id;
    }

    #[RequestMapping(methods: 'GET , POST', path:'/test/token')]
    public function token(): string
    {
        $token = di()->get(JwtToken::class)->encode([
            'uid'=>27,
            'name'=>'test',
        ]);
//        dd($token);
//        $token = '1813bef4c03caef6ec45380a7246d110';
        $arr = di()->get(JwtToken::class)->decode($token);
        return apiResponse($arr);
    }

    #[RequestMapping(methods: "GET", path:"/test/aop")]
    public function aop()
    {
        echo Carbon::now()->toDateTimeString();
        $data =  di()->get(UserService::class)->first();
        return apiResponse($data);
    }
}