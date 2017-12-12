<?php
namespace Tricolor\Tracker\Filter;
use Tricolor\Tracker\Context;

/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/12/9
 * Time: 16:58
 */
class Common extends Base
{
    public function init()
    {
        return rand(1, 5) === 1;
    }

    public function recv()
    {
        return isset(Context::$TraceId);
    }

    public function attach()
    {
        return true;
    }
}