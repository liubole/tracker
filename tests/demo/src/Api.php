<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 21:21
 */
namespace Tricolor\Tracker\Demo;
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Collector;
use \Tricolor\Tracker\Deliverer\HttpHeaders;

class Api extends Controller
{
    public function __construct()
    {
        define('CLIENTID', 'Api');
        parent::__construct();

        Collector::$reporter = function ($info) {
            $call = array('\Tricolor\Tracker\Demo\Logger', 'write');
            call_user_func_array($call, array('./logs/', $info));
        };

        Trace::buildFrom(new HttpHeaders());

        Trace::instance()
            ->record('post', $_POST)
            ->record('get', $_GET)
            ->tag('ApiRecv')
            ->run();
    }

    public function doTouch()
    {
        $return = array();
        foreach ($_POST as $k => $v) {
            $return[$k] = $v . Utils::randStr('alpha', rand(100, 1000));
        }
        // use 100 - 1000ms
        usleep(rand(100 * 1000, 1000 * 1000));
        $this->output($return);
    }
}