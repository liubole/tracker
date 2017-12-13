<?php
namespace Tricolor\Tracker\Reporter;
use Tricolor\Tracker\Config;
use Tricolor\Tracker\Context;
/**
 *
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/13
 * Time: 11:14
 *
 */
class MQ extends Base
{
    public function report()
    {
        if (Config::$mqReporter) {
            call_user_func(Config::$mqReporter, Config::$mqRoutingKey, Context::get());
        }
    }
}