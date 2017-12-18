<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Reporter;
use \Tricolor\Tracker\Sampler\Attachment\Server as ServerAttach;
use \Tricolor\Tracker\Deliverer\HttpPost as PostDeliverer;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:43
 */
/**
 * 模拟Api端
 */
class Api
{
    public function __construct()
    {
        // 在 autoload之后、业务调用之前 加入
        // 也可以把配置写在其他class里
        Reporter::$reporter = array('\Shop\RabbitMQ\Publisher', 'pubLog');
        Reporter::$reportParams = array('shop.log.trace', '{param}', 8);
        Trace::instance()
            ->setAttach(new ServerAttach($_SERVER))
            ->recv(new PostDeliverer($_POST))
            ->watch();
    }

    public function output($output)
    {
        // 在输出、api返回的地方调用
        Trace::instance()->watch('apiReturn', $output);
        echo json_encode($output);
        die();
    }
}