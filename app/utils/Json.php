<?php

namespace app\utils;

use InvalidArgumentException;
use JsonException;

class Json
{
    /**
     * @param  string  $json
     * @param  bool  $assoc
     * @param  int  $depth
     * @param  int  $options
     *
     * @return mixed
     */
    public static function decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        $data = '';
        try {
            $data = json_decode($json, $assoc, $depth, JSON_THROW_ON_ERROR | $options);
        } catch (JsonException $e) {
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_decode error: '.json_last_error_msg());
        }

        return $data;
    }

    /**
     * @param $value
     * @param  int  $options
     * @param  int  $depth
     *
     * @return string
     */
    public static function encode($value, int $options = 0, int $depth = 512): string
    {
        try {
            $json = json_encode($value, JSON_THROW_ON_ERROR | $options, $depth);
        } catch (JsonException $e) {
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_encode error: '.json_last_error_msg());
        }

        return $json;
    }

}
