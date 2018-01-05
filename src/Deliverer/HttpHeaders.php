<?php
/**
 * User: Tricolor
 * Date: 2017/12/20
 * Time: 9:32
 */
namespace Tricolor\Tracker\Deliverer;
use Tricolor\Tracker\Common\Server;
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Core\Context;

class HttpHeaders implements Base
{
    private $prefix = 'Tr-';
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
        $headers = Server::getHeaders();
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

}