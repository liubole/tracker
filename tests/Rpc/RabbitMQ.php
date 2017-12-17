<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Sampler\Attachment\MQ as MQAttach;
use \Tricolor\Tracker\Deliverer\MQ as MQDeliverer;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:44
 */
class RabbitMQ
{
    public static function publish($message)
    {
        Trace::instance()
            ->setAttach(new MQAttach())
            ->deliver(new MQDeliverer($message))
            ->watch();
        //real publish
    }

    public static function subscribe($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            Trace::instance()
                ->setAttach(new MQAttach($msgObj))
                ->deliver(new MQDeliverer($msgObj))
                ->watch();
            call_user_func($callback, $msgObj);
        };
        // do subscribe
    }
}