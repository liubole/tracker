<?php
/**
 * User: Tricolor
 * Date: 2017/12/15
 * Time: 16:25
 */
namespace Tricolor\Tracker\Core;
use Tricolor\Tracker\Common\Coding;
use \Tricolor\Tracker\Config\Collector as CollectorConfig;
use Tricolor\Tracker\Config\TraceEnv;

class Collector
{
    public static function report($message)
    {
        $message = Coding::encode($message);
        if (CollectorConfig::$compress == TraceEnv::ON) {
            $message = gzdeflate($message);
        }
        if (isset(CollectorConfig::$reporter)) {
            return self::traceReport($message, CollectorConfig::$reporter, CollectorConfig::$reportParams);
        }
        return self::traceReport($message, array('Tricolor\Tracker\Collector\RabbitMQ', 'pub'), array());
    }

    private static function traceReport(&$message, $reporter, $reportParams)
    {
        if (!is_callable($reporter)) {
            return false;
        }
        $message = Coding::encode($message);
        $r_params = array();
        if ($reportParams) {
            $r_params = is_array($reportParams) ? $reportParams : array($reportParams);
        }
        // param type1: {reporterParams}
        foreach ($r_params as &$p) {
            if (is_string($p) && preg_match('/{[\w_\d]+}/', $p)) {
                $p = $message;
                return call_user_func_array($reporter, $r_params);
            }
        }
        // param type: append
        return call_user_func_array(
            $reporter,
            array_merge($r_params, array($message))
        );
    }
}