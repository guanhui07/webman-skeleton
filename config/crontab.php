<?php
declare(strict_types=1);

use app\crontab\TestCrontab;

return [
    'crontab' => [
        ['*/1  * * * * *',TestCrontab::class],

    ],
];