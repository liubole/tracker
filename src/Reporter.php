<?php
namespace Tricolor\Tracker;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * User: Tricolor
 * DateTime: 2017/12/15 16:25
 */
use Tricolor\Tracker\Common\Coding;
use \Tricolor\Tracker\Config\Reporter as ReporterConfig;
class Reporter
{
    public static function report($message)
    {
        if (!is_callable(ReporterConfig::$reporter)) {
            return false;
        }
        $message = Coding::encode($message);
        $r_params = array();
        if (ReporterConfig::$reportParams) {
            $r_params = is_array(ReporterConfig::$reportParams) ?
                ReporterConfig::$reportParams : array(ReporterConfig::$reportParams);
        }
        // param type1: {reporterParams}
        foreach ($r_params as &$p) {
            if (is_string($p) && preg_match('/{[\w_\d]+}/', $p)) {
                $p = $message;
                return call_user_func_array(ReporterConfig::$reporter, $r_params);
            }
        }
        // param type: append
        return call_user_func_array(
            ReporterConfig::$reporter,
            array_merge($r_params, array($message))
        );
    }
}