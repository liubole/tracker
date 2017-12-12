<?php
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:00
 */
class HttpPost extends Base
{
    private static $key = '__trace__';

    public function unpack(&$post)
    {
        Context::set($post[HttpPost::$key]);
        unset($post[HttpPost::$key]);
        return $post;
    }

    public function pack(&$post)
    {
        $post[HttpPost::$key] = Context::get();
        return $post;
    }
}