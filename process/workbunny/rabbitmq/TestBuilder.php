<?php
declare(strict_types=1);

namespace process\workbunny\rabbitmq;

use Bunny\Channel as BunnyChannel;
use Bunny\Async\Client as BunnyClient;
use Bunny\Message as BunnyMessage;
use Workbunny\WebmanRabbitMQ\Constants;
use Workbunny\WebmanRabbitMQ\FastBuilder;

class TestBuilder extends FastBuilder
{
    // QOS 大小
    protected int $prefetch_size = 0;
    // QOS 数量
    protected int $prefetch_count = 0;
    // QOS 是否全局
    protected bool $is_global = false;
    // 是否延迟队列
    protected bool $delayed = false;
    // 消费回调
    public function handler(BunnyMessage $message, BunnyChannel $channel, BunnyClient $client): string
    {
        // TODO 消费需要的回调逻辑
        var_dump('请重写 TestBuilder::handler() ');
        return Constants::ACK;
        # Constants::NACK
        # Constants::REQUEUE
    }
}