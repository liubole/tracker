<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:49
 */
namespace Tricolor\Tracker\Test\Libs;
use Tricolor\Tracker\Deliverer\HttpHeaders;
use Tricolor\Tracker\Trace;

class Rpc
{
    public function exec($url, $params)
    {
        $headers = array();

        // ...
        Trace::instance()
            ->record('Url', $url)
            ->record('Params', $params)
            ->tag('CallApi')
            ->run();

        Trace::transBy(new HttpHeaders($headers));

        $output = $this->curl($url, $params);

        Trace::instance()
            ->record('Output', $output)
            ->tag('RecvApi')
            ->run();
    }

    public function curl($url, $params, $postOrGet = 'post')
    {
        return "";
    }
}