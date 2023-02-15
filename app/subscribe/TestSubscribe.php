<?php
declare(strict_types=1);


namespace app\subscribe;


use app\event\TestEvent;

class TestSubscribe
{
    public function handleTest(TestEvent $event)
    {
        var_dump('subscribe');
        var_dump($event);
    }

    public function subscribe($events)
    {
//        $events->listen(
//            TestEvent::class,
//            [__CLASS__, 'handleTest']
//        );
    }
}