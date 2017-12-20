<?php
namespace Tricolor\Tracker\Config;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 15:47
 */
class Debug
{
    const ERROR = 1;
    const WARNING = 2;
    const DEBUG = 3;
    const INFO = 4;

    public static $debug = false;
    public static $thresholds = array(Debug::ERROR, Debug::WARNING, Debug::DEBUG, Debug::INFO);
    public static $logRoot = array('Tricolor\Tracker\Common\Env', 'logRoot');
}