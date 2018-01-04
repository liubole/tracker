<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:44
 */
namespace Tricolor\Tracker\Test\Libs;
use Tricolor\Tracker\Deliverer\RabbitMQHeaders;
use \Tricolor\Tracker\Trace;

class RabbitMQ
{
    public static function publish($message)
    {
        //
        $msg = new \PhpAmqpLib\Message\AMQPMessage($message);

        Trace::transBy(new RabbitMQHeaders($msg));

        Trace::instance()
            ->record('key', $message, function ($context) {
                return isset($context['ClosePubMsg']) && ($context['ClosePubMsg'] == 1);
            })
            ->tag('PubMsg')
            ->run();
        //real publish
    }

    public static function subscribe($callback)
    {
        $realCallback = function ($msgObj) use ($callback) {
            Trace::buildFrom(new MQDeliverer($msgObj));
//            Trace::untrace('From');
//            Trace::untraceAll('StoreId', 'From');
            Trace::instance()
                ->record('msg', $msgObj->body, function ($context) {
                    return isset($context['CloseSubMsg']) && ($context['CloseSubMsg'] == 1);
                })
                ->tag('SubMsg')
                ->run();
            call_user_func($callback, $msgObj);
        };
        // do subscribe
    }
}