<?php
namespace Tricolor\Tracker\Common;
use Tricolor\Tracker\Config\Format;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 13:23
 */
class Time
{
    public static function get()
    {
        return microtime(Format::$timeAsFloat);
    }
}