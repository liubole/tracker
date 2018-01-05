<?php
/**
 * User: Tricolor
 * Date: 2017/12/13
 * Time: 11:23
 */
namespace Tricolor\Tracker\Config;

class Collector
{
    const dataTypeJson = 'json';
    const dataTypeSerialize = 'serialize';

    public static $reporter = null;
    // array(xx, ..., '{param}', xx), param: /{[\w_\d]+}/
    // or string: xx
    // or array: array(var1, var2, ...)
    public static $reportParams = null;
    // json or serialize
    public static $reportDataType = Collector::dataTypeJson;

    public static $compress = TraceEnv::OFF;
}