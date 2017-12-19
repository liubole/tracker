<?php
use \Tricolor\Tracker\Trace;
use Tricolor\Tracker\Sampler\Attachment\Server as ServerAttach;
use Tricolor\Tracker\Sampler\Filter\Random as RandomFilter;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 21:43
 */
/**
 * 模拟客户端
 */
class Client
{
    public function __construct()
    {
        // 在 autoload之后、实际业务开始前 加入
        Trace::instance()
            ->addAttachments(new ServerAttach())
            ->init(new RandomFilter(100))
            ->watch();
    }

    public function output($output)
    {
        // 在输出、api返回的地方调用
        Trace::instance()
            ->addAttachments(new ServerAttach())
            ->tag('Return')
            ->watch($output);
        echo json_encode($output);
        die();
    }
}

