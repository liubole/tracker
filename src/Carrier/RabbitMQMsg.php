<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 22:00
 */
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Common\Coding;
use Tricolor\Tracker\Common\Logger;
use Tricolor\Tracker\Config\Debug;
use Tricolor\Tracker\Config\Carrier;
use Tricolor\Tracker\Core\Context;

class RabbitMQMsg implements Base
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
            $this->msgObj = &$var;
        } else if (is_string($var)) {
            $this->message = &$var;
        }
    }

    public function unpack()
    {
        try {
            if (!$this->msgObj || !($msg = $this->msgObj->body)) {
                return false;
            }
            if (!is_string($msg) || !($msgArr = Coding::decode($msg)) || !is_array($msgArr)) {
                return false;
            }
            if (!isset($msgArr[Carrier::$traceKey]) || !isset($msgArr[Carrier::$dataKey])) {
                return false;
            }
            Context::set($msgArr[Carrier::$traceKey]);
            $this->msgObj->body = $msgArr[Carrier::$dataKey];
            return true;
        } catch (\Exception $e) {
            Logger::log(Debug::ERROR, __METHOD__ . ': exception :' . $e->getMessage());
        }
        return false;
    }

    public function pack()
    {
        if ($this->message) {
            $this->message = Coding::encode(array(
                Carrier::$traceKey => Context::toArray(),
                Carrier::$dataKey => $this->message,
            ));
            Logger::log(Debug::WARNING, __METHOD__ . ': pack succeed!');
            return true;
        }
        return false;
    }
}