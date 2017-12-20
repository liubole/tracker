<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Common\Logger;
use Tricolor\Tracker\Config\Debug;
use Tricolor\Tracker\Context;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 9:33
 */
class RabbitMQHeaders implements Base
{
    private $prefix = 'Trace-';
    private $msgObj;

    /**
     * RabbitMQHeaders constructor.
     * @param $msgObj \PhpAmqpLib\Message\AMQPMessage
     */
    public function __construct(&$msgObj)
    {
        $this->msgObj = &$msgObj;
    }

    /**
     * @return bool
     */
    public function unpack()
    {
        if (!is_object($this->msgObj)) return false;
        try {
            $headers = $this->msgObj->get('application_headers')->getNativeData();
            $trace = array();
            foreach (array_keys(Context::toArray()) as $key) {
                if (isset($headers[$this->prefix . $key]))
                    $trace[$key] = $headers[$this->prefix . $key];
            }
            if ($trace) {
                Context::set($trace);
                Logger::log(Debug::INFO, __METHOD__ . ': unpack succeed!');
                return true;
            }
        } catch (\Exception $e) {
            Logger::log(Debug::INFO, __METHOD__ . ': unpack exception : ' . $e->getMessage());
        }
        Logger::log(Debug::INFO, __METHOD__ . ': unpack failed!');
        return false;
    }

    /**
     * @return bool
     */
    public function pack()
    {
        if (!is_object($this->msgObj)) return false;
        try {
            $headers = $this->msgObj->get('application_headers')->getNativeData();
            if (!$headers) $headers = array();
            foreach (Context::toArray() as $k => $v) {
                $headers[$this->prefix . $k] = $v;
            }
            $this->msgObj->set('application_headers', $headers);
            Logger::log(Debug::INFO, __METHOD__ . ': pack succeed!');
            return true;
        } catch (\Exception $e) {
            Logger::log(Debug::INFO, __METHOD__ . ': pack exception : ' . $e->getMessage());
        }
        Logger::log(Debug::INFO, __METHOD__ . ': pack failed!');
        return false;
    }
}