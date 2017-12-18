<?php
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 20:34
 */

include_once  __DIR__ . "/../../vendor/autoload.php";
use Tricolor\Tracker\Sampler\Filter\Random;
use Tricolor\Tracker\Sampler\Filter\UriFilter;
use Tricolor\Tracker\Sampler\Filter\Ip;

$_SERVER['HTTP_CLIENT_IP'] = '192.168.3.240';
//var_dump(\Tricolor\Tracker\Common\Ip::getIp());
$ipFilter = new Ip();
//$ipFilter->onlyIp('/^192/');
$ipFilter->excludeIp('/^168/');
$res = $ipFilter->sample();
var_dump('ip filter, sample or not: ' . ($res ? 'yes' : 'no'));

$_SERVER['REQUEST_URI'] = '/user/get/name?rnd=1828312';
$uriFilter = new UriFilter($_SERVER);
$uriFilter->excludeReq('/\/user\/get\/name/');
$uriFilter->excludeReq('/\/user\/get\/uname/');
$res = $uriFilter->sample();
var_dump("uri filter, sample or not: " . ($res ? 'yes' : 'no'));

$randomFilter = new Random();
$rate = 50;
$base = 1000000;
for ($suc = 0, $i = 0; $i < $base; $i++) {
    $randomFilter->sample($rate) AND ($suc++);
}
$range = 100 * abs(100 * $suc / $base - $rate) / $rate;
var_dump("random filter, range: " . $range . "% (the smaller the better)");
