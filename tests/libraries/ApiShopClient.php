<?php
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:49
 */
class ApiShopClient
{
    public function exec($url, $params)
    {
        // ...
        if (class_exists('\Tricolor\Tracker\Trace'))
            $params = \Tricolor\Tracker\Trace::attach(new \Tricolor\Tracker\Carrier\HttpPost(), new \Tricolor\Tracker\Filter\Common(), $params);
        \Tricolor\Tracker\Trace::watch('CallApi');
        $this->curl($url, $params);
        \Tricolor\Tracker\Trace::watch('RecvApi');
    }

    public function curl($url, $params, $postOrGet = 'post')
    {

    }
}