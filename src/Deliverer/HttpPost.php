<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Common\Logger;
use Tricolor\Tracker\Config\Debug;
use Tricolor\Tracker\Config\Deliverer;
use Tricolor\Tracker\Context;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 22:00
 */
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
        if (is_array($this->post) && isset($this->post[Deliverer::$deliverPostTraceKey])) {
            Context::set($this->post[Deliverer::$deliverPostTraceKey]);
            unset($this->post[Deliverer::$deliverPostTraceKey]);
            Logger::log(Debug::INFO, __METHOD__ . ': unpack succeed!');
            return true;
        }
        Logger::log(Debug::INFO, __METHOD__ . ': unpack failed!');
        return false;
    }

    public function pack()
    {
        isset($this->post) OR ($this->post = array());
        if (is_array($this->post)) {
            $this->post[Deliverer::$deliverPostTraceKey] = Context::get();
        } else {
            $this->post = rtrim($this->post, '&') . '&' .
                http_build_query(array(
                    Deliverer::$deliverPostTraceKey => Context::get()
                ));
        }
        return true;
    }
}