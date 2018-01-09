<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:49
 */
namespace Tricolor\Tracker\Test\Libs;
use Tricolor\Tracker\Carrier\HttpHeaders;
use Tricolor\Tracker\Tracer;

class Rpc
{
    public function exec($url, $params)
    {
        $headers = array();

        // ...
        Tracer::instance()
            ->log('Url', $url)
            ->log('Params', $params)
            ->tag('CallApi')
            ->run();

        Tracer::inject(new HttpHeaders($headers));

        $output = $this->curl($url, $params);

        Tracer::instance()
            ->log('Output', $output)
            ->tag('RecvApi')
            ->run();
    }

    public function curl($url, $params, $postOrGet = 'post')
    {
        return "";
    }
}