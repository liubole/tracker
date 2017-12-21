<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Common\Logger;
use Tricolor\Tracker\Config\Debug;
use Tricolor\Tracker\Context;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 9:32
 */
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
        foreach (array_keys(Context::toArray()) as $key) {
            if (isset($headers[$this->prefix . $key])) {
                $trace[$key] = $headers[$this->prefix . $key];
                break;
            }
        }
        if ($trace) {
            Context::set($trace);
            Logger::log(Debug::INFO, __METHOD__ . ': unpack succeed!');
            return true;
        }
        Logger::log(Debug::WARNING, __METHOD__ . ': unpack failed!');
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