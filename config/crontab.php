<?php
declare(strict_types=1);

use app\crontab\TestCrontab;

return [
//    'enable' => $_ENV['crontab_enable'] ?? true,

    'crontab' => [
        ['*/1  * * * * *',TestCrontab::class],

    ],
];