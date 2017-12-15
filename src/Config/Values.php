<?php
namespace Tricolor\Tracker\Config;

/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/13
 * Time: 11:23
 *
 */
class Values
{
    private static $handler = null;

    private static $values = array(
        Define::mqReporter => null,
        Define::mqRoutingKey => null,
        Define::carrierPostTraceKey => '__trace__',
        Define::carrierMQTraceKey => '__trace__',
        Define::carrierMQDataKey => '__data__',
    );

    public static function get($key)
    {
        if (self::$handler && is_callable(self::$handler)) {
            return call_user_func(self::$handler, $key);
        }
        return self::$values[$key];
    }

    /**
     * you can overwrite the default config by:
     * Values::handler(array('YourClass', 'yourMethod'))
     * YourClass{
     *      yourMethod ($key) {
     *          return self::$key();
     *      }
     * }
     * @param $handler
     */
    public static function handler($handler)
    {
        self::$handler = $handler;
    }
}