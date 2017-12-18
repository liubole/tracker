<?php
namespace Tricolor\Tracker\Common;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 13:23
 */
class Time
{
    public static function get()
    {
        return microtime();
    }
}