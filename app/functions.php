<?php
/**
 * Here is your custom functions.
 */

use app\utils\ApplicationContext;
use app\utils\Json;
use app\utils\Str;
use GatewayClient\Gateway;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use support\Db;

if ( !function_exists('debug')) {
    function debug($v): void
    {
        if (is_string($v) || is_int($v)) {
            if (trim($v) === '') {
                var_dump($v);
                return;
            }
            echo $v;
        } elseif (is_bool($v) || is_resource($v) || is_null($v) || is_object($v)) {
            var_dump($v);
        } elseif (is_array($v)) {
            echo '<pre>';
            print_r($v);
            echo '</pre>';
        } else {
            var_dump($v);
        }
        return;
    }
}

function apiReturnSuccess($outputData = []): string
{
    $data['code']    = 200;
    $data['message'] = 'success';
    $data['data']    = $outputData;
    $urldecode_flag  = false;
    return Json::encode($data, $urldecode_flag);
}


if ( !function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string.
     * @param  int  $length
     * @return string
     * @throws RuntimeException
     */
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

function writeLog($msg, $name = null, $logDir = null)
{
    if ( !$name) {
        $name = date('Y-m-d_H', time());
    } else {
        if ($logDir === null) {
            $name .= '_'.date('H', time());
        }
    }
    if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']) {
        $name .= '_'.$_SERVER['SERVER_ADDR'];
    } else {
        if (isset($GLOBALS['local_ip'])) {
            $name .= '_'.$GLOBALS['local_ip'];
        }
    }
    if ($logDir === null) {
        $logDir = '/'.'pre_'.'/'.date('Ym', time()).'/'.date('d', time());
    }

    if ( !file_exists(PROJECT_ROOT.'runtime/errlog/'.$logDir)) {
        if (!mkdir($concurrentDirectory = PROJECT_ROOT . 'runtime/errlog/' . $logDir, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }

    $logPath = PROJECT_ROOT.'runtime/errlog/'.$logDir;
    $logFile = $logPath.'/'.$name.'.log';

    if (is_array($msg)) {
        $msg = json_encode($msg);
    }
    $msg = '['.date('Y-m-d H:i:s', time()).'] '.$msg."\n";

    return file_put_contents($logFile, $msg, FILE_APPEND);
}


function getIP()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }

    preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
    $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : null;
    unset($onlineipmatches);
    return $onlineip;
}




function apiResponse($data, $msg = 'sucess')
{
    $ret = [
        'msg' => $msg,
        'code' => 0,
        'data' => $data,
    ];
    return Json::encode($ret);
}


function apiResponseError($msg = 'error', $code = 11211, $data = [])
{
    $ret = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    return Json::encode($ret);
}


function mkDirRev($path): bool
{
    if (is_dir($path)) {
        return true;
    }
    return is_dir(dirname($path)) || mkDirRev(dirname($path)) ? mkDirRev($path) : false;
}

if (!function_exists('event')) {
    function event($event, array $payload = [], bool $halt = false)
    {
        \yzh52521\event\Event::dispatch($event, $payload, $halt);
    }
}
function signatureKey($param): string
{
    ksort($param);
    $string = '';
    foreach ($param as $k => $v) {
        $string .= $k.'='.urlencode($v);
    }
    return hash_hmac('md5', strtolower($string), 'pushOrder');
}


function randStr(): string
{
    $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    shuffle($arr);
    $arr2 = array_slice($arr, 0, 4);
    return implode('', $arr2);
}


function xmlToArray($xml)
{
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}


if ( !function_exists('objToArray')) {
    function objToArray($o)
    {
        // return json_decode(  json_encode($o),1 );
        return json_decode(json_encode($o), true);
    }
}

// ????????????API??????????????????
function result($data, $code = 200, $message = '??????'): string
{
    $name = 'content';
    if ($code == false) {
        $json = $data;
    } else {
        $json['status']  = $code;
        $json['message'] = $message;
        $data ? $json[$name] = $data : '';
    }
    // ??????api??????
    apiLog($json);
    if ($code == false) {
        print_r($data);
        return $data;
    }

    return Json::encode($json);
}


