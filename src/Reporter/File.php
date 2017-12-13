<?php
namespace Tricolor\Tracker\Reporter;
use Tricolor\Tracker\Config;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 17:18
 */
class File extends Base
{
    public function __construct()
    {
        is_dir(Config::$cacheDir) OR @mkdir(Config::$cacheDir, 0776, true);
    }

    public function report()
    {
        // 记录在日志里
        mkdir($dir = Config::$cacheDir . '/' . Context::$TraceId, 0776, true);
        $fp = fopen($file = $dir . '/' . Context::$RpcId, 'wb');
        fwrite($fp, Context::get());
        fclose($fp);
    }
}