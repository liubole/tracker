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
    private $use_server;

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
            foreach ($this->possibleKeys($key) as $possible) {
                if (isset($headers[$possible])) {
                    $trace[$key] = $headers[$possible];
                    break;
                }
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

    private function possibleKeys($key)
    {
        if ($this->use_server) {
            return array(
                $this->prefix . $key,
                'HTTP_' . strtoupper(str_replace('-', '_', $this->prefix . $key))
            );
        }
        return array(
            $this->prefix . $key,
            'HTTP_' . strtoupper(str_replace('-', '_', $this->prefix . $key))
        );
    }

    private function getHeaders()
    {
        if (version_compare(PHP_VERSION, '5.5.7', '>=')) {
            $headers = getallheaders();
        } else {
            $headers = $_SERVER;
            $this->use_server = true;
        }
        return $headers;
    }
}