<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Carrier\MQ as CarrierMQ;
use \Tricolor\Tracker\Filter\MQ as FilterMQ;
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
        $message = Trace::attach(new CarrierMQ(), new FilterMQ(), $message);
        //real publish
    }

    public static function subscribe($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            $msgObj->body = Trace::recv(new CarrierMQ(), new FilterMQ(), $msgObj->body);
            call_user_func($callback, $msgObj);
        };
        // do subscribe
    }
}