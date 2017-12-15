<?php
namespace Tricolor\Tracker\Filter;
use Tricolor\Tracker\Config\Values;
use Tricolor\Tracker\Config\Define;
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
        return rand(1, 100) <= Values::get(Define::samplingRate);
    }

    public function recv()
    {
        $post = $_POST;
        return $post && is_array($post) && isset($post[Values::get(Define::carrierPostTraceKey)]);
    }

    public function attach()
    {
        return isset(Context::$TraceId);
    }
}