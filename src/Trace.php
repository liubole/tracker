<?php
namespace Tricolor\Tracker;
/**
 * Created by PhpStorm.
 * User: Tricolor
 * Date: 2017/11/4
 * Time: 20:41
 */
use Tricolor\Tracker\Common\StrUtils;
use Tricolor\Tracker\Common\Time;
use Tricolor\Tracker\Common\UID;
use Tricolor\Tracker\Sampler\Filter\Base as FilterBase;
use Tricolor\Tracker\Sampler\Attachment\base as AttachBase;

class Trace
{
    private $tag = "Inspect";
    /**
     * @var \Tricolor\Tracker\Sampler\Attachment\Base
     */
    private $attachment;
    /**
     * @var array \Tricolor\Tracker\Sampler\Filter\Base
     */
    private $filter = array();

    /**
     * 生成跟踪上下文
     * @param $filter \Tricolor\Tracker\Sampler\Filter\Base
     * @return $this
     */
    public function init($filter = null)
    {
        $this->setFilter($filter);
        foreach ($this->filter as $f)
            if (!$f->sample()) return $this;
        Context::$TraceId = UID::create();
        StrUtils::rpcInit(Context::$RpcId);
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
        StrUtils::rpcNext(Context::$RpcId);
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
        Context::$At = Time::get();
        $report = array_merge(Context::toArray(), array(
            'Tag' => $this->tag,
            'Attach' => $this->attachment ? $this->attachment->getAll() : null,
            'Extra' => func_num_args() ? func_get_args() : null,
        ));
        Reporter::report($report);
        StrUtils::rpcStep(Context::$RpcId);
        return true;
    }

    /**
     * 设置过滤器
     * @param $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        if ($filter instanceof FilterBase)
            $this->filter[] = $filter;
        return $this;
    }

    /**
     * 设置tag
     * @param $tag
     * @return $this
     */
    public function tag($tag)
    {
        $this->tag = (string)$tag;
        return $this;
    }

    /**
     * 附件获取
     * @param $attachment
     * @return $this
     */
    public function setAttach($attachment)
    {
        if ($attachment instanceof AttachBase)
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