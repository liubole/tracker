<?php
/**
 * User: Tricolor
 * Date: 2018/1/4
 * Time: 21:22
 */
namespace Tricolor\Tracker\Demo;
use Tricolor\Tracker\Carrier\HttpHeaders;
use Tricolor\Tracker\Tracer;

class Rpc
{
    private $api_uri = 'http://trace.tricolor.com/';
    private $sdk_info = 'PHP-SDK-Trace/0.1';

    public function exec($api, $params)
    {
        $url = $this->url(trim($api, '/'));
        $headers = array();
        Tracer::inject(new HttpHeaders($headers));
        Tracer::instance()
            ->log('Api', $api)
            ->log('Params', $params)
            ->tag($this->getIdentity() . 'CallApi')
            ->run();
        $response = $this->post($url, $params, $headers);
        Tracer::instance()
            ->log('Resp', $response)
            ->tag($this->getIdentity() . 'RecvApi')
            ->run();
        $output = json_decode($response, 1);
        return $output;
    }

    private function post($url, $params, $headers = array())
    {
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, $this->ua());
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ci, CURLOPT_TIMEOUT, 3);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ci, CURLOPT_HEADER, false);
        curl_setopt($ci, CURLOPT_POST, TRUE);
        curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        // network delay: 1-500ms
        usleep(rand(1 * 1000, 500 * 1000));
        $response = curl_exec($ci);
        curl_close($ci);
        return $response;
    }

    private function url($api)
    {
        return $this->api_uri . $api;
    }

    private function ua()
    {
        $UA = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        return str_replace(' ' . $this->sdk_info, '', $UA) . ' ' . $this->sdk_info;
    }

    private function getIdentity()
    {
        return defined('CLIENTID') ? CLIENTID : '';
    }
}