<?php
/**
 * User: Tricolor
 * Date: 2017/12/20
 * Time: 15:48
 */
namespace Tricolor\Tracker\Common;

class Env
{
    private static $root;

    public static function root()
    {
        if (!self::$root) self::$root = dirname(__DIR__);
        return self::$root;
    }

}