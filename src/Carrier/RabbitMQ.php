<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Config;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:00
 */
class RabbitMQ extends Base
{
    public function unpack(&$msg)
    {
        if (is_string($msg) && ($msgArr = unserialize($msg)) && is_array($msgArr)) {
            if (isset($msgArr[Config::$carrierMQTraceKey]) && isset($msgArr[Config::$carrierMQDataKey])) {
                Context::set($msgArr[Config::$carrierMQTraceKey]);
                return $msgArr[Config::$carrierMQDataKey];
            }
        }
        return $msg;
    }

    public function pack(&$msg)
    {
        return serialize(array(
            Config::$carrierMQTraceKey => Context::get(),
            Config::$carrierMQDataKey => $msg,
        ));
    }
}