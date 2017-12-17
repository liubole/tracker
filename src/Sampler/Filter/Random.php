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
    public function sample($rate = null)
    {
        return rand(1, 100) <= isset($rate) ?
            (int)$rate :
            (int)Filter::$randomFilterRate;
    }
}