<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 17:18
 */
namespace Tricolor\Tracker\Common;

use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ
{
    public static function getRoutingKey($msg)
    {
        try {
            if ($msg && ($msg instanceof AMQPMessage)) {
                return $msg->get('routing_key');
            }
        } catch (\Exception $e) {
        }
        return '';
    }

    public static function getConsumerTag($msg)
    {
        try {
            if ($msg && ($msg instanceof AMQPMessage)) {
                return $msg->get('consumer_tag');
            }
        } catch (\Exception $e) {
        }
        return '';
    }
}