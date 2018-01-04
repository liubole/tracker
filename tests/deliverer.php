<?php
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 22:37
 */
include_once __DIR__ . "/../vendor/autoload.php";
use Tricolor\Tracker\Deliverer\HttpPost;

$params = array(
    'p1' => 123,
);
$postDeliver = new HttpPost($params);
var_dump($params);
$postDeliver->pack();
var_dump($params);