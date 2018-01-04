<?php
/**
 * User: Tricolor
 * Date: 2017/12/30
 * Time: 15:25
 */
namespace Tricolor\Tracker\Config\Collector;

class RabbitMQ
{
    public static $exchange = 'tracker';
    public static $queue = 'trace';
    public static $key = 'tracker.trace.log';

    private static $config = array(
        'host' => 'localhost',// Your Host     (default: localhost)
        'port' => 5672,// Your Port     (default: 5672)
        'user' => 'guest',// Your User     (default: guest)
        'password' => 'guest',// Your Password (default: guest)
        'vhost' => '/',// Your Vhost    (default: /)
        'insist' => false,
        'login_method' => 'AMQPLAIN',
        'login_response' => null,
        'locale' => 'en_US',
        'connection_timeout' => 3,
        'read_write_timeout' => 3,
        'context' => null,
        'keepalive' => false,
        'heartbeat' => 0,
    );

    public static function get()
    {
        return self::$config;
    }

    public static function set($config)
    {
        if (is_array($config)) {
            self::$config = array_merge(self::$config, $config);
        }
    }
}