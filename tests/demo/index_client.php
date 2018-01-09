<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 22:08
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";

use Tricolor\Tracker\Common\Server;
use Tricolor\Tracker\Filter\Random;
use Tricolor\Tracker\Filter\Simple;
use \Tricolor\Tracker\Tracer;
use \Tricolor\Tracker\Config\Collector;

define('CLIENTID', 'Client');
//Collector::$collector = function ($info) {
//    $call = array('\Tricolor\Tracker\Demo\Logger', 'write');
//    call_user_func_array($call, array('./logs/', $info));
//};
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
Tracer::init();
Tracer::setContext('AtH', microtime());
Tracer::setContext('InAt', microtime());
Tracer::setContext('UsrIp', Server::getIp());
Tracer::setContext($s = 'T-a', $s);
Tracer::setContext($s = 'T_a', $s);
Tracer::setContext($s = 'a-b', $s);
Tracer::setContext($s = 'a_b', $s);
Tracer::setContext($s = 'a b', $s);
Tracer::logFilter(
    new Random(100),
    (new Simple())->deny($host, '/https?:\/\/filter\.tracker\.tricolor\.com/')
);
Tracer::instance()->tag('Init')->run();

$microtime = isset($_POST['microtime']) ? $_POST['microtime'] : '';
// client will do:
$client = new \Tricolor\Tracker\Demo\Client();
$client->touch();
