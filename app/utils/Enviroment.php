<?php declare(strict_types=1);
/**
 * The file is part of Dcr/framework
 *
 *
 */

namespace app\utils;

/**
 * Class Enviroment
 * @package App\common\utils
 */
class Enviroment
{
    public static function isProd()
    {
        return config('app.env') === 'prod';
    }

    public static function isDev()
    {
        return config('app.env') === 'dev';
    }

    public static function isLocal()
    {
        return config('app.env') === 'local';
    }
}
