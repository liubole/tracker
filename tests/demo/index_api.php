<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 22:32
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";

use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Collector;
use \Tricolor\Tracker\Deliverer\HttpHeaders;

define('CLIENTID', 'Api');

//Collector::$collector = function ($info) {
//    $call = array('\Tricolor\Tracker\Demo\Logger', 'write');
//    call_user_func_array($call, array('./logs/', $info));
//};
Trace::buildFrom(new HttpHeaders());
Trace::instance()
    ->record('post', $_POST)
    ->record('get', $_GET)
    ->tag('ApiRecv')
    ->run();

$api = new \Tricolor\Tracker\Demo\Api();
$api->doTouch();

