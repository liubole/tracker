<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Config\Values;
use Tricolor\Tracker\Config\Define;
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
                if (isset($msgArr[Values::get(Define::carrierMQTraceKey)]) && isset($msgArr[Values::get(Define::carrierMQDataKey)])) {
                    Context::set($msgArr[Values::get(Define::carrierMQTraceKey)]);
                    return $msg = $msgArr[Values::get(Define::carrierMQDataKey)];
                }
            }
        } catch (\Exception $e) {
        }
        return $msg;
    }

    public function pack(&$msg)
    {
        return $msg = serialize(array(
            Values::get(Define::carrierMQTraceKey) => Context::get(),
            Values::get(Define::carrierMQDataKey) => $msg,
        ));
    }
}