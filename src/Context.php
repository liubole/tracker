<?php
namespace Tricolor\Tracker;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 20:48
 */
class Context
{
    public static $TraceId;//: 全局调用ID
    public static $RpcId;//: 区分调用层级
    public static $At;//: 调用时间
    public static $TAG;//: 标记

    public static function get()
    {
        return serialize(get_class_vars(__CLASS__));
    }

    public static function set($serial)
    {
        if ($serial && ($trace = unserialize($serial)))
            foreach ($trace as $var => $val) self::$$var = $val;
    }

    public static function serialToArray($serial)
    {
        $context = array();
        if ($serial && ($trace = unserialize($serial)))
            foreach ($trace as $var => $val) $context[$var] = $val;
        return $context;
    }

}