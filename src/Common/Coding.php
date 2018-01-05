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
                case Collector::dataTypeJson:
                    return @json_encode($var);
                case Collector::dataTypeSerialize:
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
                case Collector::dataTypeJson:
                    return @json_decode($str, 1);
                case Collector::dataTypeSerialize:
                    return @unserialize($str);
                default:
                    break;
            }
        } catch (\Exception $e) {
        }
        return null;
    }
}