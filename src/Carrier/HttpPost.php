<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Config\Values;
use Tricolor\Tracker\Config\Define;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:00
 */
class HttpPost extends Base
{
    public function unpack(&$post)
    {
        if (is_array($post) && isset($post[Values::get(Define::carrierPostTraceKey)])) {
            Context::set($post[Values::get(Define::carrierPostTraceKey)]);
            unset($post[Values::get(Define::carrierPostTraceKey)]);
        }
        return $post;
    }

    public function pack(&$post)
    {
        $post[Values::get(Define::carrierPostTraceKey)] = Context::get();
        return $post;
    }
}