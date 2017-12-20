<?php
namespace Tricolor\Tracker\Config;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/17 23:58
 */
class Attachment
{
    public static $httpHost = true;
    public static $requestUri = true;
    public static $queryString = false;
    public static $sapiName = true;
    public static $httpUa = true;
    public static $httpXForwardFor = true;
    public static $remoteAddr = true;

    public static $routingKey = true;
    public static $consumerTag = true;
}