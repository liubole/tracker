<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:00
 */
class RabbitMQ extends Base
{
    private static $key = '__trace__';
    private static $dataKey = 'MQData';

    public function unpack(&$msg)
    {
        if (is_string($msg) && ($msgArr = unserialize($msg)) && is_array($msgArr)) {
            if (isset($msgArr[RabbitMQ::$key]) && isset($msgArr[RabbitMQ::$dataKey])) {
                Context::set($msgArr[RabbitMQ::$key]);
                return $msgArr[RabbitMQ::$dataKey];
            }
        }
        return $msg;
    }

    public function pack(&$msg)
    {
        return serialize(array(
            RabbitMQ::$key => Context::get(),
            RabbitMQ::$dataKey => $msg,
        ));
    }
}