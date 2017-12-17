<?php
namespace Tricolor\Tracker\Sampler\Attachment;
use Tricolor\Tracker\Config\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/15 23:13
 */
class MQ implements Base
{
    private $msgObj;

    /**
     * MQ constructor.
     * @param $msgObj string|\PhpAmqpLib\Message\AMQPMessage
     */
    public function __construct(&$msgObj = null)
    {
        if (is_object($msgObj)) {
            $this->msgObj = $msgObj;
        }
    }

    public function getAll()
    {
        $attach = array();
        $define = new \ReflectionClass(Attachment::class);
        foreach ($define->getStaticProperties() as $name => $bool) {
            if (!$bool || !method_exists($this, $name = lcfirst($name))) continue;
            if ($res = $this->$name()) $attach = array_merge($attach, $res);
        }
        return $attach;
    }

    private function attachMqRoutingKey()
    {
        if ($this->msgObj) {
            return array(
                'routing_key' => $this->msgObj->get('routing_key'),
            );
        }
        return array();
    }

    private function attachMqConsumerTag()
    {
        if ($this->msgObj) {
            return array(
                'consumer_tag' => $this->msgObj->get('consumer_tag'),
            );
        }
        return array();
    }
}