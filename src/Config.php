<?php
namespace Tricolor\Tracker;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/13
 * Time: 11:23
 *
 */
class Config
{
    /**
     * deliver context through mq
     * @var array e.g. array('\namespace\Publisher', 'publishMethod')
     */
    public static $mqReporter;
    public static $mqRoutingKey;

    /**
     * write context into cache
     * @var
     */
    public static $cacheDir;

    /**
     * keep it default
     * @var string
     */
    public static $carrierPostTraceKey = '__trace__';
    public static $carrierMQTraceKey = '__trace__';
    public static $carrierMQDataKey = '__data__';
}