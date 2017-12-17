<?php
namespace Tricolor\Tracker\Config;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/17 23:58
 */
class Attachment
{
    public static $attachServerHttpHost = true;
    public static $attachServerRequestUri = true;
    public static $attachServerQueryString = false;
    public static $attachSapiName = true;

    public static $attachMqRoutingKey = true;
    public static $attachMqConsumerTag = true;
}