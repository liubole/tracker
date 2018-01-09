<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 17:00
 */
include_once __DIR__ . "/../config/rabbitmq.php";
\Tricolor\Tracker\Config\Collector::$collectDataType = \Tricolor\Tracker\Config\Collector::json;
\Tricolor\Tracker\Config\Collector\RabbitMQ::set($rabbiqmqconfig);