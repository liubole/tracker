<?php
namespace Tricolor\Tracker\Common;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 15:48
 */
class Env
{
    private static $root;

    public static function root()
    {
        if (!self::$root) self::$root = dirname(__DIR__);
        return self::$root;
    }

    public static function logRoot()
    {
        $root = self::root();
        $log_root = $root . DIRECTORY_SEPARATOR . 'logs';
        is_dir($log_root) OR mkdir($log_root, 0777, true);
        return is_dir($log_root) ? $log_root : false;
    }
}