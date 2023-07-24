<?php
declare(strict_types=1);

use Playcat\Queue\Protocols\ConsumerData;
use Playcat\Queue\Protocols\ConsumerDataInterface;
use Playcat\Queue\Protocols\ConsumerInterface;

/**
 * Class TestComsumer
 * https://github.com/nsnake/playcat-queue
 */
class TestComsumer implements ConsumerInterface
{
    //任务名称，对应发布消息的名称
    public $queue = 'test';

    public function consume(ConsumerData $payload)
    {
        //获取发布到队列时传入的内容
        $data = $payload->getQueueData();
        //sendsms or sendmail and so son.
    }
}