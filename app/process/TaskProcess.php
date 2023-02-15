<?php
namespace app\process;

use app\crontab\TestCrontab;
use Workerman\Crontab\Crontab;

class TaskProcess
{
    public function onWorkerStart()
    {
        $allCrontab = config('crontab.crontab');
        foreach ($allCrontab as $k => $crontab) {
            new Crontab($crontab[0], function () use ($crontab) {
                di()->get($crontab[1])->execute();
            });
        }

    }
}