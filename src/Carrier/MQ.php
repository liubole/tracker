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
class MQ extends Base
{
    public function unpack(&$msg)
    {
        try {
            if (is_string($msg) && ($msgArr = @unserialize($msg)) && is_array($msgArr)) {
                if (isset($msgArr[Config::$carrierMQTraceKey]) && isset($msgArr[Config::$carrierMQDataKey])) {
                    Context::set($msgArr[Config::$carrierMQTraceKey]);
                    return $msg = $msgArr[Config::$carrierMQDataKey];
                }
            }
        } catch (\Exception $e) {
        }
        return $msg;
    }

    public function pack(&$msg)
    {
        return $msg = serialize(array(
            Config::$carrierMQTraceKey => Context::get(),
            Config::$carrierMQDataKey => $msg,
        ));
    }
}