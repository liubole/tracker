<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:43
 */
namespace Tricolor\Tracker\Test\Libs;
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Collector;
use \Tricolor\Tracker\Deliverer\HttpHeaders;

class Api
{
    public function __construct()
    {
        Collector::$reporter = array('\Tricolor\RabbitMQ\Publisher', 'pubLog');
        Collector::$reportParams = array('log.trace', '{param}', 8);

        Trace::buildFrom(new HttpHeaders());

        Trace::instance()
            ->record('post', $_POST)
            ->record('get', $_GET)
            ->tag('ApiRecv')
            ->run();
    }

    public function output($output)
    {
        Trace::instance()
            ->record('output', $output)
            ->tag('ApiReturn')
            ->run();
        echo json_encode($output);
        die();
    }
}