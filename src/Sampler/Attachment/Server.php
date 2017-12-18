<?php
namespace Tricolor\Tracker\Sampler\Attachment;
use Tricolor\Tracker\Config\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/15 23:13
 */
class Server implements Base
{
    private $server;
    
    public function __construct(&$server = null)
    {
        $this->server = $server ? $server : $_SERVER;
    }

    public function getAll()
    {
        $attach = array();
        $define = new \ReflectionClass('\Tricolor\Tracker\Config\Attachment');
        foreach ($define->getStaticProperties() as $name => $bool) {
            if (!$bool || !method_exists($this, $name = lcfirst($name))) continue;
            if ($res = $this->$name()) $attach = array_merge($attach, $res);
        }
        return $attach;
    }

    private function attachServerHttpHost()
    {
        return array(
            'host' => isset($this->server['HTTP_HOST']) ? $this->server['HTTP_HOST'] : '',
        );
    }

    private function attachServerRequestUri()
    {
        return array(
            'req' => isset($this->server['REQUEST_URI']) ? $this->server['REQUEST_URI'] : '',
        );
    }

    private function attachServerQueryString()
    {
        return array(
            'query' => isset($this->server['QUERY_STRING']) ? $this->server['QUERY_STRING'] : '',
        );
    }

    private function attachSapiName()
    {
        return array(
            'sapi' => php_sapi_name(),
        );
    }
}