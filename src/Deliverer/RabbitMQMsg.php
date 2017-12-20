<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Common\Coding;
use Tricolor\Tracker\Common\Logger;
use Tricolor\Tracker\Config\Debug;
use Tricolor\Tracker\Config\Deliverer;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: Tricolor
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
            $this->msgObj = &$var;
        } else if (is_string($var)) {
            $this->message = &$var;
        }
    }

    public function unpack()
    {
        try {
            if (!$this->msgObj || !($msg = $this->msgObj->body)) {
                Logger::log(Debug::WARNING, __METHOD__ . ': unpack failed!');
                return false;
            }
            if (!is_string($msg) || !($msgArr = Coding::decode($msg)) || !is_array($msgArr)) {
                Logger::log(Debug::WARNING, __METHOD__ . ': unpack failed!');
                return false;
            }
            if (!isset($msgArr[Deliverer::$deliverMQTraceKey]) || !isset($msgArr[Deliverer::$deliverMQDataKey])) {
                Logger::log(Debug::WARNING, __METHOD__ . ': unpack failed!');
                return false;
            }
            Context::set($msgArr[Deliverer::$deliverMQTraceKey]);
            $this->msgObj->body = $msgArr[Deliverer::$deliverMQDataKey];
            Logger::log(Debug::INFO, __METHOD__ . ': unpack succeed!');
            return true;
        } catch (\Exception $e) {
        }
        Logger::log(Debug::WARNING, __METHOD__ . ': unpack failed!');
        return false;
    }

    public function pack()
    {
        if ($this->message) {
            $this->message = Coding::encode(array(
                Deliverer::$deliverMQTraceKey => Context::get(),
                Deliverer::$deliverMQDataKey => $this->message,
            ));
            Logger::log(Debug::WARNING, __METHOD__ . ': pack succeed!');
            return true;
        }
        Logger::log(Debug::WARNING, __METHOD__ . ': pack failed!');
        return false;
    }
}