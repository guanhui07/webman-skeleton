<?php
declare(strict_types=1);


namespace app\service;
use Webman\RedisQueue\Redis;
use Webman\RedisQueue\Client;

class QueueService
{
    /**
     * 任务投递
     * @param array $params
     * @return bool|string
     */
    static function push($params = []){
        if(empty($params['consumer'])){
            return "consumer参数错误";
        }
        $delay = empty($params['delay']) ? 0 : $params['delay'];
        $async = empty($params['async']) ? 0 : 1;
        // 队列名
        $queue = $params['queue_name'];
        // 数据，可以直接传数组，无需序列化
        $data = $params;
        $res = true;
        // 投递消息
        if($async){
            Client::send($queue, $data, $delay);
        }else{
            Redis::send($queue, $data, $delay);
        }
        return $res;
    }

}