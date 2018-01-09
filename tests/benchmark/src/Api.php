<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:43
 */
namespace Tricolor\Tracker\Test\Libs;
use \Tricolor\Tracker\Tracer;
use \Tricolor\Tracker\Config\Collector;
use \Tricolor\Tracker\Carrier\HttpHeaders;

class Api
{
    public function __construct()
    {
        Collector::$collector = function ($message) {
            call_user_func(array('\Tricolor\RabbitMQ\Publisher', 'pubLog'), 'log.trace', $message, 8);
        };

        Tracer::extract(new HttpHeaders());

        Tracer::instance()
            ->log('post', $_POST)
            ->log('get', $_GET)
            ->tag('ApiRecv')
            ->run();
    }

    public function output($output)
    {
        Tracer::instance()
            ->log('output', $output)
            ->tag('ApiReturn')
            ->run();
        echo json_encode($output);
        die();
    }
}