<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Sampler\Attachment\RabbitMQ as MQAttach;
use \Tricolor\Tracker\Deliverer\RabbitMQHeaders as MQDeliverer;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:44
 */
class RabbitMQ
{
    public static function publish($message)
    {
        //
        $msg = new \PhpAmqpLib\Message\AMQPMessage($message);
        Trace::instance()
            ->addAttachments(new MQAttach($msg))
            ->delivery(new MQDeliverer($msg))
            ->tag('PubMsg')
            ->watch($message);
        //real publish
    }

    public static function subscribe($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            Trace::instance()
                ->addAttachments(new MQAttach($msgObj))
                ->delivery(new MQDeliverer($msgObj))
                ->tag('SubMsg')
                ->watch($msgObj->body);
            call_user_func($callback, $msgObj);
        };
        // do subscribe
    }
}