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
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Collector;

define('CLIENTID', 'Client');
//Collector::$collector = function ($info) {
//    $call = array('\Tricolor\Tracker\Demo\Logger', 'write');
//    call_user_func_array($call, array('./logs/', $info));
//};
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
Trace::init();
Trace::transport('AtH', microtime());
Trace::transport('InAt', microtime());
Trace::transport('UsrIp', Server::getIp());
Trace::transport($s = 'T-a', $s);
Trace::transport($s = 'T_a', $s);
Trace::transport($s = 'a-b', $s);
Trace::transport($s = 'a_b', $s);
Trace::transport($s = 'a b', $s);
Trace::recordFilter(
    new Random(100),
    (new Simple())->allow($host, '/https?:\/\/filter\.tracker\.tricolor\.com/')
);
Trace::instance()->tag('Init')->run();

$microtime = isset($_POST['microtime']) ? $_POST['microtime'] : '';
// client will do:
$client = new \Tricolor\Tracker\Demo\Client();
$client->touch();
