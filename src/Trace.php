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
    private $tag = "";
    /**
     * @var array \Tricolor\Tracker\Sampler\Attachment\Base
     */
    private $attachments = array();
    /**
     * @var array \Tricolor\Tracker\Sampler\Filter\Base
     */
    private $filters = array();

    private $watch = true;

    /**
     * 生成跟踪上下文
     * @param $filter \Tricolor\Tracker\Sampler\Filter\Base
     * @return $this
     */
    public function init($filter = null)
    {
        $this->addFilters($filter);
        foreach ($this->filters as $f) {
            if (!$f->sample()) {
                $this->watch = false;
                return $this;
            }
        }
        Context::$TraceId = UID::create();
        StrUtils::rpcInit(Context::$RpcId);
        $this->tag(ucfirst(__FUNCTION__), false);
        $this->watch = true;
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
            if ($auto_init) {
                return $this->init();
            }
            $this->watch = false;
            return $this;
        }
        StrUtils::rpcNext(Context::$RpcId);
        $this->tag(ucfirst(__FUNCTION__), false);
        $this->watch = true;
        return $this;
    }

    /**
     * @param $deliverer \Tricolor\Tracker\Deliverer\Base
     * @return $this
     */
    public function delivery($deliverer)
    {
        if (!Context::$TraceId || !$deliverer->pack()) {
            $this->watch = false;
            return $this;
        }
        $this->tag(ucfirst(__FUNCTION__), false);
        $this->watch = true;
        return $this;
    }

    /**
     * @param null $_ extra data
     * @return bool
     */
    public function watch($_ = null)
    {
        if (!Context::$TraceId || !$this->watch) {
            return false;
        }
        Context::$At = Time::get();
        Reporter::report($this->getReport(func_get_args()));
        StrUtils::rpcStep(Context::$RpcId);
        return true;
    }

    /**
     * 添加过滤器
     * @param null $_ array \Tricolor\Tracker\Sampler\Filter\Base
     * @return $this
     */
    public function addFilters($_ = null)
    {
        foreach (func_get_args() as $filter) {
            if ($filter instanceof FilterBase) {
                $this->filters[] = $filter;
            }
        }
        return $this;
    }

    /**
     * 设置tag
     * @param $tag string
     * @param $rewrite bool
     * @return $this
     */
    public function tag($tag, $rewrite = true)
    {
        if ($rewrite || !$this->tag) {
            $this->tag = (string)$tag;
        }
        return $this;
    }

    /**
     * 附件获取
     * @param $_ \Tricolor\Tracker\Sampler\Attachment\Base
     * @return $this
     */
    public function addAttachments($_ = null)
    {
        foreach (func_get_args() as $attachment) {
            if ($attachment instanceof AttachBase) {
                $this->attachments[] = $attachment;
            }
        }
        return $this;
    }

    /**
     * @return Trace
     */
    public static function instance()
    {
        return new Trace();
    }

    private function getReport($args)
    {
        $attachments = array();
        foreach ($this->attachments as $attachment) {
            if ($attach = $attachment->getAll()) {
                if (is_array($attach)) {
                    $attachments = array_merge($attachments, $attach);
                } else {
                    $attachments[] = $attach;
                }
            }
        }
        return array_merge(Context::toArray(), array(
            'Tag' => $this->tag,
            'Attach' => $attachments,
            'Extra' => $args,
        ));
    }

}