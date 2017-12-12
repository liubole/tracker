<?php
use \Tricolor\Tracker\Trace;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:43
 */
/**
 * 模拟Api端
 */
class Api extends M_Controller
{

    public function __construct()
    {
        // 在 webroot/index.php 开头，autoload之后 加入
        if (class_exists('\Tricolor\Tracker\Trace'))
            \Tricolor\Tracker\Trace::recv(new \Tricolor\Tracker\Carrier\HttpPost(), new \Tricolor\Tracker\Filter\Common(), $_POST);
    }
}