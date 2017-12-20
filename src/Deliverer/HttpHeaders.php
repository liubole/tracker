<?php
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Context;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 9:32
 */
class HttpHeaders implements Base
{
    private $prefix = 'Trace-';
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
        if (version_compare(PHP_VERSION, '5.5.7', '>=')) {
            $headers = getallheaders();
        } else {
            $headers = $_SERVER;
        }
        $trace = array();
        foreach (array_keys(Context::toArray()) as $key) {
            if (isset($headers[$this->prefix . $key]))
                $trace[$key] = $headers[$this->prefix . $key];
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
            $this->headers[$this->prefix . $k] = $v;
        }
        return true;
    }
}