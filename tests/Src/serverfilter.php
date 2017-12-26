<?php
/**
 *
 * User: Tricolor
 * Date: 2017/12/26
 * Time: 12:51
 */
include_once  __DIR__ . "/../../vendor/autoload.php";
use Tricolor\Tracker\Sampler\Filter\Server as ServerFilter;

$_SERVER['HTTP_HOST'] = 'elephant.example.com';
$_SERVER['REQUEST_URI'] = '/user/get/name?user_id=1';
$_SERVER['SERVER_NAME'] = 'elephant.example.com';

$serverFilter = new ServerFilter($_SERVER);
$serverFilter->allow($_SERVER['HTTP_HOST'], '/cat\.example\.com/');
//$serverFilter->deny($_SERVER['REQUEST_URI'], '/user\/get\/name/');
$serverFilter->allow((string)$_SERVER['SERVER_NAME'], '/dog\.example\.com/');
$serverFilter->deny((string)$_SERVER['SERVER_NAME'], '/dog\.example\.com/');
$res = $serverFilter->sample();
echo 'server filter, sample or not: ' . ($res ? 'yes' : 'no') . "\n";