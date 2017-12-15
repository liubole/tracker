<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Carrier\HttpPost;
use \Tricolor\Tracker\Filter\Common;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:49
 */

/**
 * 模拟api调用客户端
 * Class RpcClient
 */
class RpcClient
{
    public function exec($url, $params)
    {
        // ...
        $params = Trace::attach(new HttpPost(), new Common(), $params);
        Trace::watch('CallApi');
        $this->curl($url, $params);
        Trace::watch('RecvApi');
    }

    public function curl($url, $params, $postOrGet = 'post')
    {

    }
}