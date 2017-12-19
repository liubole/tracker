<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Sampler\Attachment\MQ as MQAttach;
use \Tricolor\Tracker\Deliverer\MQ as MQDeliverer;
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
        Trace::instance()
            ->addAttachments(new MQAttach())
            ->delivery(new MQDeliverer($message))
            ->tag('PubMsg')
            ->watch();
        //real publish
    }

    public static function subscribe($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            Trace::instance()
                ->addAttachments(new MQAttach($msgObj))
                ->delivery(new MQDeliverer($msgObj))
                ->tag('SubMsg')
                ->watch();
            call_user_func($callback, $msgObj);
        };
        // do subscribe
    }
}