<?php
namespace Tricolor\Tracker\Filter;
use Tricolor\Tracker\Config;
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
        $post = func_get_arg(0);
        return $post && is_array($post) && isset($post[Config::$carrierPostTraceKey]);
    }

    public function attach()
    {
        return isset(Context::$TraceId);
    }
}