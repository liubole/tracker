<?php
/**
 * User: Tricolor
 * Date: 2017/12/13
 * Time: 11:23
 */
namespace Tricolor\Tracker\Config;

class Collector
{
    const json = 'json';
    const serialize = 'serialize';

    const collectorRabbitMQ = 'rabbitmq';
    const collectorFile = 'file';

    /**
     * Record collector
     * @var null|callable|string<rabbitmq/filelog>
     */
    public static $collector = null;
    /**
     * Record collector
     * @var string
     */
    public static $defaultCollector = Collector::collectorFile;
    /**
     * Log format: 'json' or 'serialize'
     * @var string
     */
    public static $collectDataType = Collector::json;

    /**
     * Compress log?
     * @var int
     */
    public static $compress = TraceEnv::OFF;
}