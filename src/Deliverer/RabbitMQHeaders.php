<?php
namespace Tricolor\Tracker\Deliverer;
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
        $headers = $this->msgObj->get('application_headers')->getNativeData();
        $trace = array();
        foreach (array_keys(Context::toArray()) as $key) {
            if (isset($headers[$this->prefix . $key]))
                $trace[$key] = $headers[$this->prefix . $key];
        }
        if ($trace) {
            Context::set($trace);
            return true;
        }
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
            return true;
        } catch (\Exception $e) {
        }
        return false;
    }
}