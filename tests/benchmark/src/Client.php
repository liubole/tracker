<?php
/**
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:43
 */
namespace Tricolor\Tracker\Test\Libs;
use Tricolor\Tracker\Filter\Random;
use Tricolor\Tracker\Filter\Simple;
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Config\Collector;
use Tricolor\Tracker\Filter\Random as RandomFilter;

class Client
{
    public function __construct()
    {
        // 在 autoload之后、实际业务开始前 加入

        // 记录过滤
        Trace::recordFilter(
            new Random(100),
            new Simple($_SERVER['HTTP_HOST'], '/https?:\/\/www\.ci123\.com/', false)
        );

        // 数据上传
        Collector::$reporter = function ($info) {
            $call = array('\Tricolor\RabbitMQ\Publisher', 'pubLog');
            if (is_callable($call)) {
                call_user_func_array($call, array('log.trace', $info, 8));
            }
        };

        // 初始化
        Trace::init();

        Trace::instance()
            ->tag('Init')
            ->run();
    }

    public function output($output)
    {
        // 在输出、api返回的地方调用
        Trace::instance()
            ->tag('Return')
            ->record('output', $output)
            ->run();
        echo json_encode($output);
        die();
    }
}

