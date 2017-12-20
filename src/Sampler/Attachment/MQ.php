<?php
namespace Tricolor\Tracker\Sampler\Attachment;
use Tricolor\Tracker\Config\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/15 23:13
 */
class MQ extends Attach implements Base
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
        return parent::get($this, $this->callPrefix);
    }

    private function mq_routingKey()
    {
        if ($this->msgObj) {
            return array(
                'routing_key' => $this->msgObj->get('routing_key'),
            );
        }
        return array();
    }

    private function mq_consumerTag()
    {
        if ($this->msgObj) {
            return array(
                'consumer_tag' => $this->msgObj->get('consumer_tag'),
            );
        }
        return array();
    }
}