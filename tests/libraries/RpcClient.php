<?php
use Tricolor\Tracker\Trace;
use Tricolor\Tracker\Sampler\Attachment\Server as ServerAttach;
use Tricolor\Tracker\Deliverer\HttpPost as PostDeliverer;
/**
 * Created by PhpStorm.
 * User: Tricolor
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
        Trace::instance()
            ->addAttachments(new ServerAttach())
            ->delivery(new PostDeliverer($params))
            ->tag('CallApi')
            ->watch();
        $output = $this->curl($url, $params);
        Trace::instance()
            ->addAttachments(new ServerAttach())
            ->tag('RecvApi')
            ->watch($output);
    }

    public function curl($url, $params, $postOrGet = 'post')
    {
        return "";
    }
}