<?php
declare(strict_types=1);


namespace app\controller;

use app\event\TestEvent;
use App\exception\RuntimeException;
use app\middleware\AuthMiddleware;
use app\middleware\CorsMiddleware;
use app\model\UserModel;
use app\service\entity\ExchGiftInfo;
use app\service\entity\TestEntity;
use app\service\TestService;
use app\service\UserService;
use app\utils\JwtToken;
use Carbon\Carbon;
use DI\Attribute\Inject;
use DI\DependencyException;
use DI\NotFoundException;
use Inhere\Validate\Validation;
use Playcat\Queue\Manager;
use Playcat\Queue\Protocols\ProducerData;
use Sunsgne\Annotations\Mapping\Middlewares;
use Sunsgne\Annotations\Mapping\RequestMapping;
use support\Log;
use support\Redis;
use support\Request;
use yzh52521\EasyHttp\Http;
use yzh52521\mailer\Mailer;
use yzh52521\WebmanLock\Locker;

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
     * @param TestService $s
     */
    public function __construct(TestService $s)
    {
        parent::__construct();
//        $this->testService = $s;
    }


    public function hello(Request $request)
    {
        return response('hello webman')
            ->header('Content-Type', 'application/json')
            ->withHeaders([
                'X-Header-One' => 'Header Value 1',
                'X-Header-Tow' => 'Header Value 2',
            ]);
    }


    #[RequestMapping(methods: "GET , POST", path: "/test/test"), Middlewares(CorsMiddleware::class, AuthMiddleware::class)]
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
        $ret = UserModel::query()->limit(2)->get(['name', 'id', 'nickname']);

        //测试redis
        Redis::setex('test_key', 22, 45);
//        debug(Redis::->get('test_key'));

        // 测试 DI
        $this->testService->testDi();
        di()->get(TestService::class)->testDi();
        //test aop
        di()->get(UserService::class)->first();
        $data = UserModel::query()->limit(1)->get(['id']);
        return json(['code' => 0, 'data' => $data]);
    }

    #[RequestMapping(methods: "GET , POST", path: "/test/event")]
    public function event(Request $request)
    {
        event('test', [new TestEvent(['test' => 'event data'])]);
        return json(['code' => 0, 'data' => 'ok']);
    }

    #[RequestMapping(methods: "GET , POST", path: "/test/validate")]
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
    #[RequestMapping(methods: 'GET , POST', path: '/test/dto')]
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

    #[RequestMapping(methods: 'GET , POST', path: '/test/token')]
    public function token(): string
    {
        $token = di()->get(JwtToken::class)->encode([
            'uid' => 27,
            'name' => 'test',
        ]);
//        dd($token);
//        $token = '1813bef4c03caef6ec45380a7246d110';
        $arr = di()->get(JwtToken::class)->decode($token);
        return apiResponse($arr);
    }

    #[RequestMapping(methods: "GET", path: "/test/aop")]
    public function aop()
    {
        echo Carbon::now()->toDateTimeString();
        $data = di()->get(UserService::class)->first();
        return apiResponse($data);
    }

    /**
     * @return string
     * @throws DependencyException
     * @throws NotFoundException
     * @see https://www.workerman.net/plugin/94
     */
    #[RequestMapping(methods: "GET", path: "/test/http")]
    public function http()
    {
//        $response = Http::get('http://httpbin.org/get?name=yzh52521', ['age' => 18]);

        $response = Http::post('http://httpbin.org/post', ['name' => 'yzh52521']);
        echo Carbon::now()->toDateTimeString();
        $data = di()->get(UserService::class)->first();
        return apiResponse($data);
    }

    #[RequestMapping(methods: "GET", path: "/test/lock")]
    public function lock()
    {
        $key = 'Test:lock_key_test';
        $lock = Locker::lock($key);
        if (!$lock->acquire()) {
            throw new RuntimeException('操作太频繁，请稍后再试');
        }
        try {
            // 修改用户金额
        } finally {
            $lock->release();
        }
        return apiResponse([]);
    }

    /**
     * 动态计划任务
     * @return string
     * @see https://github.com/yzh52521/webman-task/tree/main#%E6%B7%BB%E5%8A%A0%E4%BB%BB%E5%8A%A1
     */
    #[RequestMapping(methods: "GET", path: "/test/crotnab")]
    public function crontabCreated()
    {
        $param = [
            'method' => 'request',//计划任务列表
            'args' => [
                'title' => 'test ',
                'type' => 1,
                'rule' => '*/3 * * * * *',
                'target' => 'php webman test',
                'remark' => '备注',
                'sort' => 1,
                'status' => 1,
                'singleton' => 0, //是否单次执行 [0 是 1 不是]
            ],//参数
        ];
        $result = \yzh52521\Task\Client::instance()->request($param);
        return apiResponse($result);


    }

    public function testEmail()
    {
        Mailer::setFrom('10086@qq.com')
            ->setTo('your-mail@domain.com')
            ->setSubject('纯文本测试')
            ->setTextBody('欢迎您使用webman-mailer')
            ->send();
        return apiResponse([]);
    }

    /**
     * https://github.com/nsnake/playcat-queue
     */
    public function testQueue()
    {
        //即时消费消息
        $payload = new ProducerData();
        //对应消费队列里的任务名称
        $payload->setChannel('test');
        //对应消费队列里的任务使用的数据
        $payload->setQueueData([1, 2, 3, 4]);
        //推入队列并且获取消息id
        $id = Manager::getInstance()->push($payload);

        //延迟消费消息
        $payload_delay = new ProducerData();
        $payload_delay->setChannel('test');
        $payload_delay->setQueueData([6, 7, 8, 9]);
        //设置60秒后执行的任务
        $payload_delay->setDelayTime(60);
        //推入队列并且获取消息id
        $id = Manager::getInstance()->push($payload_delay);
    }
}