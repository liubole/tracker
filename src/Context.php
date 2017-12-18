<?php
namespace Tricolor\Tracker;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 20:48
 */
class Context
{
    public static $TraceId;//: 全局调用ID
    public static $RpcId;//: 区分调用层级
    public static $At;//: 调用时间

    public static function get()
    {
        return serialize(Context::toArray());
    }

    public static function set($serial)
    {
        try {
            if ($serial && ($trace = @unserialize($serial)))
                foreach ($trace as $var => $val) self::$$var = $val;
        } catch (\Exception $e) {}
    }

    public static function toArray()
    {
        return get_class_vars(__CLASS__);
    }

}