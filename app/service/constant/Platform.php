<?php
declare(strict_types=1);


namespace app\service\constant;


class Platform
{
    public const MP = 'mp';
    public const MINI = 'mini';
    public const WECHAT = 'wechat';
    public const WORKWX = 'workwx';
    public const APP = 'app';
    public const PC = 'pc';
    public const H5 = 'h5';

    /**
     * 类型
     * @param null $id
     * @return array|mixed
     */
    public static function wechatTypes($id = null){
        $list = [
            self::MP => '微信公众号',
            self::MINI => '微信小程序',
            self::WECHAT => '个微',
            /*self::WORKWX => '企微',*/
        ];
        return $list[$id] ?? $list;
    }

    /**
     * 类型
     * @param null $id
     * @return array|mixed
     */
    public static function types($id = null){
        $list = [
            self::MP => '微信公众号',
            self::MINI => '微信小程序',
            self::WECHAT => '个微',
            self::WORKWX => '企微',
            self::PC => '网站',
            self::APP => 'APP',
            self::H5 => 'H5',
        ];
        return $list[$id] ?? $list;
    }
}