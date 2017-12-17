<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Config\Deliverer;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:00
 */
class MQ implements Base
{
    private $message;
    private $msgObj;

    /**
     * MQ constructor.
     * @param $var string|\PhpAmqpLib\Message\AMQPMessage
     */
    public function __construct(&$var)
    {
        if (is_object($var)) {
            $this->msgObj = $var;
        } else if (is_string($var)) {
            $this->message = $var;
        }
    }

    public function unpack()
    {
        try {
            if (!$this->msgObj || !($msg = $this->msgObj->body)) return false;
            if (!is_string($msg) || !($msgArr = @unserialize($msg)) || !is_array($msgArr)) return false;
            if (!isset($msgArr[Deliverer::$deliverMQTraceKey]) || !isset($msgArr[Deliverer::$deliverMQDataKey])) return false;
            Context::set($msgArr[Deliverer::$deliverMQTraceKey]);
            $this->msgObj->body = $msgArr[Deliverer::$deliverMQDataKey];
            return true;
        } catch (\Exception $e) {
        }
        return false;
    }

    public function pack()
    {
        if ($this->message) {
            $this->message = serialize(array(
                Deliverer::$deliverMQTraceKey => Context::get(),
                Deliverer::$deliverMQDataKey => $this->message,
            ));
            return true;
        }
        return false;
    }
}