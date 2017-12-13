<?php
namespace Tricolor\Tracker;
/**
 * Created by PhpStorm.
 * User: flyhi
 * Date: 2017/11/4
 * Time: 20:41
 */
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Common\UID;
use Tricolor\Tracker\Reporter\Base as BaseReporter;
use Tricolor\Tracker\Reporter\MQ as MQReporter;

class Trace
{
    private static $reporter;

    /**
     * 生成跟踪上下文
     * @param $filter \Tricolor\Tracker\Filter\Base
     */
    public static function init($filter)
    {
        if (!$filter) return;
        if (!method_exists($filter, 'init') || !$filter->init()) return;

        Context::$TraceId = UID::create();
        Context::$RpcId = '0';

        Trace::watch('Init');
    }

    /**
     * 接收传递过来的跟踪上下文
     * @param $carrier
     * @param $filter \Tricolor\Tracker\Filter\Base
     * @param $post
     * @return mixed
     */
    public static function recv($carrier, $filter, &$post)
    {
        if (!$carrier || !$filter) return $post;
        if (!method_exists($filter, 'recv') || !$filter->recv()) return $post;

        $post = call_user_func(array($carrier, 'unpack'), $post);

        Context::$RpcId .= '.0';

        Trace::watch('Recv');
        return $post;
    }

    /**
     * 自定义记录数据
     * @param $tag
     */
    public static function watch($tag)
    {
        if (!Context::$TraceId) return;
        Context::$TAG = $tag;
        Context::$At = microtime(true);
        if (!Trace::$reporter) Trace::$reporter = new MQReporter();
        call_user_func(array(Trace::$reporter, 'report'));
        Context::$RpcId = StrUtils::step(Context::$RpcId);
    }

    /**
     * @param $carrier
     * @param $filter \Tricolor\Tracker\Filter\Base
     * @param $p
     * @return mixed
     */
    public static function attach($carrier, $filter, &$p)
    {
        if (!Context::$TraceId || !$carrier || !$filter) return $p;
        if (!method_exists($filter, 'attach') || !$filter->attach()) return $p;
        return $p = call_user_func(array($carrier, 'pack'), $p);
    }

    /**
     * 设置数据处理者
     * @param $reporter
     * @return null|BaseReporter
     * @return $cache 存储目录
     */
    public static function setReporter($reporter)
    {
        if ($reporter instanceof BaseReporter) {
            return (Trace::$reporter = $reporter);
        }
        return null;
    }
}