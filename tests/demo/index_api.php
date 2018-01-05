<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 22:32
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";

// navigation to: index_client.php
// client will do:
$api = new \Tricolor\Tracker\Demo\Api();
$api->doTouch();