<?php
/**
 * User: Tricolor
 * Date: 2017/12/18
 * Time: 13:23
 */
namespace Tricolor\Tracker\Common;

class Time
{
    public static function get()
    {
        return microtime();
    }
}