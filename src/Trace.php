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

class Trace
{
    private $tag = "Inspect";
    /**
     * @var \Tricolor\Tracker\Sampler\Attachment\Base
     */
    private $attachment;
    /**
     * @var \Tricolor\Tracker\Sampler\Filter\Base
     */
    private $filter;

    /**
     * 生成跟踪上下文
     * @param $filter \Tricolor\Tracker\Sampler\Filter\Base
     * @return $this
     */
    public function init($filter = null)
    {
        if ($filter) $this->filter = $filter;
        if (!$filter || !$filter->sample()) return $this;
        Context::$TraceId = UID::create();
        Context::$RpcId = '0';
        $this->tag = ucfirst(__FUNCTION__);
        return $this;
    }

    /**
     * @param $deliverer \Tricolor\Tracker\Deliverer\Base
     * @param bool $auto_init
     * @return $this
     */
    public function recv($deliverer, $auto_init = false)
    {
        if (!$deliverer->unpack()) {
            return $auto_init ? $this->init() : $this;
        }
        Context::$RpcId .= '.0';
        $this->tag = ucfirst(__FUNCTION__);
        return $this;
    }

    /**
     * @param $deliverer \Tricolor\Tracker\Deliverer\Base
     * @return $this
     */
    public function deliver($deliverer)
    {
        if (!Context::$TraceId) return $this;
        if (!$deliverer->pack()) return $this;
        $this->tag = ucfirst(__FUNCTION__);
        return $this;
    }

    /**
     * @param null $_ extra data
     * @return bool
     */
    public function watch($_ = null)
    {
        if (!Context::$TraceId) return false;
        Context::$At = microtime(true);
        $report = array_merge(Context::toArray(), array(
            'Tag' => $this->tag,
            'Attach' => $this->attachment ? $this->attachment->getAll() : null,
            'Extra' => func_num_args() ? func_get_args() : null,
        ));
        Reporter::report($report);
        Context::$RpcId = StrUtils::step(Context::$RpcId);
        return true;
    }

    /**
     * 设置过滤器
     * @param $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * 附件获取
     * @param $attachment
     * @return $this
     */
    public function setAttach($attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * @return Trace
     */
    public static function instance()
    {
        return new Trace();
    }
}