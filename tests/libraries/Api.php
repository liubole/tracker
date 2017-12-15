<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Define;
use \Tricolor\Tracker\Config\Values;
use \Tricolor\Tracker\Carrier\HttpPost;
use \Tricolor\Tracker\Filter\Common;
/**
 * Created by PhpStorm.
 * User: flyhi
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
        Values::handler(function ($key) {
            switch ($key) {
                case Define::mqReporter:
                    return array('\Shop\RabbitMQ\Publisher', 'pubLog');
                case Define::mqRoutingKey:
                    return 'shop.log.trace';
                default:
                    return Values::get($key);
            }
        });
        Trace::recv(new HttpPost(), new Common(), $_POST);
    }

    public function output($output)
    {
        // 在输出、api返回的地方调用
        Trace::watch('apiReturn');
        echo json_encode($output);
        die();
    }
}