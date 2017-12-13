<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Config;
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
        if (is_array($post) && !isset($post[Config::$carrierPostTraceKey])) {
            Context::set($post[Config::$carrierPostTraceKey]);
            unset($post[Config::$carrierPostTraceKey]);
        }
        return $post;
    }

    public function pack(&$post)
    {
        $post[Config::$carrierPostTraceKey] = Context::get();
        return $post;
    }
}