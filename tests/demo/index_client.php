<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 22:08
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";

// navigation to: index_client.php
$microtime = isset($_POST['microtime']) ? $_POST['microtime'] : '';
// client will do:
$client = new \Tricolor\Tracker\Demo\Client();
$client->touch();
