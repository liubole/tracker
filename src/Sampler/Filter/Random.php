<?php
namespace Tricolor\Tracker\Sampler\Filter;
use Tricolor\Tracker\Config\Filter;

/**
 *
 * User: Tricolor
 * DateTime: 2017/12/17 14:37
 */
class Random implements Base
{
    private $rate = null;

    public function __construct($rate = null)
    {
        $this->rate = $rate;
    }

    public function sample()
    {
        return rand(1, 100) <= (isset($this->rate) ? (int)$this->rate : (int)Filter::$randomFilterRate);
    }
}