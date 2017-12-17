<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Config\Deliverer;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 22:00
 */
class HttpPost implements Base
{
    private $post;

    public function __construct(&$var)
    {
        $this->post = $var;
    }

    public function unpack()
    {
        if (is_array($this->post) && isset($this->post[Deliverer::$deliverPostTraceKey])) {
            Context::set($this->post[Deliverer::$deliverPostTraceKey]);
            unset($this->post[Deliverer::$deliverPostTraceKey]);
            return true;
        }
        return false;
    }

    public function pack()
    {
        if ($this->post) {
            $this->post[Deliverer::$deliverPostTraceKey] = Context::get();
            return true;
        }
        return false;
    }
}