<?php
namespace Tricolor\Tracker\Sampler\Attachment;
use Tricolor\Tracker\Config\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/15 23:13
 */
class RabbitMQ extends Attach implements Base
{
    private $msgObj;
    private $callPrefix = 'mq_';

    /**
     * MQ constructor.
     * @param $msgObj string|\PhpAmqpLib\Message\AMQPMessage
     */
    public function __construct(&$msgObj = null)
    {
        if (is_object($msgObj)) {
            $this->msgObj = &$msgObj;
        }
    }

    public function getAll()
    {
        return $this->get($this->callPrefix);
    }

    protected function mq_routingKey()
    {
        if ($this->msgObj) {
            try {
                return array('routing_key' => $this->msgObj->get('routing_key'),);
            } catch (\Exception $e) {
                return array('routing_key' => '');
            }
        }
        return array();
    }

    protected function mq_consumerTag()
    {
        if ($this->msgObj) {
            try {
                return array('consumer_tag' => $this->msgObj->get('consumer_tag'));
            } catch (\Exception $e) {
                return array('consumer_tag' => '');
            }
        }
        return array();
    }
}