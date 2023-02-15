<?php
declare(strict_types=1);
/**
 * The file is part of Dcr/framework
 *
 *
 */

namespace app\utils;

use support\Redis;

class Cache
{
    public static function set($key, $value, $ttl = null)
    {
        return Redis::setex($key, $ttl, serialize($value));
    }

    public static function get($key, $default = null)
    {
        $val = Redis::get($key);
        if ($val) {
            return unserialize($val);
        }
        return $default;
    }

    public static function delete($key)
    {
        return Redis::del($key);
    }
}
