<?php
namespace Tricolor\Tracker\Sampler\Filter;
use \Tricolor\Tracker\Common\Ip as IpCommon;
/**
 *
 * User: Tricolor
 * DateTime: 2017/12/18 18:25
 */
class Ip implements Base
{
    private $excludeIp = array();
    private $onlyIp = array();

    public function sample()
    {
        if ($this->onlyIp) {
            return $this->checkOnlyIp();
        }
        if ($this->excludeIp) {
            return $this->checkExcludeIp();
        }
        return true;
    }

    public function onlyIp($pattern, $is_regex = true)
    {
        if ($pattern) {
            $this->onlyIp[] = array(
                'pattern' => $pattern,
                'is_regex' => (bool)$is_regex,
            );
        }
        return $this;
    }

    public function excludeIp($pattern, $is_regex = true)
    {
        if ($pattern) {
            $this->excludeIp[] = array(
                'pattern' => $pattern,
                'is_regex' => (bool)$is_regex,
            );
        }
        return $this;
    }

    /**
     * 任何一个符合，返回true
     * @return bool
     */
    private function checkOnlyIp()
    {
        $ip = IpCommon::getIp();
        foreach ($this->onlyIp as $only) {
            if ($only['is_regex']) {
                $res = preg_match($only['pattern'], $ip);
            } else {
                $res = $only['pattern'] === $ip;
            }
            if ($res) return true;
        }
        return false;
    }

    /**
     * 任何一个符合，返回false
     * @return bool
     */
    private function checkExcludeIp()
    {
        $ip = IpCommon::getIp();
        foreach ($this->excludeIp as $exclude) {
            if ($exclude['is_regex']) {
                $res = preg_match($exclude['pattern'], $ip);
            } else {
                $res = $exclude['pattern'] === $ip;
            }
            if ($res) return false;
        }
        return true;
    }
}