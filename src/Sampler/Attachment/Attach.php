<?php
namespace Tricolor\Tracker\Sampler\Attachment;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/20 11:13
 */
class Attach
{
    public function get($prefix = '')
    {
        $attach = array();
        $define = new \ReflectionClass('\Tricolor\Tracker\Config\Attachment');
        foreach ($define->getStaticProperties() as $name => $bool) {
            if (!$bool || !method_exists($this, $name = $prefix . $name)) continue;
            if ($res = $this->$name()) $attach = array_merge($attach, $res);
        }
        return $attach;
    }
}