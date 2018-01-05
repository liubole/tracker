<?php
/**
 * User: Tricolor
 * Date: 2018/1/5
 * Time: 10:17
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/functions.php";
use \Tricolor\Tracker\Config\Collector;
use \Tricolor\Tracker\Config\TraceEnv;

for($i = 1; $i <= 100; $i++) {
    $url = 'http://client.tricolor.com/index_client.php';
    echo "call ".str_pad($i, 3) ." >>";
    $res = curl($url, randParams(), randUa(), array(), $status);
    echo "\tstatus: $status, end!\n";
    break;
}

$hf = fopen(__DIR__.'/logs/20180105.log', 'rb');
$line = fgets($hf);
switch (Collector::$compress) {
    case TraceEnv::ON:
        $line = gzinflate($line);
        break;
}
switch (Collector::$reportDataType) {
    case Collector::dataTypeSerialize:
        $line = unserialize($line);
        break;
    case Collector::dataTypeJson:
        $line = json_decode($line, 1);
        break;
}
var_dump($line);
die();