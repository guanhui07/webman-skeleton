<?php
declare(strict_types=1);


namespace app\crontab;


use app\crontab\Contract\CrontabInterface;

/**
 * Class TestCrontab
 * @package app\crontab
 * 配置在 config/crontab.php
 */
class TestCrontab implements CrontabInterface
{
    public function execute()
    {
//        echo date('Y-m-d H:i:s')."11111\n";
    }
}