<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 15:17
 */
include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/config.php";

\Tricolor\Tracker\Collector\RabbitMQ::sub(function ($msg) {
    echo "[X] receive from [" . $msg->get('routing_key') . "], length [".strlen($msg->body)."] byte.\n";
});