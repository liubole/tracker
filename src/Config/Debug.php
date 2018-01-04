<?php
/**
 * User: Tricolor
 * Date: 2017/12/20
 * Time: 15:47
 */
namespace Tricolor\Tracker\Config;

class Debug
{
    const ERROR = 1;
    const WARNING = 2;
    const DEBUG = 3;
    const INFO = 4;

    public static $debug = false;
    public static $thresholds = array(Debug::ERROR, Debug::WARNING, Debug::DEBUG, Debug::INFO);
    public static $logRoot = array('Tricolor\Tracker\Common\Logger', 'logRoot');
}