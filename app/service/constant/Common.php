<?php
declare(strict_types=1);


namespace app\service\constant;


class Common
{
    public const YES = 1;
    public const NO = 0;
    public const MAN = 1;
    public const FEMALE = 2;

    public static function status($id = null){
        $list = [
            self::YES => '启用',
            self::NO => '禁用'
        ];
        return $list[$id] ?? $list;
    }

    public static function yesOrNo($id = null){
        $list = [
            self::YES => '是',
            self::NO => '否'
        ];
        return $list[$id] ?? $list;
    }

    public static function goodsStatus($id = null){
        $list = [
            self::YES => '上架',
            self::NO => '下架'
        ];
        return $list[$id] ?? $list;
    }

    public static function sex($id = null){
        $list = [
            self::MAN => '男',
            self::FEMALE => '女'
        ];
        return $list[$id] ?? $list;
    }
}