function resultx($data, $code = 200, $message = '??????', $module = '')
{
    $name = 'content';

    if ($code == false) {
        $json = $data;
    } else {
        $json['status']  = $code;
        $json['message'] = $message;
        $json['module']  = $module;
        $data ? $json[$name] = $data : '';
    }
    // ??????api??????
    apiLog($json);
    print_r($json);
    echo PHP_EOL;
    return $json;
}


// ????????????API??????????????????
function error($message = '??????', $code = 404, $data = ''): string
{
    $name = 'content';
    if ($code == false) {
        $json = $data;
    } else {
        $json['status']  = $code;
        $json['message'] = $message;
        $data ? $json[$name] = $data : '';
    }
    // ??????api??????
    apiLog($json);
    if ($code == false) {
        print_r($data);
        return $message;
    }

    $result = json_encode($data);
    return Json::encode($result);
}


function errorx($message = '??????', $code = 404, $data = '')
{
    $name = 'content';
    if ($code == false) {
        $jsonArr = $data;
    } else {
        $jsonArr['status']  = $code;
        $jsonArr['message'] = $message;
        $data ? $jsonArr[$name] = $data : '';
    }
    print_r($jsonArr);
    // ??????api??????
    apiLog($jsonArr);
    return $jsonArr;
}


if ( !function_exists('cache')) {
    function cache($key, Closure $closure, $ttl = null)
    {
        if ($result = \support\Redis::get($key)) {
            return unserialize($result);
        }
        $result = $closure($ttl);
        \support\Redis::set($key, serialize($result), $ttl);
        return $result;
    }
}


function apiLog($jsonArr): void
{
}


if ( !function_exists('collectNew')) {
    /**
     * @param  null|array|Collection  $value
     * @param  bool  $toLine
     *
     * @return Collection
     */
    function collectNew($value = null, $toLine = false)
    {
        if (empty($value)) {
            return new Collection([]);
        }
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }
        if ($value instanceof Collection) {
            return $value;
        }
        //        $value = is_array($value) && $toLine ? arrayToLine($value) : $value;
        return new Collection($value);
    }
}

if ( !function_exists('socketSend')) {
    function socketSend(int $uid, array $data): bool
    {
        $strData = Json::encode($data);
        // ????????????
        Gateway::$registerAddress = '127.0.0.1:1236';

        if (Gateway::isUidOnline($uid)) {
            // ??????????????????uid
            Gateway::sendToUid($uid, $strData);
            return true;
        } else {
            //??????????????? todo:
        }
        return true;
    }
}

if ( !function_exists('retry')) {
    /**
     * $data = retry(3, function () {
     * $rand = mt_rand(1, 10);
     * if ($rand > 8) {
     * return $rand;
     * } else {
     * throw new \Exception('test',1);
     * }
     * }, 5);
     * var_dump($data);
     *
     * @param  int  $times  ????????????
     * @param  callable  $callback  ??????
     * @param  int  $sleep  ????????????
     *
     * @return mixed
     * @throws Throwable
     */
    function retry(int $times, callable $callback, int $sleep = 0): mixed
    {
        beginning:
        try {
            return $callback();
        } catch (\Throwable $e) {
            echo $times.PHP_EOL;
            if (--$times < 0) {
                throw $e;
            }
            sleep($sleep);
            goto beginning;
        }
    }
}


function di($name = null): \DI\Container
{
    if ($name === null) {
        return ApplicationContext::getContainer();
    }
    return ApplicationContext::getContainer()->get($name);
}



/**
 * ????????????key????????????
 *
 * @param  array  $array
 *
 * @return array
 */
function arrayToLine(array $array): array
{
    if (empty($array)) {
        return [];
    }
    if ($array instanceof Arrayable) {
        $array = $array->toArray();
    }

    $convert = [];
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $convert[is_string($key) ? stringToLine($key) : $key] = arrayToLine($value);
        } else {
            $convert[is_string($key) ? stringToLine($key) : $key] = $value;
        }
    }

    return $convert;
}


/**
 * ????????????????????????
 *
 * @param  string  $string  ?????????????????????
 */
function stringToLine(string $string): string
{
    $replaceString = preg_replace_callback('/([A-Z])/', function ($matches) {
        return '_'.strtolower($matches[0]);
    }, $string);

    return trim(preg_replace('/_{2,}/', '_', $replaceString), '_');
}


