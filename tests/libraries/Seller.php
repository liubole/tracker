<?php
/**
 * watch('start/end')
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:43
 */
/**
 * 模拟seller端
 */
class Seller extends M_Controller
{
    public function __construct()
    {
        // 在 webroot/index.php 开头,autoload之后 加入
        if (class_exists('\Tricolor\Tracker\Trace')) {
            \Tricolor\Tracker\Trace::init(new \Tricolor\Tracker\Filter\Common());
        }
    }
}

