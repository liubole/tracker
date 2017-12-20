<?php
namespace Tricolor\Tracker\Sampler\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/15 23:13
 */
class Server extends Attach implements Base
{
    protected $server;
    protected $callPrefix = 'server_';

    public function __construct(&$server = null)
    {
        if ($server) {
            $this->server = &$server;
        } else {
            $this->server = &$_SERVER;
        }
    }

    public function getAll()
    {
        return $this->get($this->callPrefix);
    }

    protected function server_httpHost()
    {
        return array(
            'host' => isset($this->server['HTTP_HOST']) ? $this->server['HTTP_HOST'] : '',
        );
    }

    protected function server_requestUri()
    {
        return array(
            'req' => isset($this->server['REQUEST_URI']) ? $this->server['REQUEST_URI'] : '',
        );
    }

    protected function server_queryString()
    {
        return array(
            'query' => isset($this->server['QUERY_STRING']) ? $this->server['QUERY_STRING'] : '',
        );
    }

    protected function server_sapiName()
    {
        return array(
            'sapi' => php_sapi_name(),
        );
    }

    protected function server_httpUa()
    {
        return array(
            'ua' => isset($this->server['HTTP_USER_AGENT']) ? $this->server['HTTP_USER_AGENT'] : '',
        );
    }

    protected function server_httpXForwardFor()
    {
        return array(
            'x_forward_for' => isset($this->server['HTTP_X_FORWARDED_FOR']) ? $this->server['HTTP_X_FORWARDED_FOR'] : '',
        );
    }

    protected function server_remoteAddr()
    {
        return array(
            'remote_addr' => isset($this->server['REMOTE_ADDR']) ? $this->server['REMOTE_ADDR'] : '',
        );
    }
}