<?php
/**
 * User: Tricolor
 * Date: 2017/12/20
 * Time: 9:32
 */
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Core\Context;

class HttpHeaders implements Base
{
    private $prefix = 'Trace-';// [A-Z][\w\-\d]+
    private $headers;

    public function __construct(&$headers = null)
    {
        $this->headers = &$headers;
    }

    /**
     * @return bool
     */
    public function unpack()
    {
        $headers = $this->getHeaders();
        $trace = array();
        foreach ($headers as $key => $val) {
            if (StrUtils::startsWith($key, $this->prefix)) {
                $trace[substr($key, strlen($this->prefix))] = $val;
            }
        }
        if ($trace) {
            Context::set($trace);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function pack()
    {
        isset($this->headers) OR ($this->headers = array());
        foreach (Context::toArray() as $k => $v) {
            $this->headers[] = $this->prefix . $k . ': ' . $v;
        }
        return true;
    }

    private function getHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}