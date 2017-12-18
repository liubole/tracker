<?php
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/12/10
 * Time: 9:57
 */
include_once  __DIR__ . "/../vendor/autoload.php";

use \Tricolor\Tracker\Common\StrUtils;
use \Tricolor\Tracker\Common\UID;

//var_dump($graph);
//branch 向右
//tail 向下
// 坐标
// 横坐标为时间
// 纵坐标为层级
// 点[{x:x,y:x}, {x:x,y:y}, ...]
date_default_timezone_set("asia/shanghai");
header("Content-Type: text/event-stream\n\n");

function draw($points, $traceId, $startAt)
{
    echo "event: begin\n";
    echo "data: " . json_encode(array('traceId' => $traceId, 'startAt' => $startAt)) . "\n";
    echo "retry: 10000\n";
    echo "\n\n";
    ob_flush();
    flush();

    foreach ($points as $point) {
        echo "event: draw\n";
        echo "id: {$point['TraceId']}\n";
        echo 'data: ' . json_encode($point) . "\n";
        echo "retry: 10000\n";
        echo "\n\n";
    }
    ob_flush();
    flush();

    echo "event: end\n";
    echo "data: " . json_encode(array('traceId' => $traceId)) . "\n";
    echo "retry: 10000\n";
    echo "\n\n";
    ob_flush();
    flush();
}

$count = 0;
while(1) {
    $TraceId = UID::create();

    $files = array(
        array(
            'TraceId' => $TraceId,
            'RpcId' => '0',
            'At' => microtime(true),
            'TAG' => 'Init',
        ),
        array(
            'TraceId' => $TraceId,
            'RpcId' => '1',
            'At' => microtime(true),
            'TAG' => 'Mysql',
        ),
        array(
            'TraceId' => $TraceId,
            'RpcId' => '1.0',
            'At' => microtime(true),
            'TAG' => 'Init',
        ),
        array(
            'TraceId' => $TraceId,
            'RpcId' => '2',
            'At' => microtime(true),
            'TAG' => 'Init',
        ),
        array(
            'TraceId' => $TraceId,
            'RpcId' => '4',
            'At' => microtime(true),
            'TAG' => 'End',
        ),
    );
    $contexts = array();
    foreach ($files as $f) {
        $f['At'] = microtime(true);
        usleep(rand(100, 300000));
        $contexts[(string)$f['RpcId']] = $f;
    }
    //var_dump($contexts);
    list($points, $graph) = StrUtils::graph($contexts, $startAt, $traceId);
    draw($points, $traceId, $startAt);
    usleep(rand(800, 10000));
    $count++;
    if ($count % 5 === 0) {
        exit;
    }
}