if ( !function_exists('array_multi_column')) {
    function array_multi_column(array $array, array $column): array
    {
        return array_map(function ($data) use ($column) {
            return array_intersect_key($data, array_flip(array_values($column)));
        }, $array);
    }
}

if ( !function_exists('call')) {
    /**
     * Call a callback with the arguments.
     *
     * @param  mixed  $callback
     * @param  array  $args
     *
     * @return mixed
     */
    function call($callback, array $args = []): mixed
    {
        $result = null;
        if ($callback instanceof Closure) {
            $result = $callback(...$args);
        } elseif (is_object($callback) || (is_string($callback) && function_exists($callback))) {
            $result = $callback(...$args);
        } elseif (is_array($callback)) {
            [$object, $method] = $callback;
            $result = is_object($object) ? $object->{$method}(...$args) : $object::$method(...$args);
        } else {
            $result = call_user_func_array($callback, $args);
        }
        return $result;
    }
}

/**
 * @desc arraySort php?????????????????? ???????????????key ?????????????????????
 *
 * @param  array  $arr  ?????????????????????
 * @param  string  $keys  ???????????????key
 * @param  string  $type  ???????????? asc | desc
 *
 * @return array
 */
function arraySort(array $arr, string $keys, $type = 'asc'): array
{
    $keysvalue = $new_array = [];
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    $type === 'asc' ? asort($keysvalue) : arsort($keysvalue);
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}


/**
 * ??????????????????????????????
 */
function getTimeMillisecond()
{
    // ????????????????????????
    [$msec, $sec] = explode(' ', microtime());
    return (float) sprintf('%.0f', ((float) $msec + (float) $sec) * 1000);
}


/**
 * ??????????????????entity
 *
 * @param  Object  $std
 * @param  stdClass  $entity
 *
 * @return stdClass
 */
function classToEntity(object $std, stdClass $entity): stdClass
{
    foreach ($entity as $property => $value) {
        if (isset($std->$property)) {
            $entity->$property = $std->$property;
        }
    }
    return $entity;
}


/**
 * ??????????????????entity
 *
 * @param  array  $array
 * @param  object  $entity
 *
 * @return object
 */
function arrayToEntity(array $array, object $entity): object
{
    foreach ($entity as $property => $value) {
        if (isset($array[$property])) {
            $entity->$property = $array[$property];
        }
    }
    return $entity;
}

function createDirectoryIfNeeded($directory): void
{
    if ( !file_exists($directory) || !is_dir($directory)) {
        if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
    }
}

if(! function_exists('base64_to_pic')){
    function base64_to_pic($base64 = '', $content_type = 'image/jpeg'){
        return "data:{$content_type};base64,{$base64}";
    }
}


if(!function_exists('import_sql')){
    function import_sql($sql_path = ''){
        if(!file_exists($sql_path)) {
            return "??????????????????";
        }
        if (! $content = file_get_contents($sql_path)) {
            return "?????????????????????";
        }
        /** ???????????? */
        $content = preg_replace('/--.*/i', '', $content);
        $content = preg_replace('/\/\*.*\*\/(\;)?/i', '', $content);
        //???????????????
        $original = '`__PREFIX__';
        $prefix = '';
        $content = str_replace($original, "`{$prefix}", $content);

        /** ???????????? ???????????? */
        $arr = explode(";\n", $content);
        Db::beginTransaction();
        try {
            foreach ($arr as $k => $v)
            {
                $v = trim($v);
                if (empty($v)) {
                    unset($arr[$k]);
                }
                Db::insert($v);
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return $e->getMessage();
        }
        return true;
    }
}

if (!function_exists('input')) {
    /**
     * ????????????
     * @param string $key
     * @param null $default
     * @return mixed
     */
    function input(string $key = '', $default = null)
    {
        if (empty($key)) {
            return \request()->get() + \request()->post();
        }

        if(str_contains($key, '.')) {
            list($method, $k) = explode('.', $key);
            $method = strtolower($method);
            if(in_array($method, ['get', 'post'])){
                return \request()->$method($k ?: null);
            }
        }
        return \request()->input($key, $default);
    }
}






