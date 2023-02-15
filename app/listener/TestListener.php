<?php
declare(strict_types=1);


namespace app\listener;


use app\event\TestEvent;

class TestListener
{
    public function __construct()
    {
    }

    /**
     * 处理事件
     * @return void
     */
    public function handle(TestEvent $event)
    {
        var_dump('listener');
        var_dump($event->data);
    }
}