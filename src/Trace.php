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
use Tricolor\Tracker\Config\Env;
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
        $this->setEnv();
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
        $this->setEnv();
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
        if (!$this->isTraceOn() || !$deliverer->pack()) {
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
        if (!$this->isTraceOn() || !$this->watch) {
            return false;
        }
        Context::$At = Time::get();
        Reporter::report($this->getReport(func_get_args()));
        StrUtils::rpcStep(Context::$RpcId);
        return true;
    }

    /**
     * add filters
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
     * set tag
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
     * add attachments
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

    /**
     * get users self-defined watch info
     * @param $args
     * @return array
     */
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

    private function isTraceOn()
    {
        if (is_int(Env::$force)) {
            $on = (Env::$force & Env::ON) === Env::ON;
            if ($on || (Env::$force & Env::OFF) === Env::OFF) {
                return $on;
            }
        }
        isset(Context::$On) OR (Context::$On = 1);
        return ((bool)Context::$On) && ((bool)Context::$TraceId);
    }

    /**
     * if AFFECT_ALL is set up，Context::$On will be reset
     */
    private function setEnv()
    {
        if (is_int(Env::$force)) {
            if ((Env::$force & Env::AFFECT_ALL) === Env::AFFECT_ALL) {
                $on = (Env::$force & Env::ON) === Env::ON;
                if ($on || (Env::$force & Env::OFF) === Env::OFF) {
                    Context::$On = (int)$on;
                }
            }
        }
        isset(Context::$On) OR (Context::$On = 1);
        return $this;
    }

}