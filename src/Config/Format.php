<?php
namespace Tricolor\Tracker\Config;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 15:48
 */
class Format
{
    const codeTypeJson = 'json';
    const codeTypeSerialize = 'serialize';

    public static $codeType = Format::codeTypeSerialize;

    public static $timeAsFloat = false;
}