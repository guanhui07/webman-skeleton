<?php
declare(strict_types = 1);
/**
 * The file is part of Dcr/framework
 *
 *
 */

namespace app\service\entity;

use App\Utils\SplBean;

class TestEntity extends SplBean
{
    /**
     * @var ExchGiftInfo
     */
    public ExchGiftInfo $gift;

    public string $msg;

    public int $user_id;
}
