<?php
use \Tricolor\Tracker\Trace;
use \Tricolor\Tracker\Filter\Common;
/**
 * Created by PhpStorm.
 * User: flyhi
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
        Trace::init(new Common());
    }

    public function output($output)
    {
        // 在输出、api返回的地方调用
        Trace::watch('apiReturn');
        echo json_encode($output);
        die();
    }
}

