<?php
namespace Tricolor\Tracker\Sampler\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/15 23:13
 */
class Server extends Attach implements Base
{
    private $server;
    private $callPrefix = 'server_';

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
        return parent::get($this, $this->callPrefix);
    }

    private function server_httpHost()
    {
        return array(
            'host' => isset($this->server['HTTP_HOST']) ? $this->server['HTTP_HOST'] : '',
        );
    }

    private function server_requestUri()
    {
        return array(
            'req' => isset($this->server['REQUEST_URI']) ? $this->server['REQUEST_URI'] : '',
        );
    }

    private function server_queryString()
    {
        return array(
            'query' => isset($this->server['QUERY_STRING']) ? $this->server['QUERY_STRING'] : '',
        );
    }

    private function server_sapiName()
    {
        return array(
            'sapi' => php_sapi_name(),
        );
    }

    private function server_httpUa()
    {
        return array(
            'ua' => isset($this->server['HTTP_USER_AGENT']) ? $this->server['HTTP_USER_AGENT'] : '',
        );
    }

    private function server_httpXForwardFor()
    {
        return array(
            'x_forward_for' => isset($this->server['HTTP_X_FORWARDED_FOR']) ? $this->server['HTTP_X_FORWARDED_FOR'] : '',
        );
    }

    private function server_remoteAddr()
    {
        return array(
            'remote_addr' => isset($this->server['REMOTE_ADDR']) ? $this->server['REMOTE_ADDR'] : '',
        );
    }
}