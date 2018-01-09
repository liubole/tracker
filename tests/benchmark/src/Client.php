<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:43
 */
namespace Tricolor\Tracker\Test\Libs;
use Tricolor\Tracker\Filter\Random;
use Tricolor\Tracker\Filter\Simple;
use \Tricolor\Tracker\Tracer;
use \Tricolor\Tracker\Config\Collector;
use Tricolor\Tracker\Filter\Random as RandomFilter;

class Client
{
    public function __construct()
    {
        Tracer::logFilter(
            new Random(100),
            new Simple($_SERVER['HTTP_HOST'], '/https?:\/\/www\.ci123\.com/', false)
        );

        Collector::$collector = function ($info) {
            $call = array('\Tricolor\RabbitMQ\Publisher', 'pubLog');
            if (is_callable($call)) {
                call_user_func_array($call, array('log.trace', $info, 8));
            }
        };

        Tracer::init();

        Tracer::instance()
            ->tag('Init')
            ->run();
    }

    public function output($output)
    {
        Tracer::instance()
            ->tag('Return')
            ->log('output', $output)
            ->run();
        echo json_encode($output);
        die();
    }
}

