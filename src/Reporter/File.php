<?php
namespace Tricolor\Tracker\Reporter;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 17:18
 */
class File extends Base
{
    public static $cache;

    public function __construct()
    {
        if (file_exists('/tmp')) {
            File::$cache = '/tmp/trace_cache';
        } else {
            File::$cache = __DIR__ . '/../Cache/';
        }
        if (!($res = @mkdir(File::$cache, 0776, true))) {
            //报错 todo
        }
    }

    public function report()
    {
        // 记录在日志里
        mkdir($dir = File::$cache . '/' . Context::$TraceId, 0776, true);
        $fp = fopen($file = $dir . '/' . Context::$RpcId, 'wb');
        fwrite($fp, Context::get());
        fclose($fp);
    }
}