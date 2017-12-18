<?php
namespace Tricolor\Tracker\Common;
use Tricolor\Tracker\Config\Format;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 15:47
 */
class Coding
{
    public static function encode($var)
    {
        try {
            switch (Format::$codeType) {
                case Format::codeTypeJson:
                    return @json_encode($var);
                case Format::codeTypeSerialize:
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
            switch (Format::$codeType) {
                case Format::codeTypeJson:
                    return @json_decode($str, 1);
                case Format::codeTypeSerialize:
                    return @unserialize($str);
                default:
                    break;
            }
        } catch (\Exception $e) {
        }
        return null;
    }
}