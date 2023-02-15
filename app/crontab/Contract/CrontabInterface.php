<?php
declare(strict_types=1);


namespace app\crontab\Contract;


interface CrontabInterface
{
    public function execute();
}