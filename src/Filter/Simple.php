<?php
/**
 * User: Tricolor
 * Date: 2017/12/26
 * Time: 12:56
 */
namespace Tricolor\Tracker\Filter;

class Simple implements Base
{
    private $allow = array();
    private $deny = array();

    public function __construct()
    {
    }

    /**
     * sample or not
     * @return bool
     */
    public function sample()
    {
        if (!$this->deny && !$this->allow) {
            return false;
        }
        if ($this->deny && $this->any($this->deny)) {
            return false;
        }
        if ($this->allow) {
            return $this->any($this->allow);
        }
        return true;
    }

    public function deny($value, $pattern, $is_regex = true)
    {
        if ($pattern) {
            $this->deny[] = array(
                'value' => $value,
                'pattern' => $pattern,
                'is_regex' => (bool)$is_regex,
            );
        }
        return $this;
    }

    public function allow($value, $pattern, $is_regex = true)
    {
        if ($pattern) {
            $this->allow[] = array(
                'value' => $value,
                'pattern' => $pattern,
                'is_regex' => (bool)$is_regex,
            );
        }
        return $this;
    }

    /**
     * @param $check array
     * @return bool
     */
    private function any($check)
    {
        foreach ($check as $v) {
            $value = $v['value'];
            if ($v['is_regex']) {
                $res = preg_match($v['pattern'], $value);
            } else {
                $res = $v['pattern'] === $value;
            }
            if ($res) return true;
        }
        return false;
    }
}