<?php
/**
 *
 * User: Tricolor
 * Date: 2017/12/26
 * Time: 12:51
 */
include_once  __DIR__ . "/../../vendor/autoload.php";
use Tricolor\Tracker\Filter\Simple as SimpleFilter;

$_SERVER['HTTP_HOST'] = 'elephant.example.com';
$_SERVER['REQUEST_URI'] = '/user/get/name?user_id=1';
$_SERVER['SERVER_NAME'] = 'elephant.example.com';

$fFilter = new SimpleFilter($_SERVER);
$fFilter->allow($_SERVER['HTTP_HOST'], '/cat\.example\.com/');
//$fFilter->deny($_SERVER['REQUEST_URI'], '/user\/get\/name/');
$fFilter->allow((string)$_SERVER['SERVER_NAME'], '/dog\.example\.com/');
$fFilter->deny((string)$_SERVER['SERVER_NAME'], '/dog\.example\.com/');
$res = $fFilter->sample();
echo 'server filter, sample or not: ' . ($res ? 'yes' : 'no') . "\n";