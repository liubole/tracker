<?php
/**
 * User: Tricolor
 * Date: 2017/12/15
 * Time: 16:25
 */
namespace Tricolor\Tracker\Core;
use Tricolor\Tracker\Collector\FileLog;
use Tricolor\Tracker\Collector\RabbitMQ;
use Tricolor\Tracker\Common\Coding;
use \Tricolor\Tracker\Config\Collector as CollectorConfig;
use Tricolor\Tracker\Config\TraceEnv;

class Collector
{
    /**
     * Collect data
     * @param $message
     * @return bool|mixed
     */
    public static function collect($message)
    {
        if (isset(CollectorConfig::$collector)) {
            return self::customized(self::logFormat($message), CollectorConfig::$collector);
        }
        switch (CollectorConfig::$defaultCollector) {
            case CollectorConfig::collectorRabbitMQ:
                return self::rabbitMq(self::logFormat($message));
            case CollectorConfig::collectorFile:
                return self::fileLog(self::logFormat($message));
        }
        return false;
    }

    /**
     * Collect data by file log
     * @param $message
     * @return bool
     */
    private static function fileLog(&$message)
    {
        try {
            FileLog::write($message);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Collect data by rabbitmq
     * @param $message
     * @return bool
     */
    private static function rabbitMq(&$message)
    {
        try {
            RabbitMQ::pub($message);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Perform a custom collection method
     * @param $message string
     * @param $collector callable
     * @return bool|mixed
     */
    private static function customized(&$message, $collector)
    {
        if (is_callable($collector)) {
            return call_user_func($collector, $message);
        }
        return false;
    }

    /**
     * Encode and compress message
     * @param $message
     * @return null|string
     */
    private static function logFormat(&$message)
    {
        $message = Coding::encode($message);
        if (CollectorConfig::$compress == TraceEnv::ON) {
            $message = gzdeflate($message);
        }
        return $message;
    }
}