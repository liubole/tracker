<?php
namespace Tricolor\Tracker;
use Tricolor\Tracker\Common\Coding;
use Tricolor\Tracker\Common\Logger;
use Tricolor\Tracker\Config\Debug;

/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 20:48
 */
class Context
{
    public static $TraceId;
    public static $RpcId;
    public static $At;

    public static function get()
    {
        return Coding::encode(Context::toArray());
    }

    public static function set($serial)
    {
        if (!$serial && !is_string($serial) && !is_array($serial)) return;
        try {
            $trace = is_string($serial) ? Coding::decode($serial) : $serial;
            $keys = array_intersect(array_keys(self::toArray()), array_keys($trace));
            foreach ($keys as $key) {
                if (isset($trace[$key])) self::$$key = $trace[$key];
            }
        } catch (\Exception $e) {
            Logger::log(Debug::ERROR, __METHOD__ . ': exception :' . $e->getMessage());
        }
    }

    public static function toArray()
    {
        return get_class_vars(__CLASS__);
    }

}