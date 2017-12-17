<?php
namespace Tricolor\Tracker\Config;

/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/13
 * Time: 11:23
 *
 */
class Reporter
{
    public static $reporter = null;
    // array(xx, ..., '{param}', xx), param: /{[\w_\d]+}/
    // or string: xx
    // or array: array(var1, var2, ...)
    public static $reportParams = null;
}