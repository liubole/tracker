<?php
/**
 * User: Tricolor
 * Date: 2017/12/18
 * Time: 15:47
 */
namespace Tricolor\Tracker\Common;
use Tricolor\Tracker\Config\Collector;

class Coding
{
    public static function encode($var)
    {
        try {
            switch (Collector::$collectDataType) {
                case Collector::json:
                    return @json_encode($var);
                case Collector::serialize:
                    return @serialize($var);
                default:
                    break;
            }
        } catch (\Exception $e) {
        }
        return null;
    }

    public static function decode($str)
    {
        try {
            switch (Collector::$collectDataType) {
                case Collector::json:
                    return @json_decode($str, 1);
                case Collector::serialize:
                    return @unserialize($str);
                default:
                    break;
            }
        } catch (\Exception $e) {
        }
        return null;
    }
}