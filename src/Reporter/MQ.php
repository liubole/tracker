<?php
namespace Tricolor\Tracker\Reporter;
use Tricolor\Tracker\Config\Values;
use Tricolor\Tracker\Config\Define;
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
        if (Values::get(Define::mqReporter)) {
            call_user_func(Values::get(Define::mqReporter), Values::get(Define::mqRoutingKey), Context::get());
        }
    }
}