<?php
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:44
 */
class Rabbitmq
{
    public static function publish($msg)
    {
        if (class_exists('\Tricolor\Tracker\Trace'))
            $msg = \Tricolor\Tracker\Trace::attach(new \Tricolor\Tracker\Carrier\RabbitMQ(), new \Tricolor\Tracker\Filter\Common(), $msg);
        //real publish
    }

    public static function subscrise($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            if (class_exists('\Tricolor\Tracker\Trace'))
                $msgObj->body = \Tricolor\Tracker\Trace::recv(new \Tricolor\Tracker\Carrier\RabbitMQ(), new \Tricolor\Tracker\Filter\Common(), $msgObj->body);
            //real callback
            call_user_func($callback, $msgObj);
        };
    }

    public static function pubTrack($msg)
    {
        //real publish
    }

    public static function subTrack($callback)
    {
        //real sub
    }
}