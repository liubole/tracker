<?php
namespace Tricolor\Tracker\Config;
/**
 *
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/15
 * Date: 9:45
 */
class Define
{
    /**
     * deliver context through mq
     * array e.g. array('\namespace\Publisher', 'publishMethod')
     */
    const mqReporter = 'mqReporter';
    const mqRoutingKey = 'mqRoutingKey';

    /**
     * you can keep it default
     */
    const carrierPostTraceKey = 'carrierPostTraceKey';
    const carrierMQTraceKey = 'carrierMQTraceKey';
    const carrierMQDataKey = 'carrierMQDataKey';
}