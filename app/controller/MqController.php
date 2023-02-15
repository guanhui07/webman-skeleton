<?php
declare(strict_types=1);


namespace app\controller;

use app\service\TestService;
use DI\Attribute\Inject;
use Sunsgne\Annotations\Mapping\RequestMapping;
use support\Request;
use Webman\RedisQueue\Client;
use function Workbunny\WebmanRabbitMQ\sync_publish;
use process\workbunny\rabbitmq\TestBuilder;

class MqController extends BaseController
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

    /**
     * @param Request $request
     * @see https://www.workerman.net/plugin/12   redis
     */
    #[RequestMapping(methods: "GET , POST", path: "/mq/produce")]
    public function test(Request $request)
    {
        $queueName = 'send-mail';
        // 数据，可以直接传数组，无需序列化
        $data = ['to' => 'tom@gmail.com', 'content' => 'hello'];
        // 投递消息
        Client::send($queueName, $data);
        // 投递延迟消息，消息会在60秒后处理
        Client::send($queueName, $data, 60);

        return response('redis queue test');
    }

    /**
     * @see https://www.workerman.net/plugin/67
     * @param Request $request
     * @return \support\Response
     */
    #[RequestMapping(methods: "GET , POST", path: "/mq/produce2")]
    public function rabbit(Request $request)
    {
        sync_publish(TestBuilder::instance(), 'abc'); //return bool
        return response('redis queue test');
    }
}