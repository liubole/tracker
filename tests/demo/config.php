<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 21:22
 */
ini_set('date.timezone', 'asia/shanghai');
include_once __DIR__ . "/../config/rabbitmq.php";
use \Tricolor\Tracker\Config\Collector;
use \Tricolor\Tracker\Config\TraceEnv;
use \Tricolor\Tracker\Config\Collector\RabbitMQ;

Collector::$compress = TraceEnv::ON;
Collector::$reportDataType = Collector::dataTypeSerialize;
RabbitMQ::set($rabbiqmqconfig);