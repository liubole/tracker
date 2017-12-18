<?php
namespace Tricolor\Tracker\Sampler\Filter;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 18:13
 */
class UriFilter implements Base
{
    private $excludeReq = array();
    private $server;

    public function __construct(&$server = null)
    {
        $this->server = $server ? $server : $_SERVER;
    }

    public function sample()
    {
        if ($this->excludeReq) {
            return $this->checkExcludeReq();
        }
        return true;
    }

    public function excludeReq($pattern, $is_regex = true)
    {
        if ($pattern) {
            $this->excludeReq[] = array(
                'pattern' => $pattern,
                'is_regex' => (bool)$is_regex,
            );
        }
        return $this;
    }

    /**
     * 任何一个符合，返回false
     * @return bool
     */
    private function checkExcludeReq()
    {
        foreach ($this->excludeReq as $exclude) {
            if ($exclude['is_regex']) {
                $res = preg_match($exclude['pattern'], $this->server['REQUEST_URI']);
            } else {
                $res = $exclude['pattern'] === $this->server['REQUEST_URI'];
            }
            if ($res) return false;
        }
        return true;
    }

}