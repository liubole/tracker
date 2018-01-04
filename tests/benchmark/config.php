<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 17:00
 */
include_once __DIR__ . "/../config/rabbitmq.php";
\Tricolor\Tracker\Config\Collector::$reportDataType = \Tricolor\Tracker\Config\Collector::dataTypeJson;
\Tricolor\Tracker\Config\Collector\RabbitMQ::set($rabbiqmqconfig);