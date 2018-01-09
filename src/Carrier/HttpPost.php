<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 22:00
 */
namespace Tricolor\Tracker\Carrier;
use Tricolor\Tracker\Config\Carrier;
use Tricolor\Tracker\Core\Context;

class HttpPost implements Base
{
    private $post;

    public function __construct(&$var)
    {
        $this->post = &$var;
    }

    public function unpack()
    {
        isset($this->post) OR ($this->post = $_POST);
        if (is_array($this->post) && isset($this->post[Carrier::$traceKey])) {
            Context::set($this->post[Carrier::$traceKey]);
            unset($this->post[Carrier::$traceKey]);
            return true;
        }
        return false;
    }

    public function pack()
    {
        isset($this->post) OR ($this->post = array());
        if (is_array($this->post)) {
            $this->post[Carrier::$traceKey] = Context::toArray();
        } else {
            $this->post = rtrim($this->post, '&') . '&' .
                http_build_query(array(
                    Carrier::$traceKey => Context::toArray()
                ));
        }
        return true;
    }
}