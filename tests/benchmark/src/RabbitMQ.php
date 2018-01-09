<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:44
 */
namespace Tricolor\Tracker\Test\Libs;
use Tricolor\Tracker\Carrier\RabbitMQHeaders;
use Tricolor\Tracker\Tracer;

class RabbitMQ
{
    public static function publish($message)
    {
        //
        $msg = new \PhpAmqpLib\Message\AMQPMessage($message);

        Tracer::inject(new RabbitMQHeaders($msg));

        Tracer::instance()
            ->log('key', $message, function ($context) {
                return isset($context['ClosePubMsg']) && ($context['ClosePubMsg'] == 1);
            })
            ->tag('PubMsg')
            ->run();
        //real publish
    }

    public static function subscribe($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            Tracer::extract(new RabbitMQHeaders($msgObj));
//            Tracer::untrace('From');
//            Tracer::untraceAll('StoreId', 'From');
            Tracer::instance()
                ->log('msg', $msgObj->body, function ($context) {
                    return isset($context['CloseSubMsg']) && ($context['CloseSubMsg'] == 1);
                })
                ->tag('SubMsg')
                ->run();
            call_user_func($callback, $msgObj);
        };
        // do subscribe
    }
}