<?php
use \Tricolor\Tracker\Trace;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 21:43
 */

/**
 * 模拟 M_Controller
 */
class M_Controller
{
    /**
     * 模拟 apiReturn 方法
     */
    public function apiReturn($output)
    {
        if (class_exists('\Tricolor\Tracker\Trace'))
            \Tricolor\Tracker\Trace::watch('apiReturn');
        echo json_encode($output);
        die();
    }
}