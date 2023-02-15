<?php
declare(strict_types=1);


namespace app\queue\redis;


use Webman\RedisQueue\Consumer;

class MyMailSend implements Consumer
{
    // 要消费的队列名
    public $queue = 'send-mail';

    // 连接名，对应 plugin/webman/redis-queue/redis.php 里的连接`
    public $connection = 'default';

    // 消费
    public function consume($data)
    {
        // 无需反序列化
        var_dump($data); // 输出 ['to' => 'tom@gmail.com', 'content' => 'hello']

        $consumer = $data['consumer'];
        if(is_string($consumer)){//全局函数
            $callback = $consumer;
        }else{ //对象方法
            $obj = new $consumer[0]();
            $callback = [$obj, $consumer[1]];
        }
        call_user_func_array($callback, [$data]); //下发实际的消费者
    }
